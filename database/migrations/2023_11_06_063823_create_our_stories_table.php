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
        Schema::create('our_stories', function (Blueprint $table) {
            $table->id();
            $table->string('sub_header')->nullable();
            $table->string('primary_image')->nullable();
            $table->string('secondary_image')->nullable();
            $table->longText('content')->nullable();
            $table->string('first_counter_label')->nullable();
            $table->string('first_counter_value')->nullable();
            $table->string('second_counter_label')->nullable();
            $table->string('second_counter_value')->nullable();
            $table->string('third_counter_label')->nullable();
            $table->string('third_counter_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('our_stories');
    }
};
