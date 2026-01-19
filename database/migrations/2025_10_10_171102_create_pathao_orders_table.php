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
        Schema::create('pathao_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->string('merchant_order_id');
            $table->string('consignment_id')->nullable();
            $table->string('recipient_name');
            $table->string('recipient_phone');
            $table->string('recipient_secondary_phone')->nullable();
            $table->text('recipient_address');
            $table->integer('recipient_city')->nullable();
            $table->integer('recipient_zone')->nullable();
            $table->integer('recipient_area')->nullable();
            $table->integer('delivery_type'); // 48 for Normal, 12 for On Demand
            $table->integer('item_type'); // 1 for Document, 2 for Parcel
            $table->text('special_instruction')->nullable();
            $table->integer('item_quantity');
            $table->decimal('item_weight', 8, 2);
            $table->text('item_description')->nullable();
            $table->decimal('amount_to_collect', 10, 2);
            $table->decimal('delivery_fee', 8, 2)->nullable();
            $table->string('order_status')->default('Pending');
            $table->text('response_data')->nullable(); // Store full API response
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->index(['order_id', 'consignment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pathao_orders');
    }
};