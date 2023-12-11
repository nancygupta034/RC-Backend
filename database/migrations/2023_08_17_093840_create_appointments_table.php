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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('clinician_id');
            $table->string('reference_id')->nullable();
            $table->string('azalea_appointment_id')->nullable();
            $table->string('azalea_patient_id')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('dob')->nullable();
            $table->date('date')->nullable();
            $table->string('time')->nullable();
            $table->enum('status', ['Cancelled', 'Booked', 'Fulfilled', 'Arrived', 'Pending'])->nullable();
            $table->enum('payment_mode', ['Insurance', 'Self Pay'])->nullable();
            $table->string('insurance_id')->nullable();
            $table->string('insurance_plan_id')->nullable();
            $table->string('member_id')->nullable();
            $table->string('policy_holder_gender')->nullable();
            $table->unsignedBigInteger('service_id')->nullable();
            $table->string('relation_to_patient')->nullable();
            $table->string('hear_about_us')->nullable();
            $table->text('message')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
