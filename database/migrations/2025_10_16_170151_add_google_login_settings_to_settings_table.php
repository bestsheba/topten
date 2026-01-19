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
            $table->string('google_client_id')->nullable()->after('affiliate_feature_enabled');
            $table->string('google_client_secret')->nullable()->after('google_client_id');
            $table->boolean('google_login_enabled')->default(false)->after('google_client_secret');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['google_client_id', 'google_client_secret', 'google_login_enabled']);
        });
    }
};