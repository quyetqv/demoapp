<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'payment_method',
        'shipping_address',
    ];

    /**
     * Định nghĩa mối quan hệ: Một đơn hàng thuộc về một người dùng.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Định nghĩa mối quan hệ: Một đơn hàng có nhiều chi tiết đơn hàng (OrderItem).
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
