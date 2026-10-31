<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_profile_id',
        'make',
        'model',
        'color',
        'plate_number',
        'seats_capacity',
        'seats_available_for_carpool',
        'photo_url',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'seats_capacity' => 'integer',
            'seats_available_for_carpool' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function driverProfile(): BelongsTo
    {
        return $this->belongsTo(DriverProfile::class);
    }

    public function tripTemplates(): HasMany
    {
        return $this->hasMany(TripTemplate::class);
    }

    public function tripInstances(): HasMany
    {
        return $this->hasMany(TripInstance::class);
    }
}
