<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        // Lấy một User và Product ngẫu nhiên
        $user = User::inRandomOrder()->first() ?? User::factory()->create();
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();

        // Kiểm tra xem cặp user_id và product_id này đã tồn tại trong bảng reviews chưa.
        // Nếu có, tìm cặp khác. Đây là cách đơn giản nhưng có thể chậm nếu có nhiều dữ liệu hoặc trùng lặp cao.
        // Một cách hiệu quả hơn là lấy tất cả các cặp đã tồn tại và loại trừ chúng.
        // Tuy nhiên, với dữ liệu giả, việc "cứ thử và tìm cái mới" thường đủ dùng.
        // Đối với seeding, chúng ta có thể làm đơn giản hơn như sau:

        // Nếu bạn muốn đảm bảo mỗi cặp là duy nhất trong quá trình tạo:
        // Cần lấy danh sách user_id và product_id đã tồn tại để tránh trùng lặp.
        // Hoặc đơn giản là tạo nhiều bản ghi và một số sẽ bị bỏ qua nếu trùng lặp
        // (nhưng điều này không phải là hành vi mặc định của Factory nếu bạn dùng create() sau đó).

        // Để giải quyết lỗi này trực tiếp trong Factory:
        // Đảm bảo không tạo cặp trùng lặp NGAY TRONG LÚC FAKING.
        // Cách tốt nhất là xử lý ở Seeder, không phải Factory, vì Factory chỉ định nghĩa 1 bản ghi.
        // Sẽ quay lại điều này ở bước chỉnh sửa Seeder.

        return [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->optional(0.8)->paragraph(rand(1, 3)),
        ];
    }
}
