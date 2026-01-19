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
        try {
            Schema::table('settings', function (Blueprint $table) {
                $table->string('primary_color', 7)->nullable()->after('flash_deal_timer'); // e.g., #FFFFFF
                $table->string('secondary_color', 7)->nullable()->after('primary_color'); // e.g., #000000
                $table->string('text_color', 7)->nullable()->after('secondary_color'); // e.g., #FF0000
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn(['primary_color', 'secondary_color', 'text_color']);
            });
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
};
