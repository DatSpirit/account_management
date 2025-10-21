<x-app-layout>
    <div id="toast-container" class="fixed top-4 right-4 z-[100] space-y-3"></div>

    <x-slot name="header">
        <div class="flex items-center justify-center space-x-4">
            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2-3 .895-3 2 1.343 2 3 2m-3-2h6m-9-2h9.5m-11 0a2 2 0 012-2h10a2 2 0 012 2v6a2 2 0 01-2 2h-10a2 2 0 01-2-2v-6z"/>
            </svg>
            <h2 class="font-extrabold text-2xl text-gray-800 dark:text-gray-100 tracking-tight">
                History Transaction
            </h2>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto space-y-6">

            <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 dark:from-indigo-700 dark:via-purple-700 dark:to-pink-700 rounded-2xl shadow-xl p-6 sm:p-8 text-white">
                <h3 class="text-2xl font-bold text-white mb-4">Tổng Quan Giao Dịch</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                    
                    <div class="text-center bg-white/10 p-3 rounded-xl shadow-lg">
                        <div class="text-3xl font-bold text-white">{{ number_format($totalTransactions) }}</div>
                        <div class="text-xs text-indigo-100 dark:text-indigo-200 uppercase tracking-wide mt-1">Tổng Giao Dịch</div>
                    </div>
                    
                    <div class="text-center bg-white/10 p-3 rounded-xl shadow-lg">
                        <div class="text-3xl font-bold text-green-300">{{ number_format($totalSuccess) }}</div>
                        <div class="text-xs text-indigo-100 dark:text-indigo-200 uppercase tracking-wide mt-1">Thành Công</div>
                    </div>
                    
                    <div class="text-center bg-white/10 p-3 rounded-xl shadow-lg">
                        <div class="text-3xl font-bold text-yellow-300">{{ number_format($totalPending) }}</div>
                        <div class="text-xs text-indigo-100 dark:text-indigo-200 uppercase tracking-wide mt-1">Đang Chờ</div>
                    </div>
                    
                    <div class="text-center bg-white/10 p-3 rounded-xl shadow-lg">
                        <div class="text-3xl font-bold text-red-300">{{ number_format($totalFailed) }}</div>
                        <div class="text-xs text-indigo-100 dark:text-indigo-200 uppercase tracking-wide mt-1">Thất Bại</div>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl">
                <form method="GET" action="{{ route('admin.users') }}" class="space-y-4">
                    {{-- Thay đổi: items-end để căn chỉnh khi có label. Thay đổi bố cục thành flex-row trên lg --}}
                    <div class="flex flex-col lg:flex-row gap-4 items-end">
                        
                        <div class="relative flex-shrink-0 w-full lg:w-40">
                            <label for="filter-select" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                                Lọc Theo
                            </label>
                            <select name="filter" id="filter-select"
                                class="w-full px-4 py-3 appearance-none bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 
                                       rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100
                                       focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 
                                       transition-all duration-200 cursor-pointer hover:border-indigo-400">
                                <option value="name" {{ request('filter') === 'name' ? 'selected' : '' }}>👤 Tên</option>
                                <option value="email" {{ request('filter') === 'email' ? 'selected' : '' }}>📧 Email</option>
                                <option value="id" {{ request('filter') === 'id' ? 'selected' : '' }}>🔢 ID</option>
                            </select>
                            {{-- Icon cho dropdown --}}
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 dark:text-gray-500 mt-8">
                                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>

                        <div class="flex-1">
                            <label for="search-input" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                                Tìm Kiếm
                            </label>
                            <div class="relative">
                                {{-- Màu nền sáng đã được giữ là 'bg-white' và viền 'border-gray-300' để trông rõ ràng hơn --}}
                                <input type="text" name="search" id="search-input" 
                                       value="{{ request('search') }}"
                                       placeholder="Nhập từ khóa tìm kiếm..."
                                       class="w-full pl-4 pr-12 py-3 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 
                                              rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500
                                              focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 
                                              transition-all duration-200">
                                
                                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </button>
                                
                                <ul id="suggestions" class="absolute z-50 mt-2 w-full bg-white dark:bg-gray-800 
                                    border-2 border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl hidden overflow-hidden">
                                </ul>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-indigo-700 dark:bg-indigo-900 text-white shadow-md">
                                <th class="px-6 py-4 text-left text-xs font-extrabold uppercase tracking-wider rounded-tl-2xl">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-extrabold uppercase tracking-wider">Người Dùng</th>
                                <th class="px-6 py-4 text-left text-xs font-extrabold uppercase tracking-wider">Sản Phẩm</th>
                                <th class="px-6 py-4 text-left text-xs font-extrabold uppercase tracking-wider">Số Tiền</th>
                                <th class="px-6 py-4 text-left text-xs font-extrabold uppercase tracking-wider">Trạng Thái</th>
                                <th class="px-6 py-4 text-left text-xs font-extrabold uppercase tracking-wider">Mã Đơn</th>
                                <th class="px-6 py-4 text-left text-xs font-extrabold uppercase tracking-wider rounded-tr-2xl">Thời Gian</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($transactions as $transaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-mono text-xs">
                                            #{{ $transaction->id }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 hover:underline transition">
                                            {{ $transaction->user?->name ?? 'Khách (Guest)' }}
                                        </a>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $transaction->user?->email }}</p>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300">
                                        {{ $transaction->product?->name ?? 'Sản phẩm đã xóa' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-extrabold text-md text-indigo-700 dark:text-indigo-300">
                                        {{ number_format($transaction->amount, 0, ',', '.') }}₫
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($transaction->status === 'success')
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-bold leading-none text-green-800 bg-green-100 dark:bg-green-900/50 dark:text-green-300 rounded-full shadow-sm">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                Thành công
                                            </span>
                                        @elseif ($transaction->status === 'failed')
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-bold leading-none text-red-800 bg-red-100 dark:bg-red-900/50 dark:text-red-300 rounded-full shadow-sm">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                                                Thất bại
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 text-xs font-bold leading-none text-yellow-800 bg-yellow-100 dark:bg-yellow-900/50 dark:text-yellow-300 rounded-full shadow-sm">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a8 8 0 100 16 8 8 0 000-16zm-1 9V7a1 1 0 112 0v4a1 1 0 11-2 0zm0 4a1 1 0 112 0 1 1 0 01-2 0z"/></svg>
                                                Đang chờ
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-500 dark:text-gray-400">
                                        {{ $transaction->order_code }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $transaction->created_at->format('d/m/Y H:i') }}
                                        <p class="text-xs text-gray-400 dark:text-gray-500">{{ $transaction->created_at->diffForHumans() }}</p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center space-y-4">
                                            <div class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2m-9 0V3h4v2m-4 0h4"></path></svg>
                                            </div>
                                            <div class="space-y-2">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                    Không tìm thấy giao dịch nào
                                                </h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Hãy thử điều chỉnh bộ lọc hoặc từ khóa tìm kiếm.
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transactions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        {{ $transactions->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
            </div>
    </div>

    <script>
        // Toast Notification System (Lấy từ users.blade.php)
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            const icons = {
                success: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
                error: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>'
            };
            
            const colors = {
                success: 'bg-green-50 dark:bg-green-900/50 text-green-800 dark:text-green-200 border-green-200 dark:border-green-700',
                error: 'bg-red-50 dark:bg-red-900/50 text-red-800 dark:text-red-200 border-red-200 dark:border-red-700'
            };
            
            toast.className = `flex items-center space-x-3 px-6 py-4 rounded-xl shadow-2xl border-2 ${colors[type]} 
                              transform transition-all duration-300 translate-x-full opacity-0`;
            toast.innerHTML = `
                <div class="flex-shrink-0">${icons[type]}</div>
                <p class="font-medium text-sm">${message}</p>
                <button onclick="this.parentElement.remove()" class="ml-4 hover:opacity-70 transition-opacity">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            `;
            
            container.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 100);
            
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Show flash messages as toasts
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
    </script>
</x-app-layout>