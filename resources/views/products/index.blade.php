<x-layouts.guest>
    {{-- Nội dung trang sản phẩm --}}
    <h1 class="text-4xl font-extrabold text-gray-800 mb-8 text-center">Các Sản Phẩm Của Chúng Tôi</h1>

    {{-- Lưới hiển thị sản phẩm --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-8">
        {{-- Vòng lặp qua danh sách sản phẩm --}}
        @forelse ($products as $product)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-transform duration-300 hover:scale-105">
                {{-- Ảnh sản phẩm --}}
                @if($product->image)
                    {{-- Giả định ảnh được lưu trong storage/app/public và đã tạo symbolic link public/storage --}}
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-56 object-cover object-center">
                @else
                    {{-- Ảnh placeholder nếu không có ảnh --}}
                    <div class="w-full h-56 bg-gray-200 flex items-center justify-center text-gray-500 text-lg">Không có ảnh</div>
                @endif
                <div class="p-6">
                    {{-- Tên sản phẩm --}}
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h2>
                    {{-- Danh mục sản phẩm (nếu có) --}}
                    @if ($product->category)
                        <p class="text-sm text-gray-500 mb-2">Danh mục: <span class="font-semibold">{{ $product->category->name }}</span></p>
                    @endif
                    {{-- Mô tả sản phẩm (giới hạn ký tự) --}}
                    <p class="text-gray-700 text-sm mb-4 line-clamp-3">{{ Str::limit($product->description, 70) }}</p>
                    <div class="flex justify-between items-center mt-4">
                        {{-- Giá sản phẩm --}}
                        <p class="text-2xl font-extrabold text-blue-700">${{ number_format($product->price, 2) }}</p>
                        {{-- Nút xem chi tiết sản phẩm --}}
                        <a href="{{ route('products.show', $product->slug) }}" class="bg-blue-600 text-white py-2 px-5 rounded-lg hover:bg-blue-700 transition-colors duration-300">Xem chi tiết</a>
                    </div>
                </div>
            </div>
        @empty
            {{-- Thông báo nếu không có sản phẩm nào --}}
            <p class="text-xl text-gray-600 col-span-full text-center">Không tìm thấy sản phẩm nào. Vui lòng chạy `sail artisan db:seed` để thêm dữ liệu mẫu.</p>
        @endforelse

    </div>
    <div class="mt-8">
        {{ $products->links() }}
    </div>
</x-layouts.guest>
