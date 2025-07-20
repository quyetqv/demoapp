<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Đảm bảo có danh mục trước khi tạo sản phẩm
        if (Category::count() === 0) {
            Category::factory()->create();
        }

        Product::factory()->count(500)->create(); // Tạo 50 sản phẩm mẫu
    }
}
