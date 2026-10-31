<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TripInstance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TripController extends Controller
{
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
