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
            $table->string('bkash_number_note')->nullable()->default('Personal');
            $table->string('nagad_number_note')->nullable()->default('Personal');
            $table->string('rocket_number_note')->nullable()->default('Personal');
            $table->string('bank_account_number_note')->nullable()->default('City Bank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('bkash_number_note');
            $table->dropColumn('nagad_number_note');
            $table->dropColumn('rocket_number_note');
            $table->dropColumn('bank_account_number_note');
        });
    }
};
