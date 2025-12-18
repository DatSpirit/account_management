<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                {{-- Nút Quay lại --}}
                <a href="{{ route('admin.transactions.all-transactions') }}"
                    class="group p-2 rounded-full bg-white dark:bg-gray-700 text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 shadow-sm border border-gray-200 dark:border-gray-600 transition-all duration-200">
                    <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
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
                    'success' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-200', 'icon' => '✔'],
                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-200', 'icon' => '⏳'],
                    'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-200', 'icon' => '✖'],
                    'cancelled' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-200', 'icon' => '🚫'],
                ];
                $conf = $statusConfig[$transaction->status] ?? $statusConfig['cancelled'];
            @endphp
            <div class="flex items-center gap-2 px-4 py-2 rounded-xl border {{ $conf['bg'] }} {{ $conf['border'] }} {{ $conf['text'] }} shadow-sm">
                <span class="font-bold text-lg">{{ $conf['icon'] }}</span>
                <span class="font-bold uppercase tracking-wider text-sm">{{ ucfirst($transaction->status) }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-6xl mx-auto space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 flex items-center gap-4 transition hover:shadow-md">
                    <div class="p-3 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Tổng Thanh Toán</p>
                        <p class="text-2xl font-extrabold text-gray-900 dark:text-white mt-1">
                            {{ number_format($transaction->amount) }} 
                            <span class="text-sm font-medium text-gray-500">{{ $transaction->currency }}</span>
                        </p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 flex items-center gap-4 transition hover:shadow-md">
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/30 text-blue-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Khách Hàng</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white truncate mt-1">
                            {{ $transaction->user->name ?? 'Guest User' }}
                        </p>
                        <p class="text-xs text-gray-500 truncate">{{ $transaction->user->email ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 flex items-center gap-4 transition hover:shadow-md">
                    <div class="p-3 bg-orange-50 dark:bg-orange-900/30 text-orange-600 rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
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
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800">
                            <h3 class="font-bold text-lg text-gray-800 dark:text-white flex items-center gap-2">
                                📦 Sản Phẩm & Dịch Vụ
                            </h3>
                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                {{ ucfirst($transaction->product->product_type ?? 'Unknown') }}
                            </span>
                        </div>
                        
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 mb-8">
                                <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-inner">
                                    @if(isset($transaction->product->image))
                                        <img src="{{ $transaction->product->image }}" class="w-full h-full object-cover rounded-2xl">
                                    @else
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
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

                            <div class="border-t border-gray-100 dark:border-gray-700 pt-6">
                                @php
                                    $meta = $transaction->response_data ?? [];
                                    $type = $meta['type'] ?? '';
                                    $pType = $transaction->product->product_type ?? '';
                                @endphp

                                {{-- === LOGIC HIỂN THỊ CHI TIẾT THEO TYPE === --}}

                                {{-- CASE 1: GIA HẠN KEY --}}
                                @if ($type === 'key_extension')
                                    <div class="bg-green-50 dark:bg-green-900/10 rounded-xl p-5 border border-green-100 dark:border-green-800/30">
                                        <div class="flex items-center gap-2 mb-4">
                                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                            <h5 class="font-bold text-green-800 dark:text-green-400 uppercase text-xs">Chi tiết Gia Hạn</h5>
                                        </div>
                                        <div class="grid grid-cols-2 gap-y-4 gap-x-8 text-sm">
                                            <div>
                                                <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">Key ID</p>
                                                <p class="font-mono font-bold text-gray-900 dark:text-white">#{{ $meta['key_id'] ?? 'N/A' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">Thời gian cộng thêm</p>
                                                <p class="font-bold text-green-600 text-lg">+{{ number_format($meta['duration_minutes'] ?? 0) }} <span class="text-sm font-normal text-gray-500">phút</span></p>
                                            </div>
                                            <div class="col-span-2">
                                                <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">Mã Key</p>
                                                <div class="mt-1 flex items-center justify-between bg-white dark:bg-gray-800 p-2 rounded border border-gray-200 dark:border-gray-600">
                                                    <code class="text-indigo-600 dark:text-indigo-400 font-bold">{{ $meta['key_code'] ?? '---' }}</code>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                {{-- CASE 2: MUA CUSTOM KEY --}}
                                @elseif ($type === 'custom_key_purchase')
                                    <div class="bg-purple-50 dark:bg-purple-900/10 rounded-xl p-5 border border-purple-100 dark:border-purple-800/30">
                                        <div class="flex items-center gap-2 mb-4">
                                            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                                            <h5 class="font-bold text-purple-800 dark:text-purple-400 uppercase text-xs">Chi tiết Custom Key</h5>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div class="col-span-2">
                                                <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">Mã Key (User đặt)</p>
                                                <p class="font-mono font-bold text-2xl text-purple-600 tracking-wider mt-1">{{ $meta['key_code'] ?? '---' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 dark:text-gray-400 text-xs uppercase">Thời hạn</p>
                                                <p class="font-bold text-gray-900 dark:text-white">{{ number_format($meta['duration_minutes'] ?? 0) }} phút</p>
                                            </div>
                                        </div>
                                    </div>

                                {{-- CASE 3: NẠP COIN --}}
                                @elseif ($pType === 'coinkey')
                                    <div class="bg-yellow-50 dark:bg-yellow-900/10 rounded-xl p-5 border border-yellow-100 dark:border-yellow-800/30 text-center">
                                        <p class="text-gray-500 dark:text-gray-400 text-xs uppercase mb-2">Số lượng Coin nạp</p>
                                        <p class="text-4xl font-extrabold text-yellow-500 drop-shadow-sm">
                                            {{ number_format($transaction->product->coinkey_amount ?? 0) }} <span class="text-lg text-yellow-600">🪙</span>
                                        </p>
                                    </div>

                                {{-- CASE 4: MUA KEY THƯỜNG --}}
                                @elseif ($pType === 'package')
                                    <div class="bg-blue-50 dark:bg-blue-900/10 rounded-xl p-5 border border-blue-100 dark:border-blue-800/30">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                                                <h5 class="font-bold text-blue-800 dark:text-blue-400 uppercase text-xs">Key được tạo</h5>
                                            </div>
                                            @if($transaction->productKey)
                                            <a href="{{ route('admin.keys.show', $transaction->productKey->id) }}" class="text-xs text-blue-600 hover:underline flex items-center">
                                                Quản lý Key <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                            @endif
                                        </div>
                                        @if($transaction->productKey)
                                            <div class="bg-white dark:bg-gray-800 p-3 rounded-lg border border-gray-200 dark:border-gray-600 flex justify-between items-center">
                                                <span class="font-mono font-bold text-lg text-gray-800 dark:text-white">{{ $transaction->productKey->key_code }}</span>
                                                <span class="text-xs font-mono text-gray-400">ID: {{ $transaction->productKey->id }}</span>
                                            </div>
                                        @else
                                            <div class="text-center py-2 text-red-500 text-sm italic bg-red-50 rounded-lg">
                                                Chưa tìm thấy Key (Có thể đang xử lý hoặc lỗi)
                                            </div>
                                        @endif
                                    </div>

                                @else
                                    <p class="text-center text-gray-500 italic">Giao dịch thông thường, không có dữ liệu mở rộng.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <h4 class="font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Thông Tin Kỹ Thuật
                        </h4>
                        <ul class="space-y-4 text-sm">
                            <li class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700 last:border-0 last:pb-0">
                                <span class="text-gray-500 dark:text-gray-400">Mã Đơn (Order Code)</span>
                                <span class="font-mono font-bold bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-gray-800 dark:text-gray-200">{{ $transaction->order_code }}</span>
                            </li>
                            <li class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700 last:border-0 last:pb-0">
                                <span class="text-gray-500 dark:text-gray-400">Transaction ID</span>
                                <span class="font-mono text-gray-700 dark:text-gray-300">#{{ $transaction->id }}</span>
                            </li>
                            <li class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700 last:border-0 last:pb-0">
                                <span class="text-gray-500 dark:text-gray-400">Created At</span>
                                <span class="text-gray-700 dark:text-gray-300">{{ $transaction->created_at->format('d/m/Y H:i') }}</span>
                            </li>
                            <li class="flex justify-between items-center pb-3 border-b border-gray-100 dark:border-gray-700 last:border-0 last:pb-0">
                                <span class="text-gray-500 dark:text-gray-400">Last Updated</span>
                                <span class="text-gray-700 dark:text-gray-300">{{ $transaction->updated_at->format('d/m/Y H:i') }}</span>
                            </li>
                        </ul>
                    </div>

                    @if(!empty($transaction->response_data))
                    <div class="bg-gray-900 rounded-2xl shadow-lg border border-gray-700 overflow-hidden">
                        <div class="bg-gray-800 px-4 py-3 border-b border-gray-700 flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <h4 class="font-bold text-gray-300 text-xs uppercase tracking-wider">Metadata (JSON)</h4>
                            </div>
                            <button onclick="copyMetadata()" class="group flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold rounded-lg transition-all active:scale-95 shadow-lg shadow-indigo-500/20">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" /></svg>
                                <span>Copy JSON</span>
                            </button>
                        </div>
                        <div class="p-0">
                            <pre class="text-xs text-green-400 font-mono overflow-x-auto whitespace-pre-wrap break-all p-4 max-h-80 custom-scrollbar">{{ json_encode($transaction->response_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
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
                                    btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> <span>Copied!</span>';
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
        .custom-scrollbar::-webkit-scrollbar { height: 8px; width: 8px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #1f2937; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #4b5563; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6b7280; }
    </style>
</x-app-layout>