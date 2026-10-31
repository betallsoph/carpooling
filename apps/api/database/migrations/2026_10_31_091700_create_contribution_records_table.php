<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contribution_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->unique()->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('trip_instance_id')->constrained('trip_instances')->cascadeOnDelete();
            $table->foreignId('passenger_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('driver_profile_id')->constrained('driver_profiles')->cascadeOnDelete();

            $table->decimal('amount', 10, 2);
            $table->string('transfer_content'); // e.g. "CP10025" (mã chuyến/booking để tra cứu UNC)
            $table->text('vietqr_url'); // Direct Vietmap / NAPAS VietQR QuickLink image url

            // Trạng thái xác nhận 2 phía (không giữ tiền)
            $table->string('status')->default('pending'); // pending, passenger_confirmed, driver_confirmed, disputed
            $table->timestamp('passenger_confirmed_at')->nullable(); // Khách bấm "Đã chuyển"
            $table->timestamp('driver_confirmed_at')->nullable();    // Chủ xe bấm "Đã nhận"

            $table->string('proof_image_url')->nullable(); // Ảnh UNC khi có khiếu nại
            $table->text('dispute_reason')->nullable();
            $table->text('admin_resolution_notes')->nullable();

            $table->timestamps();

            $table->index(['trip_instance_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contribution_records');
    }
};
