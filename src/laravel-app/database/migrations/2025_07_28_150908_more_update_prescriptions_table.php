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
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->json('vaccination_history')->nullable()->after('investigations');
            $table->json('obstetric_history')->nullable()->after('vaccination_history');
            $table->json('operational_history')->nullable()->after('obstetric_history');
            $table->json('past_illness')->nullable()->after('present_illness');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn(['vaccination_history', 'obstetric_history', 'operational_history', 'past_illness']);
        });
    }
};
