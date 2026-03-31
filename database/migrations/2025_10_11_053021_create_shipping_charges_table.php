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
        Schema::create('shipping_charges', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('shipping_method_id')->constrained()->onDelete('cascade');
            // $table->foreignId('country_id')->constrained()->onDelete('cascade');
            // $table->foreignId('state_id')->nullable()->constrained()->onDelete('cascade');
            // $table->decimal('base_charge', 10, 2)->default(0);
            // $table->decimal('min_weight', 10, 2)->default(0); // kg
            // $table->decimal('max_weight', 10, 2)->default(0);
            // $table->decimal('extra_per_kg', 10, 2)->nullable();
            // $table->timestamps();
            $table->unsignedBigInteger('shipping_method_id');
            $table->unsignedBigInteger('country_id');
            $table->unsignedBigInteger('state_id')->nullable();
            $table->decimal('base_charge', 10, 2);
            $table->decimal('extra_per_kg', 10, 2)->nullable();
            $table->decimal('min_weight', 8, 2);
            $table->decimal('max_weight', 8, 2);
            $table->timestamps();

            $table->foreign('shipping_method_id')->references('id')->on('shipping_methods')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_charges');
    }
};
