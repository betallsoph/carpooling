<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\TripController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth routes
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Public Trip Search (Corridor Matching PostGIS)
    Route::get('/trips/search', [TripController::class, 'search']);
    Route::get('/trips/{trip}', [TripController::class, 'show']);

    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Commuter ride requests (Phi thương mại, tiền xăng tự tính)
        Route::post('/bookings', [BookingController::class, 'store']);
        Route::post('/bookings/{booking}/accept', [BookingController::class, 'accept']);
        Route::post('/bookings/{booking}/decline', [BookingController::class, 'decline']);
    });
});
