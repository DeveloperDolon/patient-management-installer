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
        Schema::create('patients', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->integer('age');
            $table->string('occupation')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->default('other');
            $table->string('phone');
            $table->string('address')->nullable();
            $table->string('religion')->nullable();
            $table->string('blood_group')->nullable();
            $table->foreignUlid('doctor_id')->constrained('users')->onDelete('cascade');
            $table->date('date_of_birth')->nullable();
            $table->string('profile_picture')->nullable();
            $table->string('report_images')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
