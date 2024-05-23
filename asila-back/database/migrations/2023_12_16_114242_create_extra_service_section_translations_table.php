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
        Schema::create('extra_service_section_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('locale_id')->constrained('locales')->onDelete('cascade');
            $table->foreignId('e_service_section_id')->constrained('extra_service_sections')->onDelete('cascade');
            $table->string('section_title')->nullable();
            $table->text('section_description')->nullable();
            $table->json('list_of_adv')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_service_section_translations');
    }
};
