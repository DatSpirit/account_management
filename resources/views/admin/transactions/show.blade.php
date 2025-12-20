<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                {{-- Nút Quay lại --}}
                <a href="{{ route('admin.transactions.all-transactions') }}"
                    class="group p-2 rounded-full bg-white dark:bg-gray-700 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 shadow-sm border border-gray-200 dark:border-gray-600 transition-all duration-200">
                    <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-2xl text-gray-800 dark:text-white tracking-tight leading-tight">
                        Chi tiết Giao dịch
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 font-mono">#{{ $transaction->order_code }}</p>
                </div>
            </div>

            {{-- Badge Trạng thái --}}
            @php
                $statusConfig = [
                    'success' => [
                        'bg' => 'bg-green-100',
                        'text' => 'text-green-800',
                        'border' => 'border-green-200',
                        'icon' => '✔',
                    ],
                    'pending' => [
                        'bg' => 'bg-yellow-100',
                        'text' => 'text-yellow-800',
                        'border' => 'border-yellow-200',
                        'icon' => '⏳',
                    ],
                    'failed' => [
                        'bg' => 'bg-red-100',
                        'text' => 'text-red-800',
                        'border' => 'border-red-200',
                        'icon' => '✖',
                    ],
                    'cancelled' => [
                        'bg' => 'bg-gray-100',
                        'text' => 'text-gray-800',
                        'border' => 'border-gray-200',
                        'icon' => '🚫',
                    ],
                ];
                $conf = $statusConfig[$transaction->status] ?? $statusConfig['cancelled'];
            @endphp
            <div
                class="flex items-center gap-2 px-4 py-2 rounded-xl border {{ $conf['bg'] }} {{ $conf['border'] }} {{ $conf['text'] }} shadow-sm">
                <span class="font-bold text-lg">{{ $conf['icon'] }}</span>
                <span class="font-bold uppercase tracking-wider text-sm">{{ ucfirst($transaction->status) }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-6xl mx-auto space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 flex items-center gap-4 transition hover:shadow-md">
                    <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tổng Thanh Toán</p>
                        <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-1">
                            {{ number_format($transaction->amount) }}
                            <span class="text-sm font-medium text-gray-500">{{ $transaction->currency }}</span>
                        </p>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 flex items-center gap-4 transition hover:shadow-md">
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Khách Hàng</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white truncate mt-1">
                            {{ $transaction->user->name ?? 'Guest User' }}
                        </p>
                        <p class="text-xs text-gray-500 truncate">{{ $transaction->user->email ?? 'N/A' }}</p>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 flex items-center gap-4 transition hover:shadow-md">
                    <div class="p-3 bg-orange-50 dark:bg-orange-900/30 text-orange-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Thời Gian Tạo</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">
                            {{ $transaction->created_at->format('H:i:s') }}
                        </p>
                        <p class="text-xs text-gray-500">{{ $transaction->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-6">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div
                            class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800">
                            <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                                📦 Sản Phẩm & Dịch Vụ
                            </h3>
                            <span
                                class="px-3 py-1 rounded-full text-xs font-bold bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                {{ ucfirst($transaction->product->product_type ?? 'Unknown') }}
                            </span>
                        </div>

                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 mb-8">
                                <div
                                    class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
                                    @if (isset($transaction->product->image))
                                        <img src="{{ $transaction->product->image }}"
                                            class="w-full h-full object-cover rounded-2xl">
                                    @else
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 dark:text-white leading-tight">
                                        {{ $transaction->product->name ?? 'Sản phẩm đã bị xóa' }}
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 leading-relaxed">
                                        {{ $transaction->description }}
                                    </p>
                                </div>
                            </div>
                            {{-- METADATA HIỂN THỊ THEO LOẠI GIAO DỊCH --}}
                            <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
                                @php
                                    $meta = $transaction->response_data ?? [];
                                    $type = $meta['type'] ?? null;
                                    $suffix = substr($transaction->description, -1); // Lấy 1 ký tự cuối (K, C, EX)
                                @endphp

                                {{-- === LOGIC HIỂN THỊ CHI TIẾT THEO TYPE === --}}

                                {{-- 1️⃣ GIA DỊCH NẠP COINKEY --}}
                                @if ($suffix === 'C' || $type === 'coinkey_deposit')
                                    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4">
                                        <div class="flex items-center gap-2 mb-3">
                                            <span class="text-2xl">💰</span>
                                            <h3 class="font-bold text-emerald-700">Nạp Coinkey</h3>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3 text-sm">
                                            <div>
                                                <span class="text-gray-600">Số tiền:</span>
                                                <span class="font-bold text-purple-600">
                                                    {{ number_format($meta['amount'] ?? 0) }} VND
                                                </span>
                                            </div>
                                            @if (isset($meta['currency']))
                                                <div>
                                                    <span class="text-gray-600">Phương thức:</span>
                                                    <span
                                                        class="font-bold text-green-600">
                                                        {{ $meta['currency'] === 'wallet' ? '💳 Ví ' : '💵 Chuyển khoản' }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                        {{-- 2️⃣ MUA KEY MỚI (Hệ thống tự tạo Key Code) --}}
                                    @elseif (in_array($suffix, ['K']) && $type == 'package_purchase')
                                        @if ($transaction->productKey)
                                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                                <div class="flex items-center gap-2 mb-3">
                                                    <span class="text-2xl">🔑</span>
                                                    <h3 class="font-bold text-blue-700">Tạo Key mới</h3>
                                                </div>
                                                <div class="grid grid-cols-2 gap-3 text-sm">
                                                    <div>
                                                        <span class="text-gray-600">ID Key:</span>
                                                        <a href="{{ route('admin.keys.show', $transaction->productKey->id) }}"
                                                            class="font-bold text-blue-600 hover:underline">
                                                            #{{ $transaction->productKey->id }}
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-600">Key Code:</span>
                                                        <code class="bg-gray-100 px-2 py-1 rounded font-mono text-xs">
                                                            {{ $transaction->productKey->key_code }}
                                                        </code>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-600">Thời hạn:</span>
                                                        <span class="font-semibold text-purple-600">
                                                            {{ number_format($meta['duration_minutes'] ?? $transaction->productKey->duration_minutes) }}
                                                            phút
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-600">Hết hạn:</span>
                                                        <span class="font-semibold text-red-600">
                                                            {{ $transaction->productKey->expires_at ? $transaction->productKey->expires_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') : 'Vĩnh viễn' }}
                                                        </span>
                                                    </div>
                                                    @if (isset($meta['payment_method']))
                                                        <div>
                                                            <span class="text-gray-600">Phương thức:</span>
                                                            <span
                                                                class="font-bold text-purple-600">
                                                                {{ $meta['payment_method'] === 'wallet' ? '💳 Ví Coinkey' : '💵 Chuyển khoản' }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                    @if (isset($meta['currency']))
                                                        <div>
                                                            <span class="text-gray-600">Phương thức:</span>
                                                            <span
                                                                class="font-bold text-green-600">
                                                                {{ $meta['currency'] === 'VND' ? '💵 Chuyển khoản' : '💳 Ví Coinkey' }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                                <p class="text-yellow-700 text-sm">⚠️ Key chưa được tạo hoặc đã bị xóa
                                                </p>
                                            </div>
                                        @endif

                                        {{-- 3️⃣ TẠO CUSTOM KEY (Người dùng tự đặt Key Code) --}}
                                    @elseif ($type === 'custom_key_purchase')
                                        @php
                                            $keyId = $meta['key_id'] ?? null;
                                            $key = $keyId ? \App\Models\ProductKey::find($keyId) : null;
                                        @endphp

                                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                            <div class="flex items-center gap-2 mb-3">
                                                <span class="text-2xl">🎨</span>
                                                <h3 class="font-bold text-purple-700">Tạo Custom Key</h3>
                                            </div>
                                            <div class="grid grid-cols-2 gap-3 text-sm">
                                                @if ($key)
                                                    <div>
                                                        <span class="text-gray-600">ID Key:</span>
                                                        <a href="{{ route('admin.keys.show', $key->id) }}"
                                                            class="font-bold text-purple-600 hover:underline">
                                                            #{{ $key->id }}
                                                        </a>
                                                    </div>
                                                @endif
                                                <div>
                                                    <span class="text-gray-600">Key Code (Custom):</span>
                                                    <code
                                                        class="bg-purple-100 px-2 py-1 rounded font-mono text-xs font-bold">
                                                        {{ $meta['key_code'] ?? 'N/A' }}
                                                    </code>
                                                </div>
                                                <div>
                                                    <span class="text-gray-600">Thời hạn:</span>
                                                    <span class="font-semibold text-purple-600">
                                                        {{ number_format($meta['duration_minutes'] ?? 0) }} phút
                                                    </span>
                                                </div>
                                                @if (isset($meta['product_name']))
                                                    <div>
                                                        <span class="text-gray-600">Gói sản phẩm:</span>
                                                        <span class="font-semibold">{{ $meta['product_name'] }}</span>
                                                    </div>
                                                @endif
                                                @if (isset($meta['payment_method']))
                                                    <div>
                                                        <span class="text-gray-600">Phương thức:</span>
                                                        <span
                                                            class="font-bold {{ $meta['payment_method'] === 'wallet' ? 'text-purple-600' : 'text-green-600' }}">
                                                            {{ $meta['payment_method'] === 'wallet' ? '💳 Ví Coinkey' : '💵 Chuyển khoản' }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if (isset($meta['cost_coinkey']))
                                                    <div>
                                                        <span class="text-gray-600">Chi phí:</span>
                                                        <span class="font-bold text-purple-600">
                                                            {{ number_format($meta['cost_coinkey']) }} Coin
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- 4️⃣ GIA HẠN KEY --}}
                                    @elseif ($suffix === 'X' || $type === 'key_extension')
                                        @php
                                            $keyId = $meta['key_id'] ?? null;
                                            $key = $keyId ? \App\Models\ProductKey::find($keyId) : null;
                                        @endphp

                                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                            <div class="flex items-center gap-2 mb-3">
                                                <span class="text-2xl">⏱️</span>
                                                <h3 class="font-bold text-orange-700">Gia hạn Key</h3>
                                            </div>
                                            <div class="grid grid-cols-2 gap-3 text-sm">
                                                @if ($key)
                                                    <div>
                                                        <span class="text-gray-600">ID Key:</span>
                                                        <a href="{{ route('admin.keys.show', $key->id) }}"
                                                            class="font-bold text-orange-600 hover:underline">
                                                            #{{ $key->id }}
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-600">Key Code:</span>
                                                        <code class="bg-gray-100 px-2 py-1 rounded font-mono text-xs">
                                                            {{ $key->key_code }}
                                                        </code>
                                                    </div>
                                                @elseif (isset($meta['key_code']))
                                                    <div>
                                                        <span class="text-gray-600">Key Code:</span>
                                                        <code class="bg-gray-100 px-2 py-1 rounded font-mono text-xs">
                                                            {{ $meta['key_code'] }}
                                                        </code>
                                                    </div>
                                                @endif
                                                <div>
                                                    <span class="text-gray-600">Thời gian cộng:</span>
                                                    <span class="font-bold text-green-600">
                                                        +{{ number_format($meta['duration_minutes'] ?? 0) }} phút
                                                    </span>
                                                </div>
                                                @if (isset($meta['payment_method']))
                                                    <div>
                                                        <span class="text-gray-600">Phương thức:</span>
                                                        <span
                                                            class="font-bold {{ $meta['payment_method'] === 'wallet' ? 'text-purple-600' : 'text-green-600' }}">
                                                            {{ $meta['payment_method'] === 'wallet' ? '💳 Ví Coinkey' : '💵 Chuyển khoản' }}
                                                        </span>
                                                    </div>
                                                @endif
                                                @if (isset($meta['cost_coinkey']))
                                                    <div>
                                                        <span class="text-gray-600">Chi phí:</span>
                                                        <span class="font-bold text-orange-600">
                                                            {{ number_format($meta['cost_coinkey']) }} Coin
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h4 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Thông Tin Kỹ Thuật
                        </h4>
                        <ul class="space-y-4 text-sm">
                            <li
                                class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700 last:border-0 last:pb-0">
                                <span class="text-gray-500 dark:text-gray-400">Mã Đơn (Order Code)</span>
                                <span
                                    class="font-mono font-bold bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-800 dark:text-gray-200">{{ $transaction->order_code }}</span>
                            </li>
                            <li
                                class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700 last:border-0 last:pb-0">
                                <span class="text-gray-500 dark:text-gray-400">Transaction ID</span>
                                <span
                                    class="font-mono text-gray-700 dark:text-gray-300">#{{ $transaction->id }}</span>
                            </li>
                            <li
                                class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700 last:border-0 last:pb-0">
                                <span class="text-gray-500 dark:text-gray-400">Created At</span>
                                <span
                                    class="text-gray-700 dark:text-gray-300">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
                            </li>
                            <li
                                class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700 last:border-0 last:pb-0">
                                <span class="text-gray-500 dark:text-gray-400">Last Updated</span>
                                <span
                                    class="text-gray-700 dark:text-gray-300">{{ $transaction->updated_at->format('d/m/Y H:i') }}</span>
                            </li>
                        </ul>
                    </div>

                    @if (!empty($transaction->response_data))
                        <div class="bg-gray-900 rounded-2xl shadow-lg border border-gray-700 overflow-hidden">
                            <div
                                class="bg-gray-800 px-4 py-3 border-b border-gray-700 flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-gray-300 text-xs uppercase tracking-wider">Metadata
                                        (JSON)</h4>
                                </div>
                                <button onclick="copyMetadata()"
                                    class="group flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-lg transition-all active:scale-95 shadow-lg shadow-indigo-500/20">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                    </svg>
                                    <span>Copy JSON</span>
                                </button>
                            </div>
                            <div class="p-0">
                                <pre
                                    class="text-xs text-green-400 font-mono overflow-x-auto whitespace-pre-wrap break-all p-4 max-h-80 custom-scrollbar">{{ json_encode($transaction->response_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                            <script>
                                function copyMetadata() {
                                    const data = @json($transaction->response_data);
                                    const jsonString = JSON.stringify(data, null, 2);
                                    navigator.clipboard.writeText(jsonString).then(() => {
                                        const btn = document.querySelector('button[onclick="copyMetadata()"]');
                                        const originalContent = btn.innerHTML;
                                        btn.classList.replace('bg-indigo-600', 'bg-green-600');
                                        btn.classList.replace('hover:bg-indigo-500', 'hover:bg-green-500');
                                        btn.innerHTML =
                                            '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> <span>Copied!</span>';
                                        setTimeout(() => {
                                            btn.innerHTML = originalContent;
                                            btn.classList.replace('bg-green-600', 'bg-indigo-600');
                                            btn.classList.replace('hover:bg-green-500', 'hover:bg-indigo-500');
                                        }, 2000);
                                    }).catch(err => alert('Lỗi copy!'));
                                }
                            </script>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #1f2937;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
    </style>
</x-app-layout>
