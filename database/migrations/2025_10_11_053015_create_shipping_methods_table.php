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
        Schema::create('shipping_methods', function (Blueprint $table) {
            $table->id();
            //  $table->string('name');
            // $table->enum('type', ['local', 'international','flat_rate', 'express', 'free_shipping', 'courier', 'pickup'])->default('international');
            // $table->boolean('is_active');
            // $table->timestamps();

             $table->string('name');
            $table->json('type')->nullable();
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_methods');
    }
};
