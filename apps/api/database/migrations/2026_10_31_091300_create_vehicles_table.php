<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_profile_id')->constrained('driver_profiles')->cascadeOnDelete();
            $table->string('make'); // Toyota, VinFast, Hyundai, etc.
            $table->string('model'); // Vios, VF8, Accent, etc.
            $table->string('color');
            $table->string('plate_number')->unique();
            $table->unsignedSmallInteger('seats_capacity')->default(5);
            $table->unsignedSmallInteger('seats_available_for_carpool')->default(3);
            $table->string('photo_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
