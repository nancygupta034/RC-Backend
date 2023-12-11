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
        Schema::create('tru_billing_insurance_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tru_billing_insurance_id')->nullable();
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tru_billing_insurance_plans');
    }
};
