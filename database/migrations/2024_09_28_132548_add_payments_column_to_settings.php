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
            $table->decimal('vat', 10, 2)->default(0);
            $table->string('bkash_number')->nullable();
            $table->string('nagad_number')->nullable();
            $table->string('rocket_number')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('hero_section_banner')->nullable()->default('assets/frontend/images/banner-bg.jpg');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('vat');
            $table->dropColumn('bkash_number');
            $table->dropColumn('nagad_number');
            $table->dropColumn('rocket_number');
            $table->dropColumn('bank_account_number');
            $table->dropColumn('hero_section_banner');
        });
    }
};
