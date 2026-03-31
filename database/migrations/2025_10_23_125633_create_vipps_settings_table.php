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
        Schema::create('vipps_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('active')->default(false);
            $table->string('environment')->default('test'); // test/production
            $table->string('client_id')->nullable();
            $table->string('client_secret')->nullable();
            $table->string('subscription_key')->nullable();
            $table->string('merchant_serial_number')->nullable();
            $table->string('webhook_secret')->nullable();
            $table->string('token_url')->nullable();
            $table->string('checkout_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vipps_settings');
    }
};
