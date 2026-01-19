<?php

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Customer::class)->nullable()->constrained()->cascadeOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone_number')->nullable();
            $table->string('customer_address')->nullable();
            $table->decimal('delivery_charge', 10, 2)->default(0.00);
            $table->decimal('discount', 10, 2)->default(0);
            $table->float('subtotal');
            $table->float('total');
            $table->string('payment_transaction_id')->nullable();
            $table->integer('coupon_id')->nullable();
            $table->integer('status')->default(1)->comment('status list in order model status function');
            $table->string('hashed_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
