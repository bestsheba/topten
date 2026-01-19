<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Slider::create([
            'title' => 'Slider 1',
            'description' => '',
            'image' => 'assets/frontend/images/slider/slider-1.webp',
        ]);

        Slider::create([
            'title' => 'Slider 2',
            'description' => '',
            'image' => 'assets/frontend/images/slider/slider-2.webp',
        ]);

        Slider::create([
            'title' => 'Slider 3',
            'description' => '',
            'image' => 'assets/frontend/images/slider/slider-3.webp',
        ]);
    }
}
