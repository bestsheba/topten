<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $coupons = [
            [
                'title' => 'Welcome Offer',
                'code' => 'welcome10'
            ],
            [
                'title' => 'Save on Your Purchase',
                'code' => 'save20'
            ],
            [
                'title' => 'Free Shipping',
                'code' => 'freeship50'
            ],
            [
                'title' => 'New Year Special',
                'code' => 'newyear2024'
            ],
            [
                'title' => 'Summer Sale Discount',
                'code' => 'summersale15'
            ],
            [
                'title' => 'Extra Savings',
                'code' => 'extra5'
            ],
            [
                'title' => 'Thank You Discount',
                'code' => 'thankyou25'
            ],
            [
                'title' => 'Black Friday Sale',
                'code' => 'blackfriday30'
            ],
            [
                'title' => 'VIP Access',
                'code' => 'vipaccess40'
            ],
            [
                'title' => 'Winter Deal',
                'code' => 'winterdeal60'
            ],
            [
                'title' => 'Flash Sale',
                'code' => 'flashsale10'
            ],
        ];

        foreach ($coupons as $key => $coupon) {
            Coupon::create([
                'title' => $coupon['title'],
                'code' => $coupon['code'],
                'start_date' => Carbon::parse(now()->addDays(rand(0, 3))),
                'expired_date' => Carbon::parse(now()->addDays(rand(2, 34))),
                'discount' => rand(1, 10),
                'discount_type' => Arr::random(['amount', 'percentage']),
            ]);
        }
    }
}
