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
        Schema::create('package_contain_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_contain_id')->constrained('package_contains');
            $table->foreignId('locale_id')->constrained('locales')->onDelete('cascade');
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_contain_translations');
    }
};
