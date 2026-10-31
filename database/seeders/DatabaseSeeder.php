<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\ContributionRecord;
use App\Models\DriverProfile;
use App\Models\TripInstance;
use App\Models\TripTemplate;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo tài khoản Admin Ops
        $admin = User::firstOrCreate(
            ['email' => 'admin@carpooling.vn'],
            [
                'name' => 'Quản trị viên Hệ thống',
                'password' => Hash::make('password'),
                'phone' => '0901234567',
                'role' => 'admin',
                'is_verified' => true,
            ]
        );

        // 2. Tạo tài khoản Chủ xe hạt giống (Driver)
        $driverUser = User::firstOrCreate(
            ['email' => 'driver@carpooling.vn'],
            [
                'name' => 'Nguyễn Minh Tuấn',
                'password' => Hash::make('password'),
                'phone' => '0912345678',
                'role' => 'driver',
                'gender' => 'male',
                'emergency_contact_name' => 'Nguyễn Thị Hoa',
                'emergency_contact_phone' => '0912345679',
                'is_verified' => true,
            ]
        );

        $driverProfile = DriverProfile::firstOrCreate(
            ['user_id' => $driverUser->id],
            [
                'license_number' => '790123456789',
                'license_class' => 'B2',
                'verification_status' => 'approved',
                'verified_at' => now(),
                'bank_name' => 'Vietcombank',
                'bank_code' => 'VCB',
                'bank_account_no' => '1023456789',
                'bank_account_name' => 'NGUYEN MINH TUAN',
                'rating_avg' => 4.95,
                'rating_count' => 24,
                'total_rides_completed' => 38,
            ]
        );

        $vehicle = Vehicle::firstOrCreate(
            ['plate_number' => '61A-889.92'],
            [
                'driver_profile_id' => $driverProfile->id,
                'make' => 'Toyota',
                'model' => 'Vios G 2023',
                'color' => 'Trắng',
                'seats_capacity' => 5,
                'seats_available_for_carpool' => 3,
                'is_active' => true,
            ]
        );

        // 3. Tạo Tuyến mẫu (Trip Template) Bình Dương -> Quận 1 T2-T6
        // Coordinates:
        // Origin: TP Mới Bình Dương (106.6710, 11.0535)
        // Waypoint: Lotte Mart Lái Thiêu (106.7025, 10.9021)
        // Waypoint: Ngã tư Hàng Xanh (106.7118, 10.8012)
        // Dest: Nhà Hát Thành Phố Quận 1 (106.7032, 10.7769)
        $template = new TripTemplate([
            'driver_profile_id' => $driverProfile->id,
            'vehicle_id' => $vehicle->id,
            'title' => 'Thành phố mới Bình Dương ➔ Quận 1 sáng T2-T6',
            'origin_name' => 'Trung tâm hành chính tỉnh Bình Dương',
            'origin_address' => 'Số 1 Lê Lợi, P. Hòa Phú, TP. Thủ Dầu Một, Bình Dương',
            'destination_name' => 'Nhà Hát Thành Phố',
            'destination_address' => '07 Công Trường Lam Sơn, P. Bến Nghé, Quận 1, TP.HCM',
            'total_distance_km' => 34.5,
            'estimated_duration_minutes' => 65,
            'toll_fee' => 15000, // Vé trạm thu phí Lái Thiêu
            'fuel_rate_per_km' => 1800, // Đơn giá xăng định mức theo km
            'available_seats' => 3,
            'max_detour_minutes' => 12,
            'days_of_week' => [1, 2, 3, 4, 5],
            'departure_time' => '06:45:00',
            'is_active' => true,
        ]);

        $template->origin_point = DB::raw("ST_SetSRID(ST_MakePoint(106.6710, 11.0535), 4326)");
        $template->destination_point = DB::raw("ST_SetSRID(ST_MakePoint(106.7032, 10.7769), 4326)");
        $template->route_line = DB::raw("ST_SetSRID(ST_MakeLine(ARRAY[
            ST_MakePoint(106.6710, 11.0535),
            ST_MakePoint(106.7025, 10.9021),
            ST_MakePoint(106.7118, 10.8012),
            ST_MakePoint(106.7032, 10.7769)
        ]), 4326)");
        $template->save();

        // 4. Tạo Chuyến đi thực tế (Trip Instance) cho ngày làm việc kế tiếp
        $instance = new TripInstance([
            'trip_template_id' => $template->id,
            'driver_profile_id' => $driverProfile->id,
            'vehicle_id' => $vehicle->id,
            'date' => now()->isWeekend() ? now()->next('Monday') : now()->addDay(),
            'departure_time' => '06:45:00',
            'status' => 'scheduled',
            'origin_name' => $template->origin_name,
            'origin_address' => $template->origin_address,
            'destination_name' => $template->destination_name,
            'destination_address' => $template->destination_address,
            'total_distance_km' => $template->total_distance_km,
            'estimated_duration_minutes' => $template->estimated_duration_minutes,
            'toll_fee' => $template->toll_fee,
            'fuel_rate_per_km' => $template->fuel_rate_per_km,
            'total_seats' => 3,
            'available_seats' => 2, // Đã nhận 1 khách
            'max_detour_minutes' => 12,
            'current_cost_per_person' => 38000, // (34.5*1800 + 15000) / (1 lái + 1 khách) = 77100 / 2 ≈ 38k
        ]);

        $instance->origin_point = DB::raw("ST_SetSRID(ST_MakePoint(106.6710, 11.0535), 4326)");
        $instance->destination_point = DB::raw("ST_SetSRID(ST_MakePoint(106.7032, 10.7769), 4326)");
        $instance->route_line = DB::raw("ST_SetSRID(ST_MakeLine(ARRAY[
            ST_MakePoint(106.6710, 11.0535),
            ST_MakePoint(106.7025, 10.9021),
            ST_MakePoint(106.7118, 10.8012),
            ST_MakePoint(106.7032, 10.7769)
        ]), 4326)");
        $instance->save();

        // 5. Tạo Hành khách (Passenger)
        $passengerUser = User::firstOrCreate(
            ['email' => 'passenger@carpooling.vn'],
            [
                'name' => 'Lê Phương Thảo',
                'password' => Hash::make('password'),
                'phone' => '0938112233',
                'role' => 'passenger',
                'gender' => 'female',
                'emergency_contact_name' => 'Lê Văn Ba',
                'emergency_contact_phone' => '0938112234',
                'is_verified' => true,
            ]
        );

        // 6. Tạo Booking & VietQR Record
        $booking = new Booking([
            'trip_instance_id' => $instance->id,
            'passenger_id' => $passengerUser->id,
            'seats_requested' => 1,
            'pickup_name' => 'Lotte Mart Bình Dương',
            'pickup_address' => 'Đại Lộ Bình Dương, P. Lái Thiêu, TP. Thuận An, Bình Dương',
            'dropoff_name' => 'Ngã tư Hàng Xanh',
            'dropoff_address' => 'Điện Biên Phủ, Phường 21, Bình Thạnh, TP.HCM',
            'status' => 'accepted',
            'calculated_contribution' => 38000,
            'detour_minutes_added' => 4,
            'note' => 'Em đứng ngay cổng vào bãi đỗ xe Lotte Mart ạ',
            'accepted_at' => now(),
        ]);
        $booking->pickup_point = DB::raw("ST_SetSRID(ST_MakePoint(106.7025, 10.9021), 4326)");
        $booking->dropoff_point = DB::raw("ST_SetSRID(ST_MakePoint(106.7118, 10.8012), 4326)");
        $booking->save();

        // Sinh bản ghi VietQR chia sẻ chi phí trực tiếp cho chủ xe
        $transferContent = 'CP' . str_pad((string) $booking->id, 6, '0', STR_PAD_LEFT);
        $qrUrl = ContributionRecord::generateVietQrUrl(
            bankCode: $driverProfile->bank_code,
            accountNo: $driverProfile->bank_account_no,
            accountName: $driverProfile->bank_account_name,
            amount: 38000,
            transferContent: $transferContent
        );

        ContributionRecord::create([
            'booking_id' => $booking->id,
            'trip_instance_id' => $instance->id,
            'passenger_id' => $passengerUser->id,
            'driver_profile_id' => $driverProfile->id,
            'amount' => 38000,
            'transfer_content' => $transferContent,
            'vietqr_url' => $qrUrl,
            'status' => 'pending',
        ]);
    }
}
