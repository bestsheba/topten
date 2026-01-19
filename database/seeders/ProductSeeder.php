<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Services\ProductSkuGenerateService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Arr;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $product_list = json_decode(file_get_contents(base_path('public/dummy/products.json')), true);

        for ($i = 0; $i < count($product_list); $i++) {
            $product_data[] = [
                'name' => $product_list[$i]['title'],
                'slug' => Str::slug($product_list[$i]['title']),
                'price' => rand(200, 6000),
                'picture' => $product_list[$i]['image'],
                'description'  => 'adsfdsf',
                'stock_quantity' => rand(0, 500),
                'is_active' => 1,
                'sku' => (new ProductSkuGenerateService())->generateUniqueSku($product_list[$i]['title']),
                'brand_id' => Brand::inRandomOrder()->value('id'),
                'category_id' => $product_list[$i]['category'],
                'sub_category_id' => SubCategory::where('category_id', $product_list[$i]['category'])->inRandomOrder()->first()?->id ?? null,
                'show_special_offer_list' => Arr::random([1, 0]),
                'discount' => Arr::random([null, rand(1, 10)]),
                'discount_type' => Arr::random(['amount', 'percentage']),
            ];
        }

        $product_chunks = array_chunk($product_data, ceil(count($product_data) / 3));

        foreach ($product_chunks as $product) {
            Product::insert($product);
        }


        // $products = [
        //     [
        //         'name' => 'Couple Sofa',
        //         'price' => '45.00',
        //         'picture' => 'assets/frontend/images/products/product1.jpg',
        //         'description'  => '',
        //         'stock_quantity' => '',
        //         'is_active' => '',
        //         'sku' => '',
        //         'category_id' => 1,
        //     ],

        //     [
        //         'name' => 'Single Sofa',
        //         'picture' => 'assets/frontend/images/products/product2.jpg',
        //         'category_id' => 2,
        //     ],

        //     [
        //         'name' => 'Mattrass X',
        //         'picture' => 'assets/frontend/images/products/product3.jpg',
        //         'category_id' => 3,
        //     ],

        //     [
        //         'name' => 'Bed King Size',
        //         'picture' => 'assets/frontend/images/products/product4.jpg',
        //         'category_id' => 4,
        //     ],

        //     [
        //         'name' => 'Guyer Chair',
        //         'picture' => 'assets/frontend/images/products/product5.jpg',
        //         'category_id' => 5,
        //     ],

        //     [
        //         'name' => 'Plastic Chair',
        //         'picture' => 'assets/frontend/images/products/product6.jpg',
        //         'category_id' => 6,
        //     ],

        //     [
        //         'name' => 'Sleeping Chair',
        //         'picture' => 'assets/frontend/images/products/product7.jpg',
        //         'category_id' => 7,
        //     ],
        //     [
        //         'name' => 'Small Chair',
        //         'picture' => 'assets/frontend/images/products/product8.jpg',
        //         'category_id' => 8,
        //     ],
        //     [
        //         'name' => 'Small White Chair',
        //         'picture' => 'assets/frontend/images/products/product9.jpg',
        //         'category_id' => 1,
        //     ],

        //     [
        //         'name' => 'Small Color Chair',
        //         'picture' => 'assets/frontend/images/products/product10.jpg',
        //         'category_id' => 2,
        //     ],

        //     [
        //         'name' => 'Vintage T9 Hair Cutting Machine',
        //         'picture' => 'assets/frontend/images/products/product11.webp',
        //         'category_id' => 3,
        //     ],

        //     [
        //         'name' => 'Vintage T9 Hair Cutting Machine',
        //         'picture' => 'assets/frontend/images/products/product11.webp',
        //         'category_id' => 4,
        //     ],
        //     [
        //         'name' => 'Double-Layer Egg Dispenser',
        //         'picture' => 'assets/frontend/images/products/product12.webp',
        //         'category_id' => 5,
        //     ],
        //     [
        //         'name' => 'Lexus Vegetable Crackers Biscuit',
        //         'picture' => 'assets/frontend/images/products/product13.webp',
        //         'category_id' => 6,
        //     ],
        //     [
        //         'name' => 'LED Seven Colors Light',
        //         'picture' => 'assets/frontend/images/products/product14.webp',
        //         'category_id' => 7,
        //     ],
        //     [
        //         'name' => 'AO Eyewear Lens Cleaner',
        //         'picture' => 'assets/frontend/images/products/product15.webp',
        //         'category_id' => 8,
        //     ],
        //     [
        //         'name' => 'Symphony A30 Feature Phone',
        //         'picture' => 'assets/frontend/images/products/product16.webp',
        //         'category_id' => 1,
        //     ],
        //     [
        //         'name' => 'P47 Wireless Bluetooth Headphone',
        //         'picture' => 'assets/frontend/images/products/product17.webp',
        //         'category_id' => 2,
        //     ],
        //     [
        //         'name' => 'New Mens Business Luxury Watch',
        //         'picture' => 'assets/frontend/images/products/product18.webp',
        //         'category_id' => 3,
        //     ],
        //     [
        //         'name' => 'Comforter For winter 1 PC King Size Inside Fiber',
        //         'picture' => 'assets/frontend/images/products/product19.webp',
        //         'category_id' => 4,
        //     ],
        //     [
        //         'name' => 'Stylish Leather Wallet',
        //         'picture' => 'assets/frontend/images/products/product20.webp',
        //         'category_id' => 5,
        //     ],
        //     [
        //         'name' => 'Dial Wrist Watch',
        //         'picture' => 'assets/frontend/images/products/product21.webp',
        //         'category_id' => 6,
        //     ],
        //     [
        //         'name' => 'T500 Smart Watch',
        //         'picture' => 'assets/frontend/images/products/product22.webp',
        //         'category_id' => 7,
        //     ],
        //     [
        //         'name' => 'Fresh Garden Anti-Tobacco Air Freshener',
        //         'picture' => 'assets/frontend/images/products/product23.webp',
        //         'category_id' => 8,
        //     ],
        //     [
        //         'name' => 'Fresh Garden Anti-Tobacco Air Freshener',
        //         'picture' => 'assets/frontend/images/products/product23.webp',
        //         'category_id' => 1,
        //     ],
        //     [
        //         'name' => 'VEITHDIA Polarized Sunglass',
        //         'picture' => 'assets/frontend/images/products/product24.webp',
        //         'category_id' => 2,
        //     ],
        //     [
        //         'name' => 'Summer MenS Shoes',
        //         'picture' => 'assets/frontend/images/products/product25.webp',
        //         'category_id' => 3,
        //     ],
        //     [
        //         'name' => 'Tara Liquid Toilet Cleaner - 500ml',
        //         'picture' => 'assets/frontend/images/products/product26.webp',
        //         'category_id' => 4,
        //     ],
        //     [
        //         'name' => '12 পিস প্লাস্টিকের হ্যাঙ্গার',
        //         'picture' => 'assets/frontend/images/products/product27.webp',
        //         'category_id' => 5,
        //     ],
        //     [
        //         'name' => 'REMAX RM 510 In-Ear Earphone',
        //         'picture' => 'assets/frontend/images/products/product28.webp',
        //         'category_id' => 6,
        //     ],
        //     [
        //         'name' => 'Radium Belt Pet Cat Collar',
        //         'picture' => 'assets/frontend/images/products/product29.webp',
        //         'category_id' => 7,
        //     ]
        // ];

        // foreach ($products as $key => $product) {
        //     Product::create([
        //         'name' => $product['name'],
        //         'price' => rand(10, 500),
        //         'picture' => $product['picture'],
        //         'description'  => $this->description(),
        //         'stock_quantity' => rand(0, 500),
        //         'is_active' => 1,
        //         'sku' => (new ProductSkuGenerateService())->generateUniqueSku($product['name']),
        //         'brand_id' => Brand::inRandomOrder()->first()->id,
        //         'category_id' => $product['category_id'],
        //         'sub_category_id' => SubCategory::where('category_id', $product['category_id'])->inRandomOrder()->first()->id,
        //     ]);
        // }
    }

    public function description()
    {

        return '<div class="">
            <div class="text-gray-600">
                <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Tenetur necessitatibus deleniti natus
                    dolore cum maiores suscipit optio itaque voluptatibus veritatis tempora iste facilis non aut
                    sapiente dolor quisquam, ex ab.</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Dolorum, quae accusantium voluptatem
                    blanditiis sapiente voluptatum. Autem ab, dolorum assumenda earum veniam eius illo fugiat possimus
                    illum dolor totam, ducimus excepturi.</p>
                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Error quia modi ut expedita! Iure molestiae
                    labore cumque nobis quasi fuga, quibusdam rem? Temporibus consectetur corrupti rerum veritatis
                    numquam labore amet.</p>
            </div>

            <table class="table-auto border-collapse w-full text-left text-gray-600 text-sm mt-6">
                <tbody><tr>
                    <th class="py-2 px-4 border border-gray-300 w-40 font-medium">Color</th>
                    <th class="py-2 px-4 border border-gray-300 ">Blank, Brown, Red</th>
                </tr>
                <tr>
                    <th class="py-2 px-4 border border-gray-300 w-40 font-medium">Material</th>
                    <th class="py-2 px-4 border border-gray-300 ">Latex</th>
                </tr>
                <tr>
                    <th class="py-2 px-4 border border-gray-300 w-40 font-medium">Weight</th>
                    <th class="py-2 px-4 border border-gray-300 ">55kg</th>
                </tr>
            </tbody></table>
        </div>';
    }
}
