<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 12; $i++) {
            Blog::create([
                'title' => 'Te nulla oportere reprimique his dolorum',
                'thumbnail' => 'assets/frontend/images/products/product8.jpg',
                'body' => 'Test Body'
            ]);
        }
    }
}
