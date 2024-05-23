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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('cities');
            $table->boolean('has_parking');
            $table->boolean('has_free_breakfast');
            $table->boolean('has_swimming_pool');
            $table->boolean('has_spa');
            $table->boolean('has_fitness_center');
            $table->boolean('has_free_internet');
            $table->boolean('has_restaurant');
            $table->boolean('pets_allowed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
