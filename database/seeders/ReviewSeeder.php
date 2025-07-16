<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\User;
use App\Models\Product;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Đảm bảo có user và product trước khi tạo review
        if (User::count() === 0) {
            User::factory()->create();
        }
        if (Product::count() === 0) {
            Product::factory()->create(); // Tạo ít nhất một sản phẩm nếu chưa có
        }

        $users = User::all();
        $products = Product::all();

        // Số lượng đánh giá mong muốn
        $numberOfReviews = 30;
        $createdReviewsCount = 0;

        // Giới hạn vòng lặp để tránh vòng lặp vô hạn nếu số lượng kết hợp user/product ít hơn số lượng mong muốn
        $maxAttempts = $numberOfReviews * 5; // Ví dụ: thử tối đa 5 lần cho mỗi review mong muốn

        while ($createdReviewsCount < $numberOfReviews && $maxAttempts > 0) {
            $user = $users->random();
            $product = $products->random();

            // Cố gắng tạo một đánh giá mới.
            // Nếu cặp user_id và product_id đã tồn tại, nó sẽ không tạo và trả về null (hoặc false tùy Laravel version)
            // hoặc object đã tồn tại nếu dùng firstOrCreate.
            try {
                Review::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'rating' => fake()->numberBetween(1, 5),
                    'comment' => fake()->optional(0.8)->paragraph(rand(1, 3)),
                ]);
                $createdReviewsCount++;
            } catch (\Illuminate\Database\QueryException $e) {
                // Lỗi 1062 là lỗi trùng lặp key duy nhất
                if ($e->getCode() == 23000 || str_contains($e->getMessage(), 'Duplicate entry')) {
                    // Cặp đã tồn tại, thử lại với cặp user/product khác
                    // echo "Duplicate found for user {$user->id} and product {$product->id}. Trying again...\n";
                } else {
                    // Xử lý các lỗi khác nếu có
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
