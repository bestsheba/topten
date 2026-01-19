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
        Schema::table('settings', function (Blueprint $table) {
            $table->boolean('cash_on_delivery_enabled')->default(true)->after('bank_account_number_note');
            $table->boolean('online_payment_enabled')->default(true)->after('cash_on_delivery_enabled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['cash_on_delivery_enabled', 'online_payment_enabled']);
        });
    }
};
