<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scraped_news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url')->unique();
            $table->string('image_url')->nullable();
            $table->text('excerpt')->nullable();
            $table->date('published_at')->nullable();
            $table->string('source')->default('saintek.uinsaizu.ac.id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scraped_news');
    }
};