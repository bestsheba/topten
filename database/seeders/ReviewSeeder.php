<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure we have some users
        $userCount = User::count();
        if ($userCount < 50) {
            // Create additional users if not enough
            User::factory()->count(50 - $userCount)->create();
        }

        // Get all active products
        $products = Product::active()->get();

        // Prepare users for reviews
        $users = User::inRandomOrder()->limit(50)->get();

        // Generate reviews
        foreach ($products as $product) {
            // Randomly decide how many reviews this product will have (0-20)
            $reviewCount = rand(0, 20);

            // Shuffle users to get random reviewers
            $reviewUsers = $users->shuffle()->take($reviewCount);

            foreach ($reviewUsers as $user) {
                // Check if user has already reviewed this product
                $existingReview = Review::where('product_id', $product->id)
                    ->where('user_id', $user->id)
                    ->exists();

                if (!$existingReview) {
                    Review::create([
                        'product_id' => $product->id,
                        'user_id' => $user->id,
                        'star' => rand(1, 5),
                        'comment' => $this->generateReviewComment(),
                        'created_at' => now()->subDays(rand(1, 365))
                    ]);
                }
            }
        }
    }

    /**
     * Generate a random review comment
     */
    private function generateReviewComment(): string
    {
        $positiveComments = [
            'Great product! Highly recommend.',
            'Excellent quality, exceeded my expectations.',
            'Very satisfied with my purchase.',
            'Amazing value for money.',
            'Would definitely buy again.',
            'Perfect for my needs.',
            'Fantastic product, fast shipping.',
            'Impressive features and performance.',
            'Exactly as described in the listing.',
            'Couldn\'t be happier with this purchase.'
        ];

        $neutralComments = [
            'Decent product, does the job.',
            'Okay, but could be improved.',
            'Meets basic expectations.',
            'Nothing special, but not bad.',
            'Average product.',
            'Reasonable quality for the price.',
            'Works fine, no major complaints.',
            'Satisfactory purchase.',
            'Does what it\'s supposed to do.',
            'No significant issues.'
        ];

        $criticalComments = [
            'Could be better.',
            'Some room for improvement.',
            'Not entirely satisfied.',
            'Didn\'t fully meet my expectations.',
            'Has a few minor drawbacks.',
            'Decent, but not outstanding.',
            'Mixed feelings about this product.',
            'Some good points, some not so good.',
            'Might need some refinements.',
            'Slightly disappointed.'
        ];

        $commentTypes = [
            $positiveComments,
            $neutralComments,
            $criticalComments
        ];

        // Weighted selection: more positive reviews
        $selectedType = rand(0, 9) < 7 ? 0 : (rand(0, 1) ? 1 : 2);

        return $commentTypes[$selectedType][array_rand($commentTypes[$selectedType])];
    }
}
