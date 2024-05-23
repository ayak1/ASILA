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
        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_type_id')->constrained('apartment_types');
            $table->foreignId('city_id')->constrained('cities');
            $table->decimal('longitude', 10, 7);
            $table->decimal('latitude', 10, 7);
            $table->boolean('is_for_sell');
            $table->boolean('is_for_rent');
            $table->integer('space');
            $table->boolean('has_parking');
            $table->integer('room_number');
            $table->integer('baths_number');
            $table->integer('parking_number');
            $table->integer('pools_number');
            $table->integer('floor');
            $table->boolean('is_rented');
            $table->boolean('is_sold');
            $table->dateTime('available_for_rent_at')->nullable();
            // $table->decimal('rent_price', 10, 2)->nullable();
            $table->decimal('sell_price', 10, 2)->nullable();
            $table->boolean('in_installments');
            $table->decimal('sell_per_month', 10, 2)->nullable();
            $table->decimal('rent_per_month', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartments');
    }
};
