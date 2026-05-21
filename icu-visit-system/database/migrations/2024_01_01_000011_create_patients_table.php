<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('dob')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('male');
            $table->string('diagnosis');
            $table->string('bed_number', 20)->nullable();
            $table->string('ward', 100)->nullable();
            $table->enum('status', ['active', 'critical', 'stable', 'discharged'])->default('active');
            $table->foreignId('assigned_doctor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('admission_date')->nullable();
            $table->text('notes')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone', 20)->nullable();
            $table->string('blood_group', 10)->nullable();
            $table->integer('age')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
