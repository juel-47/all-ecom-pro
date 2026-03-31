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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
             $table->string('title');
            $table->enum('type', ['free_shipping', 'free_product']);
            $table->unsignedBigInteger('category_id')->nullable(); 
            $table->unsignedBigInteger('product_id')->nullable();
            $table->integer('buy_quantity')->default(0);
            $table->integer('get_quantity')->nullable();
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->boolean('allow_coupon_stack')->default(false);
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
