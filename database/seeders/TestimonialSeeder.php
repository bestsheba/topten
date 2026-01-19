<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Emily S.',
                'testimonial' => 'Fantastic shopping experience! The website is easy to navigate, and my order arrived on time. The products are of top-notch quality. Highly recommended!'
            ],
            [
                'name' => 'John D.',
                'testimonial' => 'Amazing customer service! I had a question about my order, and the support team responded within minutes. The quality of the product is excellent, and I’ll definitely be a repeat customer.'
            ],
            [
                'name' => 'Sara M.',
                'testimonial' => 'Quick delivery and high-quality products! I ordered some items for my family, and everyone loved them. Great value for the price. I’ll be shopping here again!'
            ],
            [
                'name' => 'Mark T.',
                'testimonial' => 'Five stars all the way! I’ve never had such a smooth shopping experience. From browsing to checkout, everything was perfect, and my items arrived just as described.'
            ],
            [
                'name' => 'Tina R.',
                'testimonial' => 'Wonderful selection and quality! This site has everything I need, and the quality of the products always exceeds my expectations. I’m a loyal customer now!'
            ],
            [
                'name' => 'Alex H.',
                'testimonial' => 'Exceptional experience! The site is user-friendly, the product descriptions are accurate, and delivery was fast. I’m very satisfied with my purchase!'
            ],
            [
                'name' => 'Melissa W.',
                'testimonial' => 'Great deals and fast shipping! I found exactly what I was looking for at an unbeatable price. The item was well-packaged and arrived on time. I’ll be back for more!'
            ],
            [
                'name' => 'Chris B.',
                'testimonial' => 'Highly recommend! I was skeptical at first, but this site is legit. The product quality is incredible, and they have excellent customer support. Very happy with my experience!'
            ],
            [
                'name' => 'Rachel K.',
                'testimonial' => 'My go-to online store! I’ve shopped here a few times, and each experience has been positive. The product variety is great, and the delivery is always on time.'
            ],
            [
                'name' => 'Liam P.',
                'testimonial' => 'Exceeded my expectations! I was impressed by how quickly my order arrived and how well it was packaged. The quality is fantastic – I’ll be shopping here regularly.'
            ]
        ];

        foreach ($testimonials as $key => $testimonial) {
            Testimonial::create([
                'rating' => rand(3, 5),
                'user_id' => User::inRandomOrder()->first()->id,
                'description' => $testimonial['testimonial']
            ]);
        }
    }
}
