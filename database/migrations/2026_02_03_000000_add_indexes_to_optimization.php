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
        Schema::table('products', function (Blueprint $table) {
            $table->index(['slug', 'status']);
            $table->index(['category_id', 'status']);
            $table->index(['sub_category_id', 'status']);
            $table->index(['child_category_id', 'status']);
            $table->index('is_approved');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->index('slug');
            $table->index('status');
        });

        Schema::table('sub_categories', function (Blueprint $table) {
            $table->index('slug');
            $table->index('status');
        });

        Schema::table('child_categories', function (Blueprint $table) {
            $table->index('slug');
            $table->index('status');
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->index('status');
            $table->index('serial');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['slug', 'status']);
            $table->dropIndex(['category_id', 'status']);
            $table->dropIndex(['sub_category_id', 'status']);
            $table->dropIndex(['child_category_id', 'status']);
            $table->dropIndex('is_approved');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex('slug');
            $table->dropIndex('status');
        });

        Schema::table('sub_categories', function (Blueprint $table) {
            $table->dropIndex('slug');
            $table->dropIndex('status');
        });

        Schema::table('child_categories', function (Blueprint $table) {
            $table->dropIndex('slug');
            $table->dropIndex('status');
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->dropIndex('status');
            $table->dropIndex('serial');
        });
    }
};
