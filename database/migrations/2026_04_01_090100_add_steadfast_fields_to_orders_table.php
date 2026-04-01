<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('courier_provider')->nullable()->after('payment_method');
            $table->string('courier_consignment_id')->nullable()->after('courier_provider');
            $table->string('courier_tracking_code')->nullable()->after('courier_consignment_id');
            $table->string('courier_status')->nullable()->after('courier_tracking_code');
            $table->json('courier_response')->nullable()->after('courier_status');
            $table->timestamp('courier_sent_at')->nullable()->after('courier_response');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'courier_provider',
                'courier_consignment_id',
                'courier_tracking_code',
                'courier_status',
                'courier_response',
                'courier_sent_at',
            ]);
        });
    }
};
