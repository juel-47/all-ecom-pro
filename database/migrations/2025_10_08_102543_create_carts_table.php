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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            
            //older code
            // $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            // $table->foreignId('product_id')->constrained()->onDelete('cascade');
            // // $table->json('variant_items')->nullable();
            // $table->integer('quantity');
            // $table->decimal('price', 10, 2);
            // $table->string('session_id')->nullable(); // guest users
            // $table->timestamps();

            //new code
            $table->unsignedBigInteger('user_id')->nullable(); 
            $table->string('session_id')->nullable();
            $table->unsignedBigInteger('product_id'); 
            $table->integer('quantity')->default(1); 
            $table->decimal('price', 10, 2); 
            $table->json('options')->nullable(); 
            $table->timestamps();

            // Optional: Indexes for better performance
            $table->index('user_id');
            $table->index('session_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
