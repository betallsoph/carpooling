<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trip_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('driver_profile_id')->constrained('driver_profiles')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();

            $table->string('title')->nullable(); // e.g. "Bình Dương -> Q1 sáng T2-T6"
            $table->string('origin_name');
            $table->text('origin_address');
            $table->string('destination_name');
            $table->text('destination_address');

            // PostGIS spatial geometry
            $table->geometry('origin_point', subtype: 'point', srid: 4326);
            $table->geometry('destination_point', subtype: 'point', srid: 4326);
            $table->geometry('route_line', subtype: 'linestring', srid: 4326)->nullable();
            $table->longText('route_polyline')->nullable(); // Encoded polyline string

            $table->decimal('total_distance_km', 8, 2);
            $table->unsignedInteger('estimated_duration_minutes');
            $table->decimal('toll_fee', 10, 2)->default(0); // Phí cầu đường (nếu có)
            $table->decimal('fuel_rate_per_km', 10, 2)->default(1800); // Đơn giá nhiên liệu định mức
            $table->unsignedSmallInteger('available_seats')->default(2);
            $table->unsignedSmallInteger('max_detour_minutes')->default(10); // Thời gian chịu lệch đón tối đa

            $table->json('days_of_week'); // [1, 2, 3, 4, 5] for Mon-Fri
            $table->time('departure_time'); // e.g. 07:00:00
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });

        DB::statement('CREATE INDEX trip_templates_route_line_gist ON trip_templates USING GIST (route_line);');
        DB::statement('CREATE INDEX trip_templates_origin_point_gist ON trip_templates USING GIST (origin_point);');
        DB::statement('CREATE INDEX trip_templates_destination_point_gist ON trip_templates USING GIST (destination_point);');
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_templates');
    }
};
