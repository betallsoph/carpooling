<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\TripInstance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Hành khách gửi yêu cầu ghép xe đi chung
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
                'message' => 'Xe không còn đủ số ghế trống yêu cầu.',
            ], 422);
        }

        $booking = new Booking([
            'trip_instance_id' => $trip->id,
            'passenger_id' => $request->user()->id,
            'seats_requested' => $seats,
            'pickup_name' => $validated['pickup_name'],
            'pickup_address' => $validated['pickup_address'],
            'dropoff_name' => $validated['dropoff_name'],
            'dropoff_address' => $validated['dropoff_address'],
            'status' => 'requested',
            'calculated_contribution' => 0, // Không áp giá, tiền xăng tự thỏa thuận tùy tâm
            'note' => $validated['note'] ?? null,
        ]);

        $booking->pickup_point = DB::raw("ST_SetSRID(ST_MakePoint({$validated['pickup_lng']}, {$validated['pickup_lat']}), 4326)");
        $booking->dropoff_point = DB::raw("ST_SetSRID(ST_MakePoint({$validated['dropoff_lng']}, {$validated['dropoff_lat']}), 4326)");
        $booking->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã gửi lời hẹn đi cùng xe đến chủ xe.',
            'data' => $booking,
        ], 201);
    }

    /**
     * Chủ xe đồng ý cho đi cùng
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
            return response()->json(['status' => 'error', 'message' => 'Xe không còn đủ ghế trống.'], 422);
        }

        DB::transaction(function () use ($booking, $trip) {
            $booking->status = 'accepted';
            $booking->accepted_at = now();
            $booking->save();

            // Cập nhật số ghế trống còn lại
            $trip->available_seats -= $booking->seats_requested;
            $trip->save();
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Đã đồng ý cho đồng nghiệp đi cùng xe. Hai bên có thể nhắn tin hẹn giờ và điểm đón cụ thể.',
            'data' => $booking,
        ]);
    }

    /**
     * Chủ xe từ chối lời hẹn
     */
    public function decline(Request $request, Booking $booking): JsonResponse
    {
        $driverProfile = $booking->tripInstance->driverProfile;

        if ($driverProfile->user_id !== $request->user()->id && $request->user()->role !== 'admin') {
            return response()->json(['status' => 'error', 'message' => 'Bạn không có quyền từ chối yêu cầu này.'], 403);
        }

        $booking->update([
            'status' => 'declined',
            'rejection_reason' => $request->input('reason', 'Chủ xe không tiện đón ở chặng này.'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Đã từ chối lời hẹn đi chung.',
            'data' => $booking,
        ]);
    }
}
