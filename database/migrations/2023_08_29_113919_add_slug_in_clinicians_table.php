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
        Schema::table('clinicians', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('clinicians', 'slug'))
        {
            Schema::table('clinicians', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
