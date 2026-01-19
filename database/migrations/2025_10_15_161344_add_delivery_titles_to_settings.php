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
            $table->string('delivery_charge_inside_title')->default('Inside Dhaka');
            $table->string('delivery_charge_outside_title')->default('Outside Dhaka');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('delivery_charge_inside_title');
            $table->dropColumn('delivery_charge_outside_title');
        });
    }
};
