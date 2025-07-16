<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\User;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Đảm bảo có ít nhất một user trước khi tạo đơn hàng
        if (User::count() === 0) {
            User::factory()->create();
        }

        Order::factory()->count(20)->create(); // Tạo 20 đơn hàng mẫu
    }
}
