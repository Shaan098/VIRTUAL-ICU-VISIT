<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'doctor', 'family'])->default('family')->after('email');
            $table->string('phone', 20)->nullable()->after('role');
            $table->string('avatar')->nullable()->after('phone');
            $table->string('specialty')->nullable()->after('avatar')->comment('For doctors');
            $table->boolean('is_active')->default(true)->after('specialty');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'avatar', 'specialty', 'is_active']);
        });
    }
};
