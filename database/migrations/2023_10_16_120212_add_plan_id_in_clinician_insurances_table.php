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
        Schema::table('clinician_insurances', function (Blueprint $table) {
            $table->unsignedBigInteger('plan_id')->nullable()->after('insurance_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinician_insurances', function (Blueprint $table) {
            //
        });
    }
};
