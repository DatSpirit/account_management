<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-8 h-8 mr-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Analytics Dashboard
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Phân tích chi tiết hoạt động tài khoản của bạn
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('analytics.export') }}"
                    class="flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium transition-all shadow-lg shadow-green-500/30">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Export Data
                </a>
                <select id="time-range" onchange="filterByTimeRange(this.value)"
                    class="px-4 py-2 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium focus:ring-2 focus:ring-indigo-500 transition-all">
                    <option value="7" {{ request('days') == 7 ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30" {{ request('days') == 30 || !request('days') ? 'selected' : '' }}>Last 30 days</option>
                    <option value="90" {{ request('days') == 90 ? 'selected' : '' }}>Last 90 days</option>
                    <option value="365" {{ request('days') == 365 ? 'selected' : '' }}>Last year</option>
                </select>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- Overview Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            {{-- Total Revenue --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    @if(isset($analytics['growth_rate']) && $analytics['growth_rate'] > 0)
                    <span
                        class="text-xs font-semibold text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 px-3 py-1 rounded-full">
                        +{{ number_format($analytics['growth_rate'], 1) }}%
                    </span>
                    @endif
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Total Spending</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($analytics['total_spent'] ?? 0) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">VND in selected period</p>
            </div>

            {{-- Avg Transaction --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    @if(isset($analytics['avg_growth']) && $analytics['avg_growth'] > 0)
                    <span
                        class="text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 px-3 py-1 rounded-full">
                        +{{ number_format($analytics['avg_growth'], 1) }}%
                    </span>
                    @endif
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Avg Transaction</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ number_format($analytics['avg_transaction'] ?? 0) }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">VND per order</p>
            </div>

            {{-- Success Rate --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="p-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <span
                        class="text-xs font-semibold text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-900/30 px-3 py-1 rounded-full">
                        {{ ($analytics['success_rate'] ?? 0) >= 80 ? 'Excellent' : 'Good' }}
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Success Rate</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($analytics['success_rate'] ?? 0, 1) }}%</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Of all transactions</p>
            </div>

            {{-- Total Orders --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div
                        class="p-3 bg-gradient-to-br from-orange-500 to-red-600 rounded-xl group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    @if(isset($analytics['orders_change']) && $analytics['orders_change'] > 0)
                    <span
                        class="text-xs font-semibold text-orange-600 dark:text-orange-400 bg-orange-100 dark:bg-orange-900/30 px-3 py-1 rounded-full">
                        +{{ $analytics['orders_change'] }}
                    </span>
                    @endif
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Total Orders</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $analytics['total_orders'] ?? 0 }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">In selected period</p>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Spending Trend --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                        Spending Trend
                    </h3>
                    <span
                        class="text-xs bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 px-3 py-1 rounded-full font-semibold">
                        {{ request('days', 30) }} Days
                    </span>
                </div>
                <div class="h-80">
                    <canvas id="spendingTrendChart"></canvas>
                </div>
            </div>

            {{-- Category Distribution --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-6 h-6 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                        </svg>
                        Category Distribution
                    </h3>
                </div>
                <div class="h-80">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Detailed Stats --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Top Products --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    Top Products
                </h3>
                <div class="space-y-4">
                    @if(isset($analytics['top_products']) && count($analytics['top_products']) > 0)
                        @foreach($analytics['top_products'] as $index => $product)
                            <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="flex items-center space-x-3 flex-1">
                                    <span class="text-lg font-bold text-gray-400 dark:text-gray-500">#{{ $index + 1 }}</span>
                                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $product->orders_count }} orders</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-green-600 dark:text-green-400">
                                    {{ number_format($product->total_amount) }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="flex items-center space-x-3 flex-1">
                                    <span class="text-lg font-bold text-gray-400 dark:text-gray-500">#{{ $i }}</span>
                                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">Product {{ $i }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ rand(10, 50) }} orders</p>
                                    </div>
                                </div>
                                <span class="text-sm font-bold text-green-600 dark:text-green-400">
                                    {{ number_format(rand(100000, 500000)) }}
                                </span>
                            </div>
                        @endfor
                    @endif
                </div>
            </div>

            {{-- Payment Methods --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Payment Methods
                </h3>
                <div class="space-y-4">
                    {{-- Credit Card --}}
                    <div class="p-4 rounded-xl bg-gradient-to-r from-blue-50 to-blue-50 dark:from-blue-900/20 dark:to-blue-900/20 border border-blue-200 dark:border-blue-800">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Credit Card</span>
                            <span class="text-lg font-bold text-blue-600 dark:text-blue-400">65%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2 rounded-full transition-all duration-500" style="width: 65%"></div>
                        </div>
                    </div>

                    {{-- E-Wallet --}}
                    <div class="p-4 rounded-xl bg-gradient-to-r from-green-50 to-green-50 dark:from-green-900/20 dark:to-green-900/20 border border-green-200 dark:border-green-800">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">E-Wallet</span>
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">25%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full transition-all duration-500" style="width: 25%"></div>
                        </div>
                    </div>

                    {{-- Bank Transfer --}}
                    <div class="p-4 rounded-xl bg-gradient-to-r from-purple-50 to-purple-50 dark:from-purple-900/20 dark:to-purple-900/20 border border-purple-200 dark:border-purple-800">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Bank Transfer</span>
                            <span class="text-lg font-bold text-purple-600 dark:text-purple-400">10%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2 rounded-full transition-all duration-500" style="width: 10%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Activity Timeline --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Recent Activity
                </h3>
                <div class="space-y-4">
                    {{-- Activity 1 --}}
                    <div class="flex items-start space-x-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-lg">✅</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Purchase completed</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">2 hours ago</p>
                        </div>
                    </div>

                    {{-- Activity 2 --}}
                    <div class="flex items-start space-x-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-lg">📦</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Order shipped</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">5 hours ago</p>
                        </div>
                    </div>

                    {{-- Activity 3 --}}
                    <div class="flex items-start space-x-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-lg">💳</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Payment processed</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">1 day ago</p>
                        </div>
                    </div>

                    {{-- Activity 4 --}}
                    <div class="flex items-start space-x-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-lg">🎁</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Reward claimed</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">2 days ago</p>
                        </div>
                    </div>

                    {{-- Activity 5 --}}
                    <div class="flex items-start space-x-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-lg">⭐</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Review submitted</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">3 days ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Time Range Filter
            function filterByTimeRange(days) {
                const url = new URL(window.location.href);
                url.searchParams.set('days', days);
                window.location.href = url.toString();
            }

            document.addEventListener('DOMContentLoaded', function() {

                // Spending Trend Chart
                const trendCtx = document.getElementById('spendingTrendChart');
                if (trendCtx) {
                    new Chart(trendCtx, {
                        type: 'line',
                        data: {
                            labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                            datasets: [{
                                label: 'Spending (VND)',
                                data: [1800000, 1100000, 2500000, 1200000],
                                borderColor: 'rgb(99, 102, 241)',
                                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointRadius: 6,
                                pointHoverRadius: 8,
                                borderWidth: 3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    cornerRadius: 8,
                                    callbacks: {
                                        label: function(context) {
                                            return 'Spending: ' + context.parsed.y.toLocaleString('vi-VN') + ' VND';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value.toLocaleString('vi-VN');
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                // Category Chart
                const categoryCtx = document.getElementById('categoryChart');
                if (categoryCtx) {
                    new Chart(categoryCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Laptop', 'Phone', 'Tablet', 'Accessory', 'Others'],
                            datasets: [{
                                data: [35, 25, 20, 15, 5],
                                backgroundColor: [
                                    'rgb(99, 102, 241)',
                                    'rgb(34, 197, 94)',
                                    'rgb(251, 146, 60)',
                                    'rgb(168, 85, 247)',
                                    'rgb(248, 113, 113)'
                                ],
                                borderWidth: 0,
                                hoverOffset: 10
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 15,
                                        font: {
                                            size: 12
                                        },
                                        usePointStyle: true,
                                        pointStyle: 'circle'
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.label + ': ' + context.parsed + '%';
                                        }
                                    }
                                }
                            },
                            cutout: '65%'
                        }
                    });
                }
            });

            // Auto-refresh every 5 minutes
            setInterval(() => {
                console.log('Auto-refreshing analytics...');
                window.location.reload();
            }, 300000); // 5 minutes
        </script>
    @endpush
</x-app-layout>