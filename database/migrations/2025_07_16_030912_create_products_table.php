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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Khóa ngoại liên kết với bảng categories
            $table->string('name');
            $table->string('slug')->unique(); // Slug cho URL thân thiện, phải là duy nhất
            $table->text('description')->nullable(); // Mô tả sản phẩm, có thể để trống
            $table->decimal('price', 10, 2); // Giá sản phẩm (tối đa 10 chữ số, 2 chữ số thập phân)
            $table->integer('stock')->default(0); // Số lượng tồn kho, mặc định là 0
            $table->string('image')->nullable(); // Đường dẫn đến ảnh sản phẩm, có thể để trống
            $table->timestamps(); // created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
