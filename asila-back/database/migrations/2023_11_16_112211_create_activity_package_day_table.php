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
        Schema::create('activity_package_day', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_day_id')->constrained('package_days');
            $table->foreignId('activity_id')->constrained('activities'); // Associate with Activity
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_package_day');
    }
};
