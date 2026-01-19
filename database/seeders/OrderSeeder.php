<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Arr;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 500; $i++) {
            $date = Carbon::today()->subDays(rand(0, 365));
            $user = User::inRandomOrder()->first();
            Order::create([
                "user_id" => $user->id,
                "total" => rand(50, 5000),
                "created_at" => $date,
                "updated_at" => $date,
                "status" => "1",
                "hashed_id" => "fx-" . rand(1, 5000) . $i,
                "payment_method" => "cash_on_delivery",
                "customer_name" => $user->name,
                "customer_phone_number" => "+1 (344) 386-3057",
                "customer_address" => "Dhaka, Bangladesh",
                "payment_transaction_id" => null,
                "discount" => "0.00"
            ]);
        }
    }
}
