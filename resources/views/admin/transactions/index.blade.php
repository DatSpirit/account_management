<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-3">
            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11V7a4 4 0 00-8 0v4m8 0h2a2 2 0 012 2v6a2 2 0 01-2 2h-6a2 2 0 01-2-2v-6a2 2 0 012-2h2z"/>
            </svg>
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200">Transaction History</h2>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-6xl mx-auto">
            
            <!-- Bộ lọc -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 mb-6 border border-gray-200 dark:border-gray-700">
                <form method="GET" class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center space-x-2">
                        <label for="status" class="text-gray-700 dark:text-gray-300 font-semibold">Filter by status:</label>
                        <select id="status" name="status"
                                class="px-3 py-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-gray-100"
                                onchange="this.form.submit()">
                            <option value="">All</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="success" {{ $status === 'success' ? 'selected' : '' }}>Success</option>
                            <option value="failed" {{ $status === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                </form>
            </div>

            <!-- Bảng giao dịch -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-indigo-600 dark:bg-indigo-700 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold">#</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">User</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Product</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Amount</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Status</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Order Code</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($transactions as $transaction)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $transaction->id }}</td>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $transaction->user?->name ?? 'Guest' }}</td>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100">{{ $transaction->product?->name ?? 'Deleted Product' }}</td>
                                <td class="px-4 py-3 font-semibold text-indigo-600 dark:text-indigo-400">{{ number_format($transaction->amount, 0, ',', '.') }}₫</td>
                                <td class="px-4 py-3">
                                    @if ($transaction->status === 'success')
                                        <span class="px-3 py-1 text-xs font-bold bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-200 rounded-full">Success</span>
                                    @elseif ($transaction->status === 'failed')
                                        <span class="px-3 py-1 text-xs font-bold bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-200 rounded-full">Failed</span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-200 rounded-full">Pending</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ $transaction->order_code }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400 text-sm">{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-gray-500 dark:text-gray-400">
                                    No transactions found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
