<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DriverProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'license_number',
        'license_class',
        'license_front_image',
        'license_back_image',
        'verification_status',
        'rejection_reason',
        'verified_at',
        'bank_name',
        'bank_code',
        'bank_account_no',
        'bank_account_name',
        'rating_avg',
        'rating_count',
        'total_rides_completed',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
            'rating_avg' => 'float',
            'rating_count' => 'integer',
            'total_rides_completed' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function activeVehicle(): HasOne
    {
        return $this->hasOne(Vehicle::class)->where('is_active', true);
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
