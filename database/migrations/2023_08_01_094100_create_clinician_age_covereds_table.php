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
        Schema::create('clinician_age_covereds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinician_id');
            $table->unsignedBigInteger('age_range_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinician_age_covereds');
    }
};
