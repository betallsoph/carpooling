<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ContributionRecord;
use App\Models\TripInstance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Hành khách gửi yêu cầu đặt chỗ
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'trip_instance_id' => 'required|exists:trip_instances,id',
            'seats_requested' => 'nullable|integer|min:1|max:4',
            'pickup_name' => 'required|string|max:255',
            'pickup_address' => 'required|string',
            'pickup_lng' => 'required|numeric|between:-180,180',
            'pickup_lat' => 'required|numeric|between:-90,90',
            'dropoff_name' => 'required|string|max:255',
            'dropoff_address' => 'required|string',
            'dropoff_lng' => 'required|numeric|between:-180,180',
            'dropoff_lat' => 'required|numeric|between:-90,90',
            'note' => 'nullable|string|max:500',
        ]);

        $trip = TripInstance::findOrFail($validated['trip_instance_id']);
        $seats = $validated['seats_requested'] ?? 1;

        if ($trip->available_seats < $seats) {
            return response()->json([
                'status' => 'error',
                'message' => 'Chuyến xe không còn đủ số ghế trống yêu cầu.',
            ], 422);
        }

        // Tạm tính chi phí đóng góp nếu được chấp nhận
        $estCost = $trip->calculateCostPerPerson($seats);

        $booking = new Booking([
            'trip_instance_id' => $trip->id,
            'passenger_id' => $request->user()->id,
            'seats_requested' => $seats,
            'pickup_name' => $validated['pickup_name'],
            'pickup_address' => $validated['pickup_address'],
            'dropoff_name' => $validated['dropoff_name'],
            'dropoff_address' => $validated['dropoff_address'],
            'status' => 'requested',
            'calculated_contribution' => $estCost,
            'note' => $validated['note'] ?? null,
        ]);

        $booking->pickup_point = DB::raw("ST_SetSRID(ST_MakePoint({$validated['pickup_lng']}, {$validated['pickup_lat']}), 4326)");
        $booking->dropoff_point = DB::raw("ST_SetSRID(ST_MakePoint({$validated['dropoff_lng']}, {$validated['dropoff_lat']}), 4326)");
        $booking->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã gửi yêu cầu ghép xe đến chủ xe.',
            'data' => $booking,
        ], 201);
    }

    /**
     * Chủ xe chấp nhận yêu cầu ghép chuyến
     * Tự động sinh bản ghi VietQR chia sẻ chi phí
     */
    public function accept(Request $request, Booking $booking): JsonResponse
    {
        $trip = $booking->tripInstance;
        $driverProfile = $trip->driverProfile;

        if ($driverProfile->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Bạn không có quyền duyệt yêu cầu này.'], 403);
        }

        if ($booking->status !== 'requested') {
            return response()->json(['status' => 'error', 'message' => 'Yêu cầu này không ở trạng thái chờ duyệt.'], 422);
        }

        if ($trip->available_seats < $booking->seats_requested) {
            return response()->json(['status' => 'error', 'message' => 'Không còn đủ ghế trống.'], 422);
        }

        DB::transaction(function () use ($booking, $trip, $driverProfile) {
            $booking->status = 'accepted';
            $booking->accepted_at = now();

            // Trừ số ghế trống
            $trip->available_seats -= $booking->seats_requested;

            // Tính lại số tiền đóng góp công bằng giữa lái xe và toàn bộ khách
            $costPerPerson = $trip->calculateCostPerPerson(0);
            $trip->current_cost_per_person = $costPerPerson;
            $trip->save();

            $booking->calculated_contribution = $costPerPerson * $booking->seats_requested;
            $booking->save();

            // Tạo VietQR Record cho giao dịch trực tiếp
            $transferContent = 'CP' . str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT);
            $qrUrl = ContributionRecord::generateVietQrUrl(
                bankCode: $driverProfile->bank_code ?? 'VCB',
                accountNo: $driverProfile->bank_account_no ?? '1023456789',
                accountName: $driverProfile->bank_account_name ?? 'CHU XE CARPOOL',
                amount: $booking->calculated_contribution,
                transferContent: $transferContent
            );

            ContributionRecord::updateOrCreate(
                ['booking_id' => $booking->id],
                [
                    'trip_instance_id' => $trip->id,
                    'passenger_id' => $booking->passenger_id,
                    'driver_profile_id' => $driverProfile->id,
                    'amount' => $booking->calculated_contribution,
                    'transfer_content' => $transferContent,
                    'vietqr_url' => $qrUrl,
                    'status' => 'pending',
                ]
            );
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Đã chấp nhận yêu cầu ghép xe. Mã VietQR chia sẻ chi phí đã được tạo.',
            'data' => $booking->load('contributionRecord'),
        ]);
    }

    /**
     * Khách xác nhận "Đã chuyển tiền đóng góp qua VietQR"
     */
    public function confirmTransfer(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->passenger_id !== $request->user()->id) {
            return response()->json(['status' => 'error', 'message' => 'Chỉ hành khách mới có thể xác nhận đã chuyển.'], 403);
        }

        $record = $booking->contributionRecord;
        if (!$record) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy bản ghi đóng góp.'], 404);
        }

        $record->update([
            'status' => 'passenger_confirmed',
            'passenger_confirmed_at' => now(),
            'proof_image_url' => $request->input('proof_image_url'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Đã ghi nhận bạn đã chuyển khoản. Chờ chủ xe xác nhận nhận tiền.',
            'data' => $record,
        ]);
    }

    /**
     * Chủ xe xác nhận "Đã nhận tiền đóng góp"
     */
    public function confirmReceipt(Request $request, Booking $booking): JsonResponse
    {
        $driverProfile = $booking->tripInstance->driverProfile;
        if ($driverProfile->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Chỉ chủ xe mới có thể xác nhận đã nhận.'], 403);
        }

        $record = $booking->contributionRecord;
        if (!$record) {
            return response()->json(['status' => 'error', 'message' => 'Không tìm thấy bản ghi đóng góp.'], 404);
        }

        $record->update([
            'status' => 'completed',
            'driver_confirmed_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Chủ xe đã xác nhận nhận đủ tiền chia sẻ chi phí.',
            'data' => $record,
        ]);
    }
}
