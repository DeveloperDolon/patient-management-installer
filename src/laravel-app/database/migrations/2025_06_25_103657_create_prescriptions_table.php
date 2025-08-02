<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignUlid('doctor_id')->constrained('users')->onDelete('cascade');
            $table->json('complaints');
            $table->json('present_illness');
            $table->json('history_of_medication');
            $table->json('history_of_concomitant_illness');
            $table->json('family_disease_history')->nullable();
            $table->json('menstrual_history')->nullable();
            $table->json('personal_history')->nullable();
            $table->json('general_examinations');
            $table->json('systemic_examinations');
            $table->json('dermatological_examinations');
            $table->json('site_involvement')->nullable();
            $table->json('investigations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
