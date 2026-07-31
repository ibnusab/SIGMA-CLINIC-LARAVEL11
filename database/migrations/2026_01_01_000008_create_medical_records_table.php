<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->string('record_number')->unique(); // RMREC-20260730-001
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
            $table->dateTime('examination_date');
            $table->text('complaints');
            $table->text('medical_history')->nullable();
            $table->string('blood_pressure')->nullable(); // e.g. 120/80
            $table->decimal('temperature', 4, 1)->nullable(); // e.g. 36.5
            $table->decimal('height', 5, 1)->nullable(); // cm
            $table->decimal('weight', 5, 1)->nullable(); // kg
            $table->decimal('bmi', 4, 1)->nullable(); // Body Mass Index
            $table->text('diagnosis');
            $table->text('doctor_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
