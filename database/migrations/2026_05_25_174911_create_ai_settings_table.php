<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel konfigurasi AI provider untuk admin.
     * Mendukung OpenAI, Anthropic, Google AI, Groq, dll.
     */
    public function up(): void
    {
        Schema::create('ai_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('Label, contoh: OpenAI GPT-4');
            $table->string('provider', 30)->comment('openai, anthropic, google, groq, ollama, custom');

            // API Configuration
            $table->text('api_key')->nullable()->comment('Encrypted API Key');
            $table->string('api_endpoint', 255)->nullable()->comment('Custom endpoint URL');
            $table->string('model', 100)->nullable()->comment('Model name: gpt-4, claude-3, gemini-pro, dll');

            // Model Parameters
            $table->decimal('temperature', 3, 2)->default(0.7)->comment('0.0 - 2.0');
            $table->integer('max_tokens')->default(2000)->comment('Maximum response tokens');
            $table->decimal('top_p', 3, 2)->default(1.0)->comment('0.0 - 1.0');
            $table->integer('frequency_penalty')->default(0)->comment('-2 to 2');
            $table->integer('presence_penalty')->default(0)->comment('-2 to 2');

            // Usage Limits & Tracking
            $table->unsignedInteger('daily_limit')->default(0)->comment('0 = unlimited requests per day');
            $table->unsignedInteger('daily_used')->default(0);
            $table->date('last_reset_date')->nullable();
            $table->timestamp('last_used_at')->nullable();

            // Cost Tracking (optional)
            $table->decimal('cost_per_1k_tokens', 10, 6)->nullable()->comment('Cost in USD');
            $table->decimal('total_cost', 12, 2)->default(0)->comment('Total accumulated cost');

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->unsignedInteger('priority')->default(0)->comment('Urutan pakai, kecil = duluan');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'is_default']);
            $table->index('provider');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_settings');
    }
};
