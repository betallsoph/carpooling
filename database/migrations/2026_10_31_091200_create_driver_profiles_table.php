<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('license_number');
            $table->string('license_class')->default('B2'); // B1, B2, C, etc.
            $table->string('license_front_image')->nullable();
            $table->string('license_back_image')->nullable();
            $table->string('verification_status')->default('pending'); // pending, approved, rejected
            $table->text('rejection_reason')->nullable();
            $table->timestamp('verified_at')->nullable();

            // VietQR Banking details for peer-to-peer fuel sharing
            $table->string('bank_name')->nullable();
            $table->string('bank_code')->nullable(); // Napas bin or shortcode (e.g., TCB, VCB, MB)
            $table->string('bank_account_no')->nullable();
            $table->string('bank_account_name')->nullable();

            $table->decimal('rating_avg', 3, 2)->default(5.00);
            $table->unsignedInteger('rating_count')->default(0);
            $table->unsignedInteger('total_rides_completed')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_profiles');
    }
};
