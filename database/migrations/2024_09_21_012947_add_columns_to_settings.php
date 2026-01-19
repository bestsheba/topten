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
            $table->string('copyright')->nullable()->after('address');
            $table->string('payment_logo')->nullable()->after('address');
            $table->string('facebook')->nullable()->after('address');
            $table->string('youtube')->nullable()->after('address');
            $table->string('instagram')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('copyright');
            $table->dropColumn('payment_logo');
            $table->dropColumn('facebook');
            $table->dropColumn('youtube');
            $table->dropColumn('instagram');
        });
    }
};
