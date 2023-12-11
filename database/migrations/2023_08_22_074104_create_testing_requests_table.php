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
        Schema::create('testing_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('testing_type_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('home_phone')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('dob')->nullable();
            $table->enum('insurance_or_selfpay', ['Insurance', 'Self Pay'])->nullable();
            $table->string('insurance_id')->nullable();
            $table->string('insurance_plan_id')->nullable();
            $table->string('member_id')->nullable();
            $table->string('policy_holder_gender')->nullable();
            $table->string('relation_to_patient')->nullable();
            $table->text('clinician_preference')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testing_requests');
    }
};
