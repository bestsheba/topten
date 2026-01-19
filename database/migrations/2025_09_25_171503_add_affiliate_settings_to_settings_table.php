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
            $table->decimal('affiliate_min_withdrawal_amount', 10, 2)->default(100.00)->after('affiliate_commission_percent');
            $table->boolean('affiliate_feature_enabled')->default(true)->after('affiliate_min_withdrawal_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['affiliate_min_withdrawal_amount', 'affiliate_feature_enabled']);
        });
    }
};