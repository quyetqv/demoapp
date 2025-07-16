<x-layouts.guest>
    <div class="container mx-auto px-2 sm:px-6">
        <div class="bg-white p-8 rounded-xl shadow-lg flex flex-col md:flex-row gap-10 items-start">
            {{-- Phần hiển thị ảnh sản phẩm --}}
            <div class="md:w-1/2">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-auto object-cover rounded-lg shadow-md">
                @else
                    <div class="w-full h-96 bg-gray-200 flex items-center justify-center text-gray-500 text-xl rounded-lg shadow-md">Không có ảnh</div>
                @endif
            </div>

            {{-- Phần thông tin sản phẩm và nút thêm giỏ hàng --}}
            <div class="md:w-1/2 flex flex-col justify-between">
                <div>
                    <h1 class="text-5xl font-extrabold text-gray-900 mb-4">{{ $product->name }}</h1>
                    @if ($product->category)
                        <p class="text-lg text-gray-600 mb-3">Danh mục: <span class="font-semibold">{{ $product->category->name }}</span></p>
                    @endif
                    <p class="text-gray-800 text-lg leading-relaxed mb-6">{{ $product->description }}</p>
                    <p class="text-4xl font-extrabold text-blue-700 mb-6">${{ number_format($product->price, 2) }}</p>
                    <p class="text-xl text-gray-700 mb-8">Tồn kho: <span class="font-semibold">{{ $product->stock }}</span> sản phẩm còn lại</p>

                    <button class="bg-green-600 text-white py-4 px-8 rounded-xl text-2xl font-bold hover:bg-green-700 transition-colors duration-300 shadow-md">Thêm vào Giỏ hàng</button>
                </div>

                {{-- Phần đánh giá sản phẩm (sẽ được phát triển sau) --}}
                <div class="mt-12 border-t pt-8 border-gray-200">
                    <h3 class="text-3xl font-bold text-gray-900 mb-6">Đánh giá của khách hàng</h3>
                    <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                        <p class="text-gray-700 italic">Chưa có đánh giá nào. Hãy là người đầu tiên đánh giá sản phẩm này!</p>
                        {{-- Form thêm đánh giá sẽ được đặt ở đây --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.guest>
