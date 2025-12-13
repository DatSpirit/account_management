<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                {{-- Nút Back: Liên kết về trang danh sách --}}
                <a href="{{ route('admin.transactions.all-transactions') }}"
                    class="p-2 rounded-full text-gray-600 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 bg-gray-100 dark:bg-gray-700 transition-all duration-200 shadow-md">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 tracking-tight">
                    Chi tiết giao dịch #{{ $transaction->order_code }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto space-y-6">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Cột Chính (Thông tin và Mô tả) --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- 1. Trạng thái & Thời gian --}}
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            {{-- Trạng thái --}}
                            <div class="space-y-1">
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">
                                    Trạng thái hiện tại</p>
                                @php
                                    $statusConfig = [
                                        'success' => [
                                            'bg' => 'bg-green-100 dark:bg-green-900/50',
                                            'text' => 'text-green-800 dark:text-green-300',
                                            'label' => 'Success',
                                            'icon' => '✓',
                                        ],
                                        'pending' => [
                                            'bg' => 'bg-yellow-100 dark:bg-yellow-900/50',
                                            'text' => 'text-yellow-800 dark:text-yellow-300',
                                            'label' => 'Pending',
                                            'icon' => '⏱',
                                        ],
                                        'failed' => [
                                            'bg' => 'bg-red-100 dark:bg-red-900/50',
                                            'text' => 'text-red-800 dark:text-red-300',
                                            'label' => 'Failed',
                                            'icon' => '✗',
                                        ],
                                        'cancelled' => [
                                            'bg' => 'bg-gray-100 dark:bg-gray-700',
                                            'text' => 'text-gray-800 dark:text-gray-300',
                                            'label' => 'Cancelled',
                                            'icon' => '⊘',
                                        ],
                                    ];
                                    $config = $statusConfig[$transaction->status] ?? $statusConfig['pending'];
                                @endphp
                                <span
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-lg font-bold shadow-md {{ $config['bg'] }} {{ $config['text'] }}">
                                    <span class="mr-2">{{ $config['icon'] }}</span>
                                    {{ $config['label'] }}
                                </span>
                            </div>

                            {{-- Thời gian --}}
                            <div class="text-right space-y-1">
                                <p
                                    class="text-sm text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider">
                                    Thời gian tạo</p>
                                <p class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $transaction->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s, d/m/Y') }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $transaction->created_at->setTimezone('Asia/Ho_Chi_Minh')->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Thông tin giao dịch chi tiết --}}
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                        <h3
                            class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 border-b pb-2 border-gray-200 dark:border-gray-700">
                            Thông tin chi tiết</h3>

                        <dl class="space-y-4">
                            <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Order Code</dt>
                                <dd class="text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                                    #{{ $transaction->order_code }}</dd>
                            </div>
                            <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Người dùng</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $transaction->user->name ?? 'Guest' }}</dd>
                            </div>
                            <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $transaction->user->email ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between border-b border-gray-100 dark:border-gray-700 pb-2">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sản phẩm</dt>
                                <dd class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $transaction->product->name ?? 'N/A' }}</dd>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-gray-200 dark:border-gray-600">
                                <dt class="text-base font-bold text-gray-900 dark:text-gray-100">Tổng tiền</dt>
                                <dd class="text-xl font-extrabold text-green-600 dark:text-green-400">
                                    {{ number_format($transaction->amount, 0, ',', '.') }} VND</dd>
                            </div>
                        </dl>
                    </div>
                    {{-- 3. Mô tả Giao dịch (Hiển thị dữ liệu thô JSON từ webhook) --}}
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                        <div
                            class="flex items-center justify-between mb-4 border-b pb-2 border-gray-200 dark:border-gray-700">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                Dữ liệu Thô từ Webhook (Raw JSON)
                            </h3>

                            {{-- Nút Copy --}}
                            <button onclick="copyRawJson(this)"
                                class="px-4 py-1.5 text-sm font-medium rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition">
                                Copy
                            </button>
                        </div>

                        <div
                            class="rounded-lg bg-gray-50 dark:bg-gray-900/50 p-4 border border-gray-200 dark:border-gray-700 overflow-auto">

                            @php
                                $rawJson = $transaction->raw_payload;
                                $decoded = null;
                                $isJsonValid = false;

                                if (!empty($rawJson)) {
                                    // Thử decode
                                    $decoded = json_decode($rawJson, true);

                                    // Kiểm tra xem decode có thành công không
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                        $isJsonValid = true;
                                    } else {
                                        // Nếu DB lưu dạng Array/Object do Eloquent Cast (trường hợp hiếm), ta ép kiểu
                                        if (is_array($rawJson) || is_object($rawJson)) {
                                            $decoded = (array) $rawJson;
                                            $isJsonValid = true;
                                        }
                                    }
                                }
                            @endphp

                            <div class="rounded-lg bg-gray-900 p-4 border border-gray-700 overflow-auto max-h-96">
                                @if ($isJsonValid)
                                    <pre id="rawJsonContent" class="text-xs sm:text-sm text-green-400 font-mono whitespace-pre-wrap">
{{ json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
        </pre>
                                @else
                                    <div class="text-gray-400 italic">
                                        <p>Không có dữ liệu thô hoặc định dạng không hợp lệ.</p>
                                        {{-- Debug: Hiển thị cái đang có trong DB để kiểm tra --}}
                                        <p class="text-xs mt-2 text-red-400">Raw Data in DB:
                                            {{ Str::limit($transaction->raw_payload, 100) }}</p>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>

                    {{-- Script Copy --}}
                    <script>
                        function copyRawJson(btn) {
                            const content = document.getElementById('rawJsonContent')?.innerText;

                            if (!content) {
                                alert("Không có dữ liệu để copy!");
                                return;
                            }

                            navigator.clipboard.writeText(content).then(() => {
                                const original = btn.innerText;

                                btn.innerText = "Đã sao chép ✔";
                                btn.classList.remove("bg-blue-600");
                                btn.classList.add("bg-green-600");

                                setTimeout(() => {
                                    btn.innerText = original;
                                    btn.classList.remove("bg-green-600");
                                    btn.classList.add("bg-blue-600");
                                }, 1500);
                            });
                        }
                    </script>

                </div>

                {{-- Cột Phụ (Hành động & Lịch sử) --}}
                <div class="space-y-6">

                    {{-- 4. Thao tác / Cập nhật trạng thái --}}
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl p-6 border border-gray-200 dark:border-gray-700 space-y-4">
                        <h3
                            class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 border-b pb-2 border-gray-200 dark:border-gray-700">
                            Hành động</h3>

                        {{-- Form cập nhật trạng thái (Đã sửa thành PATCH) --}}
                        <form method="POST" action="{{ route('admin.transactions.update-status', $transaction->id) }}"
                            class="space-y-4">
                            @csrf
                            {{-- Sử dụng PATCH để phù hợp với route của bạn --}}
                            @method('PATCH')

                            <select name="status" id="update_status"
                                class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-500/50 transition-all duration-200">
                                <option value="pending" {{ $transaction->status == 'pending' ? 'selected' : '' }}>
                                    Pending</option>
                                <option value="success" {{ $transaction->status == 'success' ? 'selected' : '' }}>
                                    Success</option>
                                <option value="failed" {{ $transaction->status == 'failed' ? 'selected' : '' }}>Failed
                                </option>
                                <option value="cancelled" {{ $transaction->status == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled</option>
                            </select>

                            <button type="submit"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition-all duration-200 shadow-md">
                                Cập nhật trạng thái
                            </button>
                        </form>

                        {{-- Bổ sung: Nút In hóa đơn --}}
                        <button onclick="window.print()"
                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 transition-all duration-200 shadow-md">
                            🖨️ In hóa đơn
                        </button>

                    </div>

                    {{-- 5. Lịch sử giao dịch (History Timeline) --}}
                    <div
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                        <h3
                            class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 border-b pb-2 border-gray-200 dark:border-gray-700">
                            Lịch sử hoạt động</h3>

                        <div class="flow-root">
                            <ul role="list" class="-mb-6">
                                {{-- Tạo giao dịch --}}
                                <li class="relative pb-6">
                                    <div class="relative flex space-x-3">
                                        <div class="relative">
                                            <span
                                                class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </span>
                                            <span
                                                class="absolute top-10 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-600"
                                                aria-hidden="true"></span>
                                        </div>

                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">Tạo giao
                                                    dịch</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Thời gian tạo:
                                                    {{ $transaction->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('H:i d/m') }}
                                                </p>
                                            </div>
                                            <div
                                                class="text-right text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap pt-1">
                                                {{ $transaction->created_at->setTimezone('Asia/Ho_Chi_Minh')->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                {{-- Trạng thái cập nhật (nếu có thay đổi) --}}
                                @if ($transaction->created_at != $transaction->updated_at)
                                    <li class="relative pb-6">
                                        <div class="relative flex space-x-3">
                                            <div class="relative">
                                                {{-- Icon Status --}}
                                                <span
                                                    class="h-8 w-8 rounded-full {{ $config['bg'] }} flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                                    <svg class="w-5 h-5 {{ $config['text'] }}" fill="currentColor"
                                                        viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-900 dark:text-gray-100 font-medium">Cập
                                                        nhật trạng thái</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">Trạng thái:
                                                        **{{ ucfirst($transaction->status) }}**</p>
                                                </div>
                                                <div
                                                    class="text-right text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap pt-1">
                                                    {{ $transaction->updated_at->setTimezone('Asia/Ho_Chi_Minh')->format('H:i d/m') }}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</x-app-layout>
