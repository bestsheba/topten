<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                "name" => "Nike",
                "logo" => "dummy/brand/nike.jpg"
            ],
            [
                "name" => "Adidas",
                "logo" => "dummy/brand/adidas.jpg"
            ],
            [
                "name" => "Apple",
                "logo" => "dummy/brand/apple.jpg"
            ],
            [
                "name" => "Samsung",
                "logo" => "dummy/brand/samsung.jpg"
            ],
            [
                "name" => "Sony",
                "logo" => "dummy/brand/sony.jpg"
            ]
        ];

        foreach ($brands as $list) {
            Brand::create([
                'name' => $list['name'],  // Brand name
                'picture' => $list['logo'],  // Image URL for the logo
                'is_active' => 1,  // Assuming you want to set this to 1 (active)
            ]);
        }


        // $lists = [
        //     [
        //         'name' => 'Brand 1',
        //         'picture' => 'assets/frontend/images/category/category-1.jpg',
        //     ],
        //     [
        //         'name' => 'Brand 2',
        //         'picture' => 'assets/frontend/images/category/category-2.jpg',
        //     ],
        //     [
        //         'name' => 'Brand 3',
        //         'picture' => 'assets/frontend/images/category/category-3.jpg',
        //     ],
        //     [
        //         'name' => 'Brand 4',
        //         'picture' => 'assets/frontend/images/category/category-4.jpg',
        //     ],
        //     [
        //         'name' => 'Brand 5',
        //         'picture' => 'assets/frontend/images/category/category-5.jpg',
        //     ],
        //     [
        //         'name' => 'Brand 6',
        //         'picture' => 'assets/frontend/images/category/category-6.jpg',
        //     ],
        // ];

        // foreach ($lists as $key => $list) {
        //     Brand::create([
        //         'name' => $list['name'],
        //         'picture' => $list['picture'],
        //         'is_active' => 1,
        //     ]);
        // }
    }
}
