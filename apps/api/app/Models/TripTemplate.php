<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TripTemplate extends Model
{
    use HasFactory;

    protected $hidden = [
        'origin_point',
        'destination_point',
        'route_line',
    ];

    protected $fillable = [
        'driver_profile_id',
        'vehicle_id',
        'title',
        'origin_name',
        'origin_address',
        'destination_name',
        'destination_address',
        'route_polyline',
        'total_distance_km',
        'estimated_duration_minutes',
        'toll_fee',
        'fuel_rate_per_km',
        'available_seats',
        'max_detour_minutes',
        'days_of_week',
        'departure_time',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'days_of_week' => 'array',
            'is_active' => 'boolean',
            'total_distance_km' => 'float',
            'estimated_duration_minutes' => 'integer',
            'toll_fee' => 'float',
            'fuel_rate_per_km' => 'float',
            'available_seats' => 'integer',
            'max_detour_minutes' => 'integer',
        ];
    }

    public function driverProfile(): BelongsTo
    {
        return $this->belongsTo(DriverProfile::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function instances(): HasMany
    {
        return $this->hasMany(TripInstance::class);
    }
}
