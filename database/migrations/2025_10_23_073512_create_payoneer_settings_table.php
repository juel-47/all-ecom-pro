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
        Schema::create('payoneer_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('account_mode');
            $table->string('api_key');
            $table->string('api_secret_key');
            $table->string('program_id');
            $table->string('currency_name');
            $table->string('country_name');
            $table->string('api_url')->nullable();
            $table->string('token_url')->nullable();
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payoneer_settings');
    }
};
