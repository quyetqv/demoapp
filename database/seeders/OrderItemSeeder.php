<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Đảm bảo có ít nhất một đơn hàng và sản phẩm trước khi tạo chi tiết đơn hàng
        if (Order::count() === 0) {
            $this->call(OrderSeeder::class); // Gọi OrderSeeder nếu chưa có order
        }
        if (Product::count() === 0) {
            $this->call(ProductSeeder::class); // Gọi ProductSeeder nếu chưa có product
        }

        OrderItem::factory()->count(50)->create(); // Tạo 50 chi tiết đơn hàng mẫu
    }
}
