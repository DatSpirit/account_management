
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-8 h-8 mr-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    My Transactions
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Quản lý tất cả giao dịch của bạn</p>
            </div>
            <button onclick="window.location.reload()" class="hidden sm:flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh
            </button>
        </div>
    </x-slot>

    <div class="space-y-6">
        
        {{-- Quick Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm opacity-90 uppercase tracking-wider mb-2">Success</p>
                <p class="text-3xl font-bold">{{ $stats['success'] ?? 0 }}</p>
                <p class="text-xs opacity-75 mt-2">{{ number_format($stats['success_amount'] ?? 0) }} VND</p>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm opacity-90 uppercase tracking-wider mb-2">Pending</p>
                <p class="text-3xl font-bold">{{ $stats['pending'] ?? 0 }}</p>
                <p class="text-xs opacity-75 mt-2">{{ number_format($stats['pending_amount'] ?? 0) }} VND</p>
            </div>

            <div class="bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm opacity-90 uppercase tracking-wider mb-2">Failed</p>
                <p class="text-3xl font-bold">{{ $stats['failed'] ?? 0 }}</p>
                <p class="text-xs opacity-75 mt-2">Cancelled & Failed</p>
            </div>

            <div class="bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-white/20 backdrop-blur-sm rounded-xl">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm opacity-90 uppercase tracking-wider mb-2">Total Spent</p>
                <p class="text-3xl font-bold">{{ number_format($stats['total_amount'] ?? 0) }}</p>
                <p class="text-xs opacity-75 mt-2">VND</p>
            </div>
        </div>

        {{-- Filters --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <form method="GET" action="{{ route('transactions.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    
                    {{-- Status Filter --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                            Status
                        </label>
                        <select name="status" class="w-full px-4 py-2.5 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all">
                            <option value="">All Status</option>
                            <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>✅ Success</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>❌ Failed</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>⊘ Cancelled</option>
                        </select>
                    </div>

                    {{-- Date From --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                            From Date
                        </label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2.5 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all">
                    </div>

                    {{-- Date To --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                            To Date
                        </label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-4 py-2.5 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all">
                    </div>

                    {{-- Search --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                            Search
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Order code..." class="w-full px-4 py-2.5 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all">
                    </div>

                    {{-- Buttons --}}
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition-all shadow-lg shadow-indigo-500/50">
                            <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search
                        </button>
                        <a href="{{ route('transactions.index') }}" class="px-4 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-all">
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Transactions Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-700 border-b-2 border-gray-200 dark:border-gray-600">
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider hidden md:table-cell">Product</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">Date</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($transactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">#{{ $transaction->order_code }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 lg:hidden">
                                            {{ $transaction->created_at->format('d/m/Y H:i') }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 hidden md:table-cell">
                                    @if($transaction->product)
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ Str::limit($transaction->product->name, 30) }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Str::limit($transaction->description, 40) }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400 italic">No product</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-right">
                                    <div class="flex flex-col items-end">
                                        <span class="text-base font-bold text-gray-900 dark:text-white">
                                            {{ number_format($transaction->amount, 0, ',', '.') }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">VND</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $statusConfig = [
                                            'success' => ['bg' => 'bg-green-100 dark:bg-green-900/50', 'text' => 'text-green-800 dark:text-green-300', 'label' => 'Success', 'icon' => '✓'],
                                            'pending' => ['bg' => 'bg-yellow-100 dark:bg-yellow-900/50', 'text' => 'text-yellow-800 dark:text-yellow-300', 'label' => 'Pending', 'icon' => '⏱'],
                                            'failed' => ['bg' => 'bg-gray-100 dark:bg-gray-700', 'text' => 'text-gray-800 dark:text-gray-300', 'label' => 'Failed', 'icon' => '✗'],
                                            'cancelled' => ['bg' => 'bg-red-100 dark:bg-red-900/50', 'text' => 'text-red-800 dark:text-red-300', 'label' => 'Cancelled', 'icon' => '⊘'],
                                        ];
                                        $config = $statusConfig[$transaction->status] ?? $statusConfig['pending'];
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $config['bg'] }} {{ $config['text'] }}">
                                        <span class="mr-1">{{ $config['icon'] }}</span>
                                        {{ $config['label'] }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 hidden lg:table-cell">
                                    <div class="flex flex-col text-sm">
                                        <span class="text-gray-900 dark:text-gray-100 font-medium">{{ $transaction->created_at->format('d/m/Y') }}</span>
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">{{ $transaction->created_at->format('H:i:s') }}</span>
                                        <span class="text-gray-400 dark:text-gray-500 text-xs">{{ $transaction->created_at->diffForHumans() }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    <button onclick="viewTransaction({{ $transaction->id }})" class="p-2 rounded-lg bg-indigo-500 hover:bg-indigo-600 text-white transition-all transform hover:scale-110">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">No transactions found</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Start shopping to see your transactions here</p>
                                        </div>
                                        <a href="{{ route('products') }}" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium transition-all">
                                            Browse Products
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($transactions->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:dark:bg-gray-800">
                    {{ $transactions->appends(request()->query())->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>

    </div>

    @push('scripts')
    <script>
        function viewTransaction(id) {
            // Implement view transaction detail modal or redirect
            alert('View transaction #' + id);
            // window.location.href = `/transactions/${id}`;
        }
    </script>
    @endpush
</x-app-layout>