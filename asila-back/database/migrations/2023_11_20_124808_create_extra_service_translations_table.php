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
        Schema::create('extra_service_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('locale_id')->constrained('locales')->onDelete('cascade');
            $table->foreignId('extra_service_id')->constrained('extra_services')->onDelete('cascade');
            $table->string('title');
            $table->text('main_description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_service_translations');
    }
};
