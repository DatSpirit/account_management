<x-app-layout>

    {{-- HEADER --}}
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-4">

            <div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-8 h-8 mr-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Analytics Dashboard
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Insights & reports
                </p>
            </div>

            <div class="flex items-center space-x-3">

                <select id="time-range" onchange="changeRange(this.value)"
                    class="px-4 py-2 bg-white dark:bg-gray-700 border rounded-xl text-sm">
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>Last 7 days</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>Last 30 days</option>
                    <option value="90" {{ $days == 90 ? 'selected' : '' }}>Last 90 days</option>
                    <option value="365" {{ $days == 365 ? 'selected' : '' }}>Last year</option>
                </select>

                <a href="{{ route('analytics.export') }}"
                    class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-medium shadow">
                    Export
                </a>

            </div>

        </div>
    </x-slot>

    {{-- PAGE CONTAINER --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">


        {{-- ========================= --}}
        {{-- KPI CARDS --}}
        {{-- ========================= --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

            {{-- Total Revenue --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Revenue</p>
                        <p class="text-2xl font-bold mt-2 dark:text-white">
                            {{ number_format($analytics['totalRevenue']) }} VND
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            {{ $startDate->toDateString() }} → {{ $endDate->toDateString() }}
                        </p>
                    </div>
                    <div class="rounded-xl bg-gradient-to-br from-green-400 to-emerald-600 p-3">
                        <svg class="w-8 h-8 text-green-500 dark:text-emerald-400" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" aria-hidden="true">
                            <circle cx="12" cy="12" r="8" stroke-width="1.5" />
                            <path
                                d="M10.5 9.5c.6-.4 1.6-.6 2.5 0 .9.6 1.5 1.8 1.5 2.5 0 .9-.6 1.9-1.5 2.4-.9.5-1.9.3-2.5 0"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12 8v8" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Orders --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Orders</p>
                        <p class="text-2xl font-bold mt-2 dark:text-white">
                            Total {{ $analytics['ordersTotal'] }} / Success {{ $analytics['ordersSuccess'] }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            Success: {{ $analytics['successRate'] }}%
                        </p>
                    </div>
                    <div class="rounded-xl bg-gradient-to-br from-blue-400 to-indigo-600 p-3">
                        <svg class="w-8 h-8 text-slate-600 dark:text-slate-300" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" aria-hidden="true">
                            <path d="M4 6h16" stroke-width="1.8" stroke-linecap="round" />
                            <path d="M4 12h16" stroke-width="1.8" stroke-linecap="round" />
                            <path d="M4 18h16" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Avg Order Value --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Avg Order Value</p>
                        <p class="text-2xl font-bold mt-2 dark:text-white">
                            {{ number_format($analytics['avgOrder']) }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">Success only</p>
                    </div>
                    <div class="rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 p-3">
                        <svg class="w-8 h-8 text-purple-500 dark:text-pink-400" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" aria-hidden="true">
                            <path d="M12 5v14" stroke-width="1.8" stroke-linecap="round" />
                            <path d="M5 12h14" stroke-width="1.8" stroke-linecap="round" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Payment Methods --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Payment Methods</p>
                        <p class="text-2xl font-bold mt-2 dark:text-white">
                            {{ $paymentMethods['COINKEY'] }} COINKEY & {{ $paymentMethods['VND'] }} VND
                        </p>
                        <p class="text-xs text-gray-400 mt-1">
                            Total transactions
                        </p>
                    </div>
                    <div class="rounded-xl bg-gradient-to-br from-orange-400 to-red-500 p-3">
                        <svg class="w-8 h-8 text-orange-400 dark:text-red-500" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" aria-hidden="true">
                            <path d="M20 6L9 17l-5-5" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>

                    </div>
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- CHARTS --}}
        {{-- ========================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- Revenue Trend --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border">
                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-semibold dark:text-white">Revenue Trend</h3>
                    <p class="text-sm text-gray-500">
                        {{ $startDate->toDateString() }} → {{ $endDate->toDateString() }}
                    </p>
                </div>
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            {{-- Payment Methods --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border">
                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-semibold dark:text-white">Payment Distribution</h3>
                    <p class="text-sm text-gray-500">COINKEY vs VND</p>
                </div>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="paymentChart" class="max-w-[300px]"></canvas>
                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- HOURLY + TOP PRODUCTS --}}
        {{-- ========================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

            {{-- Heatmap --}}
            <div class="col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border">

                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-semibold dark:text-white">Hourly Activity</h3>
                    <p class="text-sm text-gray-500">Heatmap</p>
                </div>

                <div id="heatmap" class="grid grid-cols-12 gap-2"></div>
            </div>

            {{-- Top Products --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border">
                <h3 class="text-lg font-semibold dark:text-white mb-3">Top Products</h3>
                <div class="space-y-3">

                    @forelse($topProducts as $p)
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium dark:text-white">{{ $p->product_name }}</p>
                                <p class="text-xs text-gray-500">{{ $p->orders }} orders</p>
                            </div>
                            <p class="text-sm font-bold text-green-600 dark:text-green-400">
                                {{ number_format($p->total) }}
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500">No products in this period.</p>
                    @endforelse

                </div>
            </div>

        </div>


        {{-- ========================= --}}
        {{-- TOP CUSTOMERS + COHORT --}}
        {{-- ========================= --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

            {{-- Top customers --}}
            <div class="col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border">

                <h3 class="text-lg font-semibold dark:text-white mb-4">
                    Top Customers
                </h3>

                <div class="space-y-3">
                    @foreach ($topCustomers as $c)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div
                                    class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg
                                            flex items-center justify-center text-sm font-semibold">
                                    {{ strtoupper(substr($c['name'], 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium dark:text-white">{{ $c['name'] }}</p>
                                    <p class="text-xs text-gray-400">{{ $c['orders'] }} orders</p>
                                </div>
                            </div>
                            <p class="text-sm font-bold text-green-600 dark:text-green-400">
                                {{ number_format($c['total_spent']) }}
                            </p>
                        </div>
                    @endforeach
                </div>

            </div>

            {{-- Cohort Summary --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-5 border">

                <h3 class="text-lg font-semibold dark:text-white mb-3">Cohort Summary</h3>

                <div class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                    <div class="flex justify-between"><span>New users</span><span>{{ $cohort['newUsers'] }}</span>
                    </div>
                    <div class="flex justify-between"><span>Revenue from new
                            users</span><span>{{ number_format($cohort['newUsersRevenue']) }}</span></div>
                    <div class="flex justify-between"><span>Repeat purchase
                            rate</span><span>{{ $cohort['repeatRate'] }}%</span></div>
                </div>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // ================================
        // TIME RANGE CHANGE
        // ================================
        function changeRange(days) {
            window.location.href = "?days=" + days;
        }


        // ================================
        // REVENUE TREND CHART
        // ================================
        const trendLabels = @json($trend->pluck('date')->toArray());
        const trendData = @json($trend->pluck('total')->toArray());


        const ctxRevenue = document.getElementById("revenueChart");

        new Chart(ctxRevenue, {
            type: "line",
            data: {
                labels: trendLabels,
                datasets: [{
                    label: "Revenue (VND)",
                    data: trendData,
                    tension: 0.4,
                    fill: true,
                    backgroundColor: function(context) {
                        const chart = context.chart;
                        const {
                            ctx,
                            chartArea
                        } = chart;
                        if (!chartArea) return null;

                        const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea
                            .bottom);
                        gradient.addColorStop(0, "rgba(99, 102, 241, 0.35)");
                        gradient.addColorStop(1, "rgba(99, 102, 241, 0)");
                        return gradient;
                    },
                    borderColor: "rgba(99, 102, 241, 1)",
                    borderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 6,
                    pointBackgroundColor: "rgba(99, 102, 241, 1)",
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        ticks: {
                            color: "#9CA3AF"
                        },
                        grid: {
                            color: "rgba(156,163,175,0.2)"
                        }
                    },
                    x: {
                        ticks: {
                            color: "#9CA3AF"
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });


        // ================================
        // PAYMENT METHOD PIE CHART
        // ================================
        const ctxPayment = document.getElementById("paymentChart");

        new Chart(ctxPayment, {
            type: "doughnut",
            data: {
                labels: ["COINKEY", "VND"],
                datasets: [{
                    data: [
                        {{ $paymentMethods['COINKEY'] }},
                        {{ $paymentMethods['VND'] }}
                    ],
                    backgroundColor: [
                        "rgba(52, 211, 153, 0.9)",
                        "rgba(59, 130, 246, 0.9)"
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: "65%",
                plugins: {
                    legend: {
                        position: "bottom",
                        labels: {
                            color: "#9CA3AF"
                        }
                    }
                }
            }
        });


        // ================================
        // HOURLY HEATMAP
        // ================================
        const hourlyData = @json($hourly);

        const heatmap = document.getElementById("heatmap");

        for (let h = 0; h < 24; h++) {
            const count = hourlyData[h] ?? 0;

            // Determine color intensity
            let bg;
            if (count === 0) bg = "bg-gray-200 dark:bg-gray-700";
            else if (count < 3) bg = "bg-green-200 dark:bg-green-700";
            else if (count < 7) bg = "bg-green-400 dark:bg-green-600";
            else bg = "bg-green-600 dark:bg-green-500";

            const cell = document.createElement("div");
            cell.className = `h-10 rounded flex items-center justify-center text-xs text-gray-900 dark:text-white ${bg}`;
            cell.innerHTML = `<span>${h}:00</span>`;
            heatmap.appendChild(cell);
        }
    </script>


</x-app-layout>
