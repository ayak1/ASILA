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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('cities');
            $table->boolean('private_car_program');
            $table->boolean('group_program');
            $table->timestamps();
        });

        // Pivot table for the many-to-many relationship between programs and areas
        Schema::create('area_program', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained('areas');
            $table->foreignId('program_id')->constrained('programs');
        });

        // Pivot table for the many-to-many relationship between programs and activities
        Schema::create('activity_program', function (Blueprint $table) {
            $table->id();
            $table->foreignId('activity_id')->constrained('activities');
            $table->foreignId('program_id')->constrained('programs');
        });
    }

    public function down()
    {
        Schema::dropIfExists('programs');
        Schema::dropIfExists('area_program');
        Schema::dropIfExists('activity_program');
    }
};
