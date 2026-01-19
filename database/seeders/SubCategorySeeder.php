<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sub_categories = [
            [
                'parent_id' => 1,
                "name" => 'Computer',
            ],
            [
                'parent_id' => 1,
                "name" => 'Laptop',
            ],
            [
                'parent_id' => 1,
                "name" => 'Smartphone',
            ],
            [
                'parent_id' => 1,
                "name" => 'Tablets',
            ],
            [
                'parent_id' => 2,
                "name" => 'Men',
            ],
            [
                'parent_id' => 2,
                "name" => 'Women',
            ],
            [
                'parent_id' => 2,
                "name" => 'Watch',
            ],
            [
                'parent_id' => 2,
                "name" => 'Sunglass',
            ],
            [
                'parent_id' => 5,
                "name" => 'Team Sports',
            ],
            [
                'parent_id' => 5,
                "name" => 'Shoes & Clothing',
            ],
            [
                'parent_id' => 5,
                "name" => 'Fitness Accessories',
            ],
            [
                'parent_id' => 6,
                "name" => 'Table',
            ],
            [
                'parent_id' => 6,
                "name" => 'Chair',
            ],
            [
                'parent_id' => 6,
                "name" => 'Sofa',
            ]
        ];

        foreach ($sub_categories as $key => $cat) {

            SubCategory::create([
                'category_id' => $cat['parent_id'],
                'name' => $cat['name'],
                'picture' =>  '',
                'is_active' => 1,
            ]);
        }

        // $cats = Category::all();

        // foreach ($cats as $key => $cat) {
        //     for ($i = 0; $i < 3; $i++) {

        //         $images = [
        //             'assets/frontend/images/category/category-1.jpg',
        //             'assets/frontend/images/category/category-2.jpg',
        //             'assets/frontend/images/category/category-3.jpg',
        //         ];

        //         SubCategory::create([
        //             'category_id' => $cat->id,
        //             'name' => 'Sub Category ' . $i + 1,
        //             'picture' =>  $images[$i],
        //             'is_active' => 1,
        //         ]);
        //     }
        // }
    }
}
