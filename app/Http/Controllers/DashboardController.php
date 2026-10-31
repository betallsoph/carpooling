<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\TripInstance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            ->take(8)
            ->get();

        $bookings = Booking::with(['tripInstance.driverProfile.user', 'passenger'])
            ->latest()
            ->take(8)
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
}
