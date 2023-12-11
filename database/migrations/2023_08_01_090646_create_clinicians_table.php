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
        Schema::create('clinicians', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('qualification')->nullable();
            $table->string('image')->nullable();
            $table->longText('bio')->nullable();
            $table->string('azalea_id')->nullable();
            $table->string('tru_billing_id')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('is_insurance')->default(0);
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinicians');
    }
};
