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
        Schema::create('clinician_service_provideds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinician_id');
            $table->unsignedBigInteger('service_provided_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinician_service_provideds');
    }
};
