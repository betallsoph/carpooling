<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->unique()->after('email');
            $table->string('role')->default('passenger')->after('phone'); // passenger, driver, admin
            $table->string('avatar_url')->nullable()->after('role');
            $table->string('gender')->nullable()->after('avatar_url'); // male, female, other
            $table->string('emergency_contact_name')->nullable()->after('gender');
            $table->string('emergency_contact_phone')->nullable()->after('emergency_contact_name');
            $table->boolean('is_verified')->default(false)->after('emergency_contact_phone');
            $table->boolean('is_blocked')->default(false)->after('is_verified');
            $table->string('block_reason')->nullable()->after('is_blocked');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'role',
                'avatar_url',
                'gender',
                'emergency_contact_name',
                'emergency_contact_phone',
                'is_verified',
                'is_blocked',
                'block_reason',
            ]);
        });
    }
};
