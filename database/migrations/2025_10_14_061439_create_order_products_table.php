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
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('product_id');
            // $table->integer('vendor_id');
            $table->string('product_name');
            $table->text('variants');
            $table->integer('variants_total')->nullable();
            $table->string('unit_price');
            $table->integer('qty');
            $table->decimal('extra_price', 10, 2)->default(0); // extra customization price
            $table->text('front_image')->nullable();         // front image path/url
            $table->text('back_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
