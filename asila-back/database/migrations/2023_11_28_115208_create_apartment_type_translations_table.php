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
        Schema::create('apartment_type_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_type_id')->constrained('apartment_types');
            $table->foreignId('locale_id')->constrained('locales')->onDelete('cascade');
            $table->string('type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apartment_type_translations');
    }
};
