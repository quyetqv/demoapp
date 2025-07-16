<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(), // Gán cho một user ngẫu nhiên hoặc tạo mới
            'total_amount' => $this->faker->randomFloat(2, 100, 5000), // Tổng tiền từ 100 đến 5000
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'cancelled']), // Trạng thái ngẫu nhiên
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal', 'cash_on_delivery']), // Phương thức ngẫu nhiên
            'shipping_address' => $this->faker->address, // Địa chỉ ngẫu nhiên
        ];
    }
}
