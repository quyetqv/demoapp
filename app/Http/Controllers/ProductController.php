<?php

namespace App\Http\Controllers;

use App\Models\Product; // Import Product Model
use App\Models\Category; // Import Category Model (nếu bạn muốn lọc/liên kết theo danh mục)
use Illuminate\Http\Request; // Import Request class để xử lý các yêu cầu HTTP
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     * Hiển thị danh sách tất cả sản phẩm.
     */
    public function index()
    {
        // Lấy tất cả sản phẩm từ database.
        // Dùng `with('category')` để tải trước thông tin danh mục, tránh vấn đề N+1 queries.
        $products = Product::with('category')->paginate(10); // 20 sản phẩm mỗi trang, có thể thay đổi số lượng

        // Trả về view 'products.index' và truyền biến $products vào.
        return view('products.index', compact('products'));
    }

    /**
     * Display the specified product.
     * Hiển thị chi tiết một sản phẩm cụ thể.
     *
     * @param  \App\Models\Product  $product (Laravel's Route Model Binding)
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        // Laravel's Route Model Binding tự động tìm sản phẩm dựa trên slug (hoặc ID)
        // và inject đối tượng Product vào đây. Nếu không tìm thấy, nó sẽ tự động trả về 404.

        // Trả về view 'products.show' và truyền biến $product vào.
        return view('products.show', compact('product'));
    }

    // --- Các phương thức khác cho Quản trị viên (Admin) ---
    // (Bạn sẽ phát triển các phương thức này khi xây dựng chức năng quản lý sản phẩm cho admin)

    /**
     * Show the form for creating a new product.
     * Hiển thị form để tạo sản phẩm mới.
     */
    // public function create()
    // {
    //     $categories = Category::all(); // Lấy tất cả danh mục để hiển thị trong dropdown
    //     return view('admin.products.create', compact('categories'));
    // }

    /**
     * Store a newly created product in storage.
     * Lưu sản phẩm mới vào database.
     */
    // public function store(Request $request)
    // {
    //     // Xác thực dữ liệu đầu vào
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'slug' => 'required|string|unique:products,slug|max:255',
    //         'description' => 'nullable|string',
    //         'price' => 'required|numeric|min:0',
    //         'stock' => 'required|integer|min:0',
    //         'category_id' => 'required|exists:categories,id',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Quy tắc cho file ảnh
    //     ]);

    //     $data = $request->except('image'); // Lấy tất cả dữ liệu trừ 'image'

    //     // Xử lý upload ảnh
    //     if ($request->hasFile('image')) {
    //         $imagePath = $request->file('image')->store('products', 'public'); // Lưu ảnh vào thư mục 'products' trong storage/app/public
    //         $data['image'] = $imagePath;
    //     }

    //     Product::create($data); // Tạo sản phẩm mới

    //     return redirect()->route('products.index')->with('success', 'Sản phẩm đã được tạo thành công!');
    // }

    /**
     * Show the form for editing the specified product.
     * Hiển thị form để chỉnh sửa sản phẩm.
     */
    // public function edit(Product $product)
    // {
    //     $categories = Category::all();
    //     return view('admin.products.edit', compact('product', 'categories'));
    // }

    /**
     * Update the specified product in storage.
     * Cập nhật thông tin sản phẩm.
     */
    // public function update(Request $request, Product $product)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'slug' => 'required|string|unique:products,slug,' . $product->id . '|max:255', // Unique trừ chính nó
    //         'description' => 'nullable|string',
    //         'price' => 'required|numeric|min:0',
    //         'stock' => 'required|integer|min:0',
    //         'category_id' => 'required|exists:categories,id',
    //         'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     ]);

    //     $data = $request->except('image');

    //     // Xử lý cập nhật ảnh (xóa ảnh cũ nếu có và lưu ảnh mới)
    //     if ($request->hasFile('image')) {
    //         if ($product->image) {
    //             Storage::disk('public')->delete($product->image);
    //         }
    //         $imagePath = $request->file('image')->store('products', 'public');
    //         $data['image'] = $imagePath;
    //     }

    //     $product->update($data);

    //     return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật thành công!');
    // }

    /**
     * Remove the specified product from storage.
     * Xóa sản phẩm khỏi database.
     */
    // public function destroy(Product $product)
    // {
    //     // Xóa ảnh liên quan nếu có
    //     if ($product->image) {
    //         Storage::disk('public')->delete($product->image);
    //     }
    //     $product->delete();

    //     return redirect()->route('products.index')->with('success', 'Sản phẩm đã được xóa thành công!');
    // }
}
