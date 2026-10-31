<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trip_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_template_id')->nullable()->constrained('trip_templates')->nullOnDelete();
            $table->foreignId('driver_profile_id')->constrained('driver_profiles')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();

            $table->date('date');
            $table->time('departure_time');
            $table->string('status')->default('scheduled'); // scheduled, in_progress, completed, cancelled

            $table->string('origin_name');
            $table->text('origin_address');
            $table->string('destination_name');
            $table->text('destination_address');

            // PostGIS spatial geometry
            $table->geometry('origin_point', subtype: 'point', srid: 4326);
            $table->geometry('destination_point', subtype: 'point', srid: 4326);
            $table->geometry('route_line', subtype: 'linestring', srid: 4326)->nullable();
            $table->longText('route_polyline')->nullable();

            $table->decimal('total_distance_km', 8, 2);
            $table->unsignedInteger('estimated_duration_minutes');
            $table->decimal('toll_fee', 10, 2)->default(0);
            $table->decimal('fuel_rate_per_km', 10, 2)->default(1800);

            $table->unsignedSmallInteger('total_seats')->default(2);
            $table->unsignedSmallInteger('available_seats')->default(2);
            $table->unsignedSmallInteger('max_detour_minutes')->default(10);

            // Công thức chia chi phí: (km * fuel_rate + toll) / (1 chủ xe + n khách accepted)
            $table->decimal('current_cost_per_person', 10, 2)->default(0);

            $table->text('cancellation_reason')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            $table->index(['date', 'status']);
        });

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('CREATE INDEX trip_instances_route_line_gist ON trip_instances USING GIST (route_line);');
            DB::statement('CREATE INDEX trip_instances_origin_point_gist ON trip_instances USING GIST (origin_point);');
            DB::statement('CREATE INDEX trip_instances_destination_point_gist ON trip_instances USING GIST (destination_point);');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_instances');
    }
};
