<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('steadfast_settings', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('status')->default(0);
            $table->string('base_url')->nullable();
            $table->string('api_key')->nullable();
            $table->string('secret_key')->nullable();
            $table->string('webhook_bearer_token')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('steadfast_settings');
    }
};
