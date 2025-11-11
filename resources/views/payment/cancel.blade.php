<x-app-layout>
    <div class="max-w-xl mx-auto py-12 text-center">
        <h1 class="text-3xl font-bold text-red-600 mb-4">🚫 Giao dịch đã bị hủy</h1>
        <p class="text-lg text-gray-700 mb-6">Mã đơn hàng: <span class="font-mono font-semibold">{{ $orderCode ?? 'N/A' }}</span></p>
        <p class="text-gray-500">Bạn đã chọn hủy giao dịch. Bạn có thể quay lại trang sản phẩm để thử lại.</p>
        <a href="{{ route('products') }}" class="mt-8 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700">
            Trở về trang sản phẩm
        </a>
    </div>
</x-app-layout>