<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_instance_id')->constrained('trip_instances')->cascadeOnDelete();
            $table->foreignId('passenger_id')->constrained('users')->cascadeOnDelete();

            $table->unsignedSmallInteger('seats_requested')->default(1);
            $table->string('pickup_name');
            $table->text('pickup_address');
            $table->geometry('pickup_point', subtype: 'point', srid: 4326);

            $table->string('dropoff_name');
            $table->text('dropoff_address');
            $table->geometry('dropoff_point', subtype: 'point', srid: 4326);

            $table->string('status')->default('requested');
            // requested, accepted, declined, cancelled_by_passenger, cancelled_by_driver, completed

            $table->decimal('calculated_contribution', 10, 2)->default(0);
            $table->unsignedSmallInteger('detour_minutes_added')->default(0);

            $table->text('note')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();

            $table->timestamps();

            $table->index(['trip_instance_id', 'status']);
            $table->index(['passenger_id', 'status']);
        });

        DB::statement('CREATE INDEX bookings_pickup_point_gist ON bookings USING GIST (pickup_point);');
        DB::statement('CREATE INDEX bookings_dropoff_point_gist ON bookings USING GIST (dropoff_point);');
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
