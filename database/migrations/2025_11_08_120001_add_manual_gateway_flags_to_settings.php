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
            $table->boolean('bkash_enabled')->default(false)->after('bkash_number_note');
            $table->boolean('nagad_enabled')->default(false)->after('nagad_number_note');
            $table->boolean('rocket_enabled')->default(false)->after('rocket_number_note');
            $table->boolean('bank_enabled')->default(false)->after('bank_account_number_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['bkash_enabled', 'nagad_enabled', 'rocket_enabled', 'bank_enabled']);
        });
    }
};
