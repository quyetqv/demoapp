<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        if (User::count() === 0) {
            User::factory()->create();
        }
        if (Product::count() === 0) {
            Product::factory()->create();
        }

        $users = User::all();
        $products = Product::all();

        $numberOfReviews = 500; // Tăng số lượng đánh giá lên 500
        $createdReviewsCount = 0;
        $maxAttempts = $numberOfReviews * 5;

        while ($createdReviewsCount < $numberOfReviews && $maxAttempts > 0) {
            $user = $users->random();
            $product = $products->random();

            try {
                Review::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'rating' => fake()->numberBetween(1, 5),
                    'comment' => fake()->optional(0.8)->paragraph(rand(1, 3)),
                ]);
                $createdReviewsCount++;
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->getCode() == 23000 || str_contains($e->getMessage(), 'Duplicate entry')) {
                    // Duplicate found, try again
                } else {
                    throw $e;
                }
            }
            $maxAttempts--;
        }

        if ($createdReviewsCount < $numberOfReviews) {
            $this->command->warn("Chỉ tạo được {$createdReviewsCount} đánh giá duy nhất. Có thể không đủ user/sản phẩm để tạo thêm các cặp duy nhất.");
        } else {
             $this->command->info("Đã tạo thành công {$createdReviewsCount} đánh giá.");
        }
    }
}
