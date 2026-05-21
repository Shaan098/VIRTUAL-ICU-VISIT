<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visit_request_id')->constrained('visit_requests')->onDelete('cascade');
            $table->string('room_name')->unique();
            $table->string('room_password', 50)->nullable();
            $table->string('jitsi_url')->nullable();
            $table->dateTime('scheduled_at');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('ended_at')->nullable();
            $table->enum('status', ['scheduled', 'active', 'completed', 'cancelled'])->default('scheduled');
            $table->foreignId('host_id')->constrained('users')->onDelete('cascade');
            $table->integer('duration_minutes')->nullable()->comment('Scheduled duration');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
