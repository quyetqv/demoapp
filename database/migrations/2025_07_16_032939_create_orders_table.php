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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Khóa ngoại tới bảng users
            $table->decimal('total_amount', 10, 2); // Tổng số tiền của đơn hàng
            $table->string('status')->default('pending'); // Trạng thái đơn hàng (pending, processing, completed, cancelled)
            $table->string('payment_method')->nullable(); // Phương thức thanh toán
            $table->text('shipping_address')->nullable(); // Địa chỉ giao hàng
            // Bạn có thể thêm các trường khác như billing_address, phone, email, v.v.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
