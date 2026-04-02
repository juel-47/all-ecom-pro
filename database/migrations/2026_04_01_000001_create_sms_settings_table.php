<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(0);
            $table->text('message_template')->nullable();

            // Bangladesh (Barta) config
            $table->boolean('bd_enabled')->default(0);
            $table->string('bd_driver')->nullable();
            $table->json('bd_credentials')->nullable();

            // Global (laravel-sms-api) config
            $table->boolean('global_enabled')->default(0);
            $table->string('provider_name')->nullable();
            $table->string('endpoint_url')->nullable();
            $table->string('http_method')->default('POST');
            $table->string('content_type')->default('form'); // form|json
            $table->boolean('add_code')->default(0);
            $table->string('country_code')->nullable();
            $table->boolean('json_to_array')->default(1);
            $table->string('wrapper')->nullable();
            $table->json('wrapper_params')->nullable();

            $table->string('auth_type')->default('none'); // none|bearer|header|basic|query
            $table->string('auth_key')->nullable();
            $table->text('auth_value')->nullable();
            $table->string('sender_key')->nullable();
            $table->string('sender_value')->nullable();
            $table->string('to_key')->nullable();
            $table->string('message_key')->nullable();
            $table->json('headers')->nullable();
            $table->json('extra_params')->nullable();
            $table->integer('request_timeout')->default(10);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_settings');
    }
};
