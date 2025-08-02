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
            $table->json('special_procedures')->nullable()->after('site_involvement');
            $table->longText('report_images')->nullable()->after('investigations');
            $table->json('medication_guidelines')->nullable()->after('report_images');
            $table->json('advices')->nullable()->after('medication_guidelines');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn('special_procedures');
            $table->dropColumn('report_images');
            $table->dropColumn('medication_guidelines');
            $table->dropColumn('advices');
        });
    }
};
