<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\DriverProfile;
use App\Models\TripInstance;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $activeTripsCount = TripInstance::where('status', 'scheduled')->count();
        $availableSeatsCount = (int) TripInstance::where('status', 'scheduled')->sum('available_seats');
        $totalBookingsCount = Booking::count();
        $pendingBookingsCount = Booking::where('status', 'requested')->count();

        $trips = TripInstance::with(['driverProfile.user', 'vehicle'])
            ->latest()
            ->take(10)
            ->get();

        $bookings = Booking::with(['tripInstance.driverProfile.user', 'passenger'])
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'user',
            'activeTripsCount',
            'availableSeatsCount',
            'totalBookingsCount',
            'pendingBookingsCount',
            'trips',
            'bookings'
        ));
    }

    public function storeTrip(Request $request): RedirectResponse
    {
        $request->validate([
            'origin_name' => ['required', 'string', 'max:255'],
            'destination_name' => ['required', 'string', 'max:255'],
            'departure_time' => ['required', 'string'],
            'available_seats' => ['required', 'integer', 'min:1', 'max:6'],
        ], [
            'origin_name.required' => 'Vui lòng nhập điểm xuất phát (nơi đón).',
            'destination_name.required' => 'Vui lòng nhập điểm đến làm việc.',
            'departure_time.required' => 'Vui lòng chọn giờ xuất phát.',
            'available_seats.required' => 'Vui lòng chọn số ghế còn trống.',
        ]);

        $user = Auth::user();

        // Đảm bảo user có DriverProfile
        $driverProfile = DriverProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'license_number' => '79' . rand(100000000, 999999999),
                'license_class' => 'B2',
                'verification_status' => 'approved',
                'verified_at' => now(),
            ]
        );

        // Đảm bảo user có Vehicle
        $vehicle = Vehicle::firstOrCreate(
            ['driver_profile_id' => $driverProfile->id],
            [
                'plate_number' => $request->input('vehicle_plate', '61A-' . rand(100, 999) . '.' . rand(10, 99)),
                'make' => $request->input('vehicle_make', 'Toyota'),
                'model' => $request->input('vehicle_model', 'Vios G'),
                'color' => 'Trắng',
                'seats_capacity' => 5,
                'seats_available_for_carpool' => (int) $request->input('available_seats', 3),
                'is_active' => true,
            ]
        );

        $isPgsql = DB::getDriverName() === 'pgsql';

        $trip = new TripInstance([
            'driver_profile_id' => $driverProfile->id,
            'vehicle_id' => $vehicle->id,
            'date' => now()->isWeekend() ? now()->next('Monday') : now(),
            'departure_time' => $request->input('departure_time', '06:45:00'),
            'status' => 'scheduled',
            'origin_name' => $request->input('origin_name'),
            'origin_address' => $request->input('origin_address', $request->input('origin_name')),
            'destination_name' => $request->input('destination_name'),
            'destination_address' => $request->input('destination_address', $request->input('destination_name')),
            'total_distance_km' => 32.5,
            'estimated_duration_minutes' => 55,
            'total_seats' => (int) $request->input('available_seats', 3),
            'available_seats' => (int) $request->input('available_seats', 3),
            'max_detour_minutes' => 10,
            'current_cost_per_person' => 0,
        ]);

        $trip->origin_point = $isPgsql 
            ? DB::raw("ST_SetSRID(ST_MakePoint(106.6710, 11.0535), 4326)") 
            : 'POINT(106.6710 11.0535)';
        $trip->destination_point = $isPgsql 
            ? DB::raw("ST_SetSRID(ST_MakePoint(106.7032, 10.7769), 4326)") 
            : 'POINT(106.7032 10.7769)';
        $trip->route_line = $isPgsql 
            ? DB::raw("ST_SetSRID(ST_MakeLine(ARRAY[ST_MakePoint(106.6710, 11.0535), ST_MakePoint(106.7032, 10.7769)]), 4326)") 
            : 'LINESTRING(106.6710 11.0535, 106.7032 10.7769)';
        $trip->save();

        return redirect()->route('dashboard')
            ->with('success', 'Đã lưu và mở chuyến xe đi làm thành công! Tuyến đường đã sẵn sàng để đồng nghiệp cùng trục hẹn đi nhờ.');
    }
}
