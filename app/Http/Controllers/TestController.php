<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index()
    {
            // --- 1. Top 5 sản phẩm bán chạy nhất (dựa trên số lượng trong đơn hàng) ---
        // Phương pháp: JOIN OrderItem với Product, nhóm theo Product và tính tổng quantity.
        $topSellingProducts = Product::select('products.name', DB::raw('SUM(order_items.quantity) as total_quantity_sold'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity_sold')
            ->limit(5)
            ->get();

        echo "<h3>1. Top 5 Sản Phẩm Bán Chạy Nhất:</h3>";
        foreach ($topSellingProducts as $product) {
            echo "Tên: {$product->name}, Số lượng đã bán: {$product->total_quantity_sold}<br>";
        }
        echo "<hr>";

        // --- 2. Danh mục có doanh thu cao nhất ---
        // Phương pháp: JOIN Category -> Product -> OrderItem, nhóm theo Category và tính tổng (quantity * price).
        $topRevenueCategory = Category::select('categories.name', DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue'))
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->first(); // Lấy danh mục có doanh thu cao nhất

        echo "<h3>2. Danh Mục Có Doanh Thu Cao Nhất:</h3>";
        if ($topRevenueCategory) {
            echo "Tên danh mục: {$topRevenueCategory->name}, Doanh thu: $" . number_format($topRevenueCategory->total_revenue, 2) . "<br>";
        } else {
            echo "Không tìm thấy danh mục có doanh thu.<br>";
        }
        echo "<hr>";


        // --- 3. Khách hàng chi tiêu nhiều nhất (Top 3 Users) ---
        // Phương pháp: JOIN User -> Order, nhóm theo User và tính tổng total_amount.
        $topSpenders = User::select('users.name', 'users.email', DB::raw('SUM(orders.total_amount) as total_spent'))
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(3)
            ->get();

        echo "<h3>3. Khách Hàng Chi Tiêu Nhiều Nhất (Top 3):</h3>";
        foreach ($topSpenders as $user) {
            echo "Tên: {$user->name}, Email: {$user->email}, Tổng chi tiêu: $" . number_format($user->total_spent, 2) . "<br>";
        }
        echo "<hr>";


        // --- 4. Sản phẩm chưa có đánh giá hoặc có số lượng đánh giá thấp hơn mức trung bình ---
        // Phương pháp: LEFT JOIN Reviews, sau đó COUNT và HAVING.
        // Bước 1: Tính trung bình số lượng đánh giá trên mỗi sản phẩm (cho những sản phẩm có đánh giá)
        $avgReviewsPerProduct = Review::select(DB::raw('COUNT(product_id) as review_count'))
            ->groupBy('product_id')
            ->pluck('review_count')
            ->average();

        // Bước 2: Tìm sản phẩm có số lượng đánh giá ít hơn mức trung bình hoặc chưa có đánh giá
        $underperformingProducts = Product::leftJoin('reviews', 'products.id', '=', 'reviews.product_id')
            ->select(
                'products.name',
                DB::raw('COUNT(reviews.id) as total_reviews'),
                DB::raw('AVG(reviews.rating) as average_rating')
            )
            ->groupBy('products.id', 'products.name')
            ->havingRaw('COUNT(reviews.id) = 0 OR COUNT(reviews.id) < ?', [$avgReviewsPerProduct]) // Sản phẩm chưa có đánh giá HOẶC ít hơn trung bình
            ->orderBy('total_reviews')
            ->get();

        echo "<h3>4. Sản Phẩm Có Ít Đánh Giá Hơn Mức Trung Bình ({$avgReviewsPerProduct} TB):</h3>";
        foreach ($underperformingProducts as $product) {
            echo "Tên: {$product->name}, Tổng đánh giá: {$product->total_reviews}, Đánh giá TB: " . number_format($product->average_rating, 2) . "<br>";
        }
        echo "<hr>";

        // --- 5. Đơn hàng được đặt trong tháng trước và trạng thái 'pending' hoặc 'processing' ---
        // Phương pháp: WHERE giữa hai ngày và WHERE IN cho trạng thái.
        $lastMonthPendingOrders = Order::whereIn('status', ['pending', 'processing'])
            ->whereBetween('created_at', [
                now()->subMonth()->startOfMonth(), // Bắt đầu tháng trước
                now()->subMonth()->endOfMonth()   // Kết thúc tháng trước
            ])
            ->with('user') // Tải thông tin người dùng liên quan để hiển thị
            ->limit(10) // Giới hạn để dễ đọc
            ->get();

        echo "<h3>5. Đơn Hàng 'Pending'/'Processing' Trong Tháng Trước:</h3>";
        foreach ($lastMonthPendingOrders as $order) {
            echo "ID Đơn: {$order->id}, User: {$order->user->name}, Tổng tiền: $" . number_format($order->total_amount, 2) . ", Trạng thái: {$order->status}, Ngày đặt: {$order->created_at->format('Y-m-d')}<br>";
        }
        echo "<hr>";

        // --- 6. Top 5 sản phẩm có đánh giá trung bình cao nhất (chỉ những sản phẩm có ít nhất 10 đánh giá) ---
        // Phương pháp: JOIN Reviews, GROUP BY, HAVING COUNT, và ORDER BY AVG.
        $topRatedProducts = Product::select('products.name', DB::raw('AVG(reviews.rating) as average_rating'), DB::raw('COUNT(reviews.id) as total_reviews'))
            ->join('reviews', 'products.id', '=', 'reviews.product_id')
            ->groupBy('products.id', 'products.name')
            ->having('total_reviews', '>=', 10) // Chỉ bao gồm sản phẩm có ít nhất 10 đánh giá
            ->orderByDesc('average_rating')
            ->limit(5)
            ->get();

        echo "<h3>6. Top 5 Sản Phẩm Được Đánh Giá Cao Nhất (min 10 reviews):</h3>";
        if ($topRatedProducts->isEmpty()) {
            echo "Chưa có sản phẩm nào đạt tiêu chí.<br>";
        } else {
            foreach ($topRatedProducts as $product) {
                echo "Tên: {$product->name}, Đánh giá TB: " . number_format($product->average_rating, 2) . ", Tổng đánh giá: {$product->total_reviews}<br>";
            }
        }
        echo "<hr>";

        // --- 7. Tìm khách hàng đã mua cả sản phẩm X và sản phẩm Y (Intersection) ---
        // Giả sử Product ID 1 và Product ID 2
        $productXId = 1;
        $productYId = 2;

        $usersWhoBoughtBoth = User::whereHas('orders.orderItems', function ($query) use ($productXId) {
            $query->where('product_id', $productXId);
        })->whereHas('orders.orderItems', function ($query) use ($productYId) {
            $query->where('product_id', $productYId);
        })->get();

        echo "<h3>7. Khách Hàng Đã Mua Cả Sản Phẩm ID {$productXId} và ID {$productYId}:</h3>";
        if ($usersWhoBoughtBoth->isEmpty()) {
            echo "Không có khách hàng nào mua cả hai sản phẩm.<br>";
        } else {
            foreach ($usersWhoBoughtBoth as $user) {
                echo "ID: {$user->id}, Tên: {$user->name}, Email: {$user->email}<br>";
            }
        }
        echo "<hr>";
    }
}
