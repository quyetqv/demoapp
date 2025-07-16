<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo một user mẫu để đăng nhập (nếu chưa có)
        // Lưu ý: Nếu bạn dùng Laravel Breeze/Jetstream, User đã được tạo sẵn qua migration/seeder của chúng
        if (User::count() === 0) {
            User::factory()->create([
                'name' => 'Admin Test',
                'email' => 'admin@test.com',
                'password' => bcrypt('12345678'),
            ]);
        }


        // Gọi các seeder khác theo thứ tự phụ thuộc
        // Quan trọng: Đảm bảo các Model phụ thuộc (User, Category, Product) có dữ liệu trước!
        $this->call([
            CategorySeeder::class, // Tạo danh mục
            ProductSeeder::class,  // Tạo sản phẩm (phụ thuộc Category)
            OrderSeeder::class,    // Tạo đơn hàng (phụ thuộc User)
            OrderItemSeeder::class,// Tạo chi tiết đơn hàng (phụ thuộc Order và Product)
            ReviewSeeder::class,   // Tạo đánh giá (phụ thuộc User và Product)
        ]);
    }
}
