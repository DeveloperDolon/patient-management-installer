<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('doctor_id')->constrained('users')->onDelete('cascade');
            $table->string('clinic_name');
            $table->string('clinic_address');
            $table->string('clinic_phone');
            $table->string('clinic_email')->nullable();
            $table->text('visit_time');
            $table->text('logo')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
