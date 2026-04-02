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
        Schema::create('ai_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(false);
            $table->string('default_provider')->default('openai');
            $table->string('fallback_provider')->nullable();
            $table->text('system_prompt')->nullable();
            $table->decimal('temperature', 3, 2)->nullable();
            $table->integer('max_tokens')->nullable();
            $table->integer('request_timeout')->default(60);

            $table->text('openai_api_key')->nullable();
            $table->string('openai_model')->nullable();

            $table->text('gemini_api_key')->nullable();
            $table->string('gemini_model')->nullable();

            $table->text('groq_api_key')->nullable();
            $table->string('groq_model')->nullable();

            $table->timestamps();
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
