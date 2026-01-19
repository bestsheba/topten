<?php

namespace Database\Seeders;

use App\Models\OfferSlider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfferSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OfferSlider::create([
            'title' => 'Slider 1',
            'image' => 'assets/frontend/images/slider/slider-1.webp',
        ]);

        OfferSlider::create([
            'title' => 'Slider 2',
            'image' => 'assets/frontend/images/slider/slider-2.webp',
        ]);

        OfferSlider::create([
            'title' => 'Slider 3',
            'image' => 'assets/frontend/images/slider/slider-3.webp',
        ]);
    }
}
