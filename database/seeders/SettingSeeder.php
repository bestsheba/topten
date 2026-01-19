<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::create([
            'website_name' => 'Best Commerce - An E-commerce Shopping mall',
            'website_logo' => 'assets/frontend/images/logo.png',
            'website_favicon' => 'assets/frontend/images/logo.png',
            'whatsapp_number' => '1894839491',
            'phone_number' => '+8801894839491',
            'email' => 'shop@bestsheba.com',
            'address' => 'Naokhali, Bangladesh',
            'payment_logo' => 'assets/frontend/images/payment.png',
            'bkash_number' => '018670000000',
            'nagad_number' => '0912700000000',
            'rocket_number' => '0923700000000',
            'bank_account_number' => 'Demo account info',
            'footer_logo' => 'assets/frontend/images/logo.png',
            'flash_deal_timer' => now()->addDays(20),
            'primary_color' => '#000000',
            'secondary_color' => '#ffffff',
            'text_color' => '#ffffff'
        ]);
    }
}
