<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        $images = [
            'dummy/product/tv/haier.jpeg',
            'dummy/product/tv/mango.png',
            'dummy/product/tv/samsung32.jpeg',
            'dummy/product/tv/starex-31.jpeg',
            'dummy/product/tv/starex-31.jpeg',
            'dummy/product/tv/starx-32.jpeg'
        ];

        foreach ($products as $key => $product) {

            foreach ($images as $key => $image) {
                $product->galleries()->create([
                    'path' => $image,
                ]);
            }
        }
    }
}
