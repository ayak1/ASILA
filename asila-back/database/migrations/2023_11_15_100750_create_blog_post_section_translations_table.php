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
        Schema::create('blog_post_section_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_section_id')->constrained('blog_post_sections');
            $table->foreignId('locale_id')->constrained('locales')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->longText('text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_post_section_translations');
    }
};
