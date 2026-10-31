<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TripInstance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Models\Booking;
use App\Models\DriverProfile;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TripController extends Controller
{
    /**
     * Danh sách chuyến xe đi làm (Feed/Dashboard)
     */
    public function index(Request $request): JsonResponse
    {
        $trips = TripInstance::with(['driverProfile.user', 'vehicle'])
            ->where('status', 'scheduled')
            ->latest('date')
            ->take(20)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $trips,
        ]);
    }

    /**
     * Thống kê tổng quan cộng đồng đi chung xe
     */
    public function stats(): JsonResponse
    {
        $activeTripsCount = TripInstance::where('status', 'scheduled')->count();
        $availableSeatsCount = (int) TripInstance::where('status', 'scheduled')->sum('available_seats');
        $totalBookingsCount = Booking::count();
        $pendingBookingsCount = Booking::where('status', 'requested')->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'active_trips_count' => $activeTripsCount,
                'available_seats_count' => $availableSeatsCount,
                'total_bookings_count' => $totalBookingsCount,
                'pending_bookings_count' => $pendingBookingsCount,
                'platform_fee' => 0,
            ],
        ]);
    }

    /**
     * Đăng lịch trình xe mới (Roomio Form)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'origin_name' => 'required|string|max:255',
            'destination_name' => 'required|string|max:255',
            'departure_time' => 'required|string',
            'available_seats' => 'required|integer|min:1|max:6',
            'vehicle_model' => 'nullable|string',
            'vehicle_plate' => 'nullable|string',
        ]);

        $user = $request->user() ?? Auth::user() ?? \App\Models\User::first();

        $driverProfile = DriverProfile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'license_number' => '79' . rand(100000000, 999999999),
                'license_class' => 'B2',
                'verification_status' => 'approved',
                'verified_at' => now(),
            ]
        );

        $vehicle = Vehicle::firstOrCreate(
            ['driver_profile_id' => $driverProfile->id],
            [
                'plate_number' => $validated['vehicle_plate'] ?? '61A-' . rand(100, 999) . '.' . rand(10, 99),
                'make' => 'Toyota',
                'model' => $validated['vehicle_model'] ?? 'Vios G',
                'color' => 'Trắng',
                'seats_capacity' => 5,
                'seats_available_for_carpool' => (int) $validated['available_seats'],
                'is_active' => true,
            ]
        );

        $isPgsql = DB::getDriverName() === 'pgsql';

        $trip = new TripInstance([
            'driver_profile_id' => $driverProfile->id,
            'vehicle_id' => $vehicle->id,
            'date' => now()->isWeekend() ? now()->next('Monday') : now(),
            'departure_time' => $validated['departure_time'],
            'status' => 'scheduled',
            'origin_name' => $validated['origin_name'],
            'origin_address' => $validated['origin_name'],
            'destination_name' => $validated['destination_name'],
            'destination_address' => $validated['destination_name'],
            'total_distance_km' => 30.0,
            'estimated_duration_minutes' => 50,
            'total_seats' => (int) $validated['available_seats'],
            'available_seats' => (int) $validated['available_seats'],
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

        return response()->json([
            'status' => 'success',
            'message' => 'Đã lưu và mở chuyến xe đi làm thành công!',
            'data' => $trip->load(['driverProfile.user', 'vehicle']),
        ], 201);
    }

    /**
     * Tìm chuyến theo hành lang (Corridor Matching PostGIS)
     * Tuyệt đối không gọi Distance Matrix n x m
     */
    public function search(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pickup_lng' => 'required|numeric|between:-180,180',
            'pickup_lat' => 'required|numeric|between:-90,90',
            'dropoff_lng' => 'required|numeric|between:-180,180',
            'dropoff_lat' => 'required|numeric|between:-90,90',
            'date' => 'nullable|date',
            'seats' => 'nullable|integer|min:1|max:4',
            'buffer_meters' => 'nullable|numeric|min:500|max:5000',
        ]);

        $seats = $validated['seats'] ?? 1;
        $bufferMeters = $validated['buffer_meters'] ?? 2000; // Mặc định lệch hành lang 2km
        $date = $validated['date'] ?? now()->toDateString();

        $trips = TripInstance::query()
            ->with(['driverProfile.user', 'vehicle'])
            ->where('date', '>=', $date)
            ->where('status', 'scheduled')
            ->where('available_seats', '>=', $seats)
            ->matchCorridor(
                pickupLng: (float) $validated['pickup_lng'],
                pickupLat: (float) $validated['pickup_lat'],
                dropoffLng: (float) $validated['dropoff_lng'],
                dropoffLat: (float) $validated['dropoff_lat'],
                bufferMeters: (float) $bufferMeters
            )
            ->orderBy('date')
            ->orderBy('departure_time')
            ->get();

        $results = $trips->map(function (TripInstance $trip) use ($seats) {
            return [
                'id' => $trip->id,
                'date' => $trip->date->format('Y-m-d'),
                'departure_time' => $trip->departure_time,
                'origin' => [
                    'name' => $trip->origin_name,
                    'address' => $trip->origin_address,
                ],
                'destination' => [
                    'name' => $trip->destination_name,
                    'address' => $trip->destination_address,
                ],
                'total_distance_km' => $trip->total_distance_km,
                'estimated_duration_minutes' => $trip->estimated_duration_minutes,
                'available_seats' => $trip->available_seats,
                'estimated_cost_per_person' => $trip->calculateCostPerPerson($seats),
                'driver' => [
                    'name' => $trip->driverProfile->user->name,
                    'avatar_url' => $trip->driverProfile->user->avatar_url,
                    'rating_avg' => $trip->driverProfile->rating_avg,
                    'rating_count' => $trip->driverProfile->rating_count,
                    'rides_completed' => $trip->driverProfile->total_rides_completed,
                ],
                'vehicle' => [
                    'make' => $trip->vehicle->make,
                    'model' => $trip->vehicle->model,
                    'color' => $trip->vehicle->color,
                    'plate_number' => $trip->vehicle->plate_number,
                ],
            ];
        });

        return response()->json([
            'status' => 'success',
            'count' => $results->count(),
            'data' => $results,
        ]);
    }

    /**
     * Chi tiết chuyến đi
     */
    public function show(TripInstance $trip): JsonResponse
    {
        $trip->load(['driverProfile.user', 'vehicle', 'reviews.reviewer']);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $trip->id,
                'date' => $trip->date->format('Y-m-d'),
                'departure_time' => $trip->departure_time,
                'status' => $trip->status,
                'origin' => [
                    'name' => $trip->origin_name,
                    'address' => $trip->origin_address,
                ],
                'destination' => [
                    'name' => $trip->destination_name,
                    'address' => $trip->destination_address,
                ],
                'total_distance_km' => $trip->total_distance_km,
                'toll_fee' => $trip->toll_fee,
                'current_cost_per_person' => $trip->calculateCostPerPerson(0),
                'available_seats' => $trip->available_seats,
                'max_detour_minutes' => $trip->max_detour_minutes,
                'driver' => [
                    'name' => $trip->driverProfile->user->name,
                    'avatar_url' => $trip->driverProfile->user->avatar_url,
                    'rating_avg' => $trip->driverProfile->rating_avg,
                    'rating_count' => $trip->driverProfile->rating_count,
                ],
                'vehicle' => [
                    'make' => $trip->vehicle->make,
                    'model' => $trip->vehicle->model,
                    'color' => $trip->vehicle->color,
                    'plate_number' => $trip->vehicle->plate_number,
                ],
                'reviews' => $trip->reviews->map(fn ($r) => [
                    'rating' => $r->rating,
                    'comment' => $r->comment,
                    'reviewer' => $r->reviewer->name,
                ]),
            ],
        ]);
    }
}
