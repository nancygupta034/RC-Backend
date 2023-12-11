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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->string('dr_name')->nullable();
            $table->string('clinic_name')->nullable();
            $table->string('dr_email')->nullable();
            $table->string('dr_mobile_number')->nullable();
            $table->string('clinic_mobile_number')->nullable();
            $table->string('clinic_email')->nullable();
            $table->string('tax_id')->nullable();
            $table->longText('clinic_address')->nullable();
            $table->string('type')->nullable();
            $table->longText('reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
