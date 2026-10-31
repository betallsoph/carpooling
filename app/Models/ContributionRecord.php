<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContributionRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'trip_instance_id',
        'passenger_id',
        'driver_profile_id',
        'amount',
        'transfer_content',
        'vietqr_url',
        'status',
        'passenger_confirmed_at',
        'driver_confirmed_at',
        'proof_image_url',
        'dispute_reason',
        'admin_resolution_notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'float',
            'passenger_confirmed_at' => 'datetime',
            'driver_confirmed_at' => 'datetime',
        ];
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function tripInstance(): BelongsTo
    {
        return $this->belongsTo(TripInstance::class);
    }

    public function passenger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function driverProfile(): BelongsTo
    {
        return $this->belongsTo(DriverProfile::class);
    }

    /**
     * Helper tạo link VietQR NAPAS 247 QuickLink chuẩn
     */
    public static function generateVietQrUrl(
        string $bankCode,
        string $accountNo,
        string $accountName,
        float $amount,
        string $transferContent
    ): string {
        $encodedName = rawurlencode($accountName);
        $encodedContent = rawurlencode($transferContent);

        return "https://img.vietqr.io/image/{$bankCode}-{$accountNo}-compact2.png?amount={$amount}&addInfo={$encodedContent}&accountName={$encodedName}";
    }
}
