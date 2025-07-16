<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create(); // Lấy sản phẩm hoặc tạo mới
        $quantity = $this->faker->numberBetween(1, 5); // Số lượng từ 1 đến 5

        return [
            'order_id' => Order::inRandomOrder()->first()->id ?? Order::factory(), // Gán cho một đơn hàng ngẫu nhiên
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->price, // Giá sản phẩm tại thời điểm đặt hàng
        ];
    }
}
