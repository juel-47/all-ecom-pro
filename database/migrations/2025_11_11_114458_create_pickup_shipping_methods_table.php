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
        Schema::create('pickup_shipping_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('store_name'); 
            $table->string('address')->nullable();
            $table->string('map_location')->nullable(); 
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->decimal('cost', 10, 2)->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pickup_shipping_methods');
    }
};
