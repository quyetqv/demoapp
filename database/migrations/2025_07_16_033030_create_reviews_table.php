<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Khóa ngoại tới bảng users
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Khóa ngoại tới bảng products
            $table->integer('rating')->unsigned(); // Điểm đánh giá (ví dụ: 1-5 sao, unsigned để đảm bảo không âm)
            $table->text('comment')->nullable(); // Nội dung bình luận, có thể để trống
            $table->timestamps();
            // Đảm bảo mỗi user chỉ có thể đánh giá 1 sản phẩm 1 lần (tùy chọn, thêm unique constraint)
            $table->index(['user_id', 'product_id']); // Nếu bạn chỉ muốn tạo index để tăng tốc truy vấn mà không cần unique
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
