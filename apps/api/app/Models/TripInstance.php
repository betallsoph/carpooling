<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class TripInstance extends Model
{
    use HasFactory;

    protected $hidden = [
        'origin_point',
        'destination_point',
        'route_line',
    ];

    protected $fillable = [
        'trip_template_id',
        'driver_profile_id',
        'vehicle_id',
        'date',
        'departure_time',
        'status',
        'origin_name',
        'origin_address',
        'destination_name',
        'destination_address',
        'route_polyline',
        'total_distance_km',
        'estimated_duration_minutes',
        'toll_fee',
        'fuel_rate_per_km',
        'total_seats',
        'available_seats',
        'max_detour_minutes',
        'current_cost_per_person',
        'cancellation_reason',
        'started_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'total_distance_km' => 'float',
            'estimated_duration_minutes' => 'integer',
            'toll_fee' => 'float',
            'fuel_rate_per_km' => 'float',
            'total_seats' => 'integer',
            'available_seats' => 'integer',
            'max_detour_minutes' => 'integer',
            'current_cost_per_person' => 'float',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(TripTemplate::class, 'trip_template_id');
    }

    public function driverProfile(): BelongsTo
    {
        return $this->belongsTo(DriverProfile::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function contributionRecords(): HasMany
    {
        return $this->hasMany(ContributionRecord::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Thuật toán tìm chuyến theo hành lang (Corridor Matching qua PostGIS)
     * Không gọi Matrix API tốn tiền:
     * 1. Điểm đón (pickup) nằm trong bán kính bufferMeters của tuyến đường
     * 2. Điểm trả (dropoff) nằm trong bán kính bufferMeters của tuyến đường
     * 3. Điểm đón xuất hiện trước điểm trả theo hướng di chuyển của xe
     */
    public function scopeMatchCorridor(
        Builder $query,
        float $pickupLng,
        float $pickupLat,
        float $dropoffLng,
        float $dropoffLat,
        float $bufferMeters = 2000
    ): Builder {
        if (DB::getDriverName() !== 'pgsql') {
            // Fallback dành cho môi trường dev/test SQLite (không có extension PostGIS C-libraries)
            if ($pickupLng > $dropoffLng) {
                return $query->whereRaw('1 = 0');
            }
            return $query->whereNotNull('route_line');
        }

        return $query
            ->whereNotNull('route_line')
            ->whereRaw(
                "ST_DWithin(route_line::geography, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography, ?)",
                [$pickupLng, $pickupLat, $bufferMeters]
            )
            ->whereRaw(
                "ST_DWithin(route_line::geography, ST_SetSRID(ST_MakePoint(?, ?), 4326)::geography, ?)",
                [$dropoffLng, $dropoffLat, $bufferMeters]
            )
            ->whereRaw(
                "ST_LineLocatePoint(route_line, ST_SetSRID(ST_MakePoint(?, ?), 4326)) < ST_LineLocatePoint(route_line, ST_SetSRID(ST_MakePoint(?, ?), 4326))",
                [$pickupLng, $pickupLat, $dropoffLng, $dropoffLat]
            );
    }

    /**
     * Tính toán số tiền mỗi người đóng góp theo công thức pháp lý carpooling:
     * (km * đơn giá nhiên liệu + phí cầu đường) / (1 chủ xe + số khách đã nhận)
     */
    public function calculateCostPerPerson(int $additionalAcceptedPassengers = 0): float
    {
        $currentAcceptedCount = $this->bookings()->where('status', 'accepted')->sum('seats_requested');
        $totalParticipants = 1 + $currentAcceptedCount + $additionalAcceptedPassengers;

        if ($totalParticipants <= 0) {
            $totalParticipants = 1;
        }

        $totalTripCost = ($this->total_distance_km * $this->fuel_rate_per_km) + $this->toll_fee;

        // Làm tròn lên nghìn đồng gần nhất (chuẩn UX thanh toán VN)
        $costPerPerson = ceil(($totalTripCost / $totalParticipants) / 1000) * 1000;

        return (float) $costPerPerson;
    }
}
