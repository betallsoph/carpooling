<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $hidden = [
        'pickup_point',
        'dropoff_point',
    ];

    protected $fillable = [
        'trip_instance_id',
        'passenger_id',
        'seats_requested',
        'pickup_name',
        'pickup_address',
        'dropoff_name',
        'dropoff_address',
        'status',
        'calculated_contribution',
        'detour_minutes_added',
        'note',
        'rejection_reason',
        'accepted_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'seats_requested' => 'integer',
            'calculated_contribution' => 'float',
            'detour_minutes_added' => 'integer',
            'accepted_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function tripInstance(): BelongsTo
    {
        return $this->belongsTo(TripInstance::class);
    }

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function contributionRecord(): HasOne
    {
        return $this->hasOne(ContributionRecord::class);
    }
}
