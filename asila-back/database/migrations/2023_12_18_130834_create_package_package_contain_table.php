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
        Schema::create('package_package_contain', function (Blueprint $table) {
            $table->id();
            $table->unsignedBiginteger('package_id');
            $table->unsignedBiginteger('package_contain_id');


            $table->foreign('package_id')->references('id')
                 ->on('packages')->onDelete('cascade');
            $table->foreign('package_contain_id')->references('id')
                ->on('package_contains')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_package_contain');
    }
};
