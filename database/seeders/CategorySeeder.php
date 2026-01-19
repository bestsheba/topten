<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                "name" => 'Electronics',
                "image" => 'dummy/category/electronics.png',
            ],
            [
                "name" => 'Fashion',
                "image" => 'dummy/category/fashion.png',
            ],
            [
                "name" => 'Organic',
                "image" => 'dummy/category/organic.png',
            ],
            [
                "name" => 'Groceries',
                "image" => 'dummy/category/grocery.png',
            ],
            [
                "name" => 'Sports & Outdoors',
                "image" => 'dummy/category/sports.png',
            ],
            [
                "name" => 'Furniture',
                "image" => 'dummy/category/furniture.png',
            ],
            [
                "name" => 'Health & Beauty',
                "image" => 'dummy/category/health.png',
            ],
            [
                "name" => 'TV & Homes',
                "image" => 'dummy/category/tv.png',
            ],
        ];

        foreach ($categories as $category) {

            Category::create([
                'name' => $category['name'],
                'picture' => $category['image'],
                'is_active' => 1,
            ]);
        }
    }
}
