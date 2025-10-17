<x-app-layout>
    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-[100] space-y-3"></div>

    <x-slot name="header">
        <div class="flex items-center justify-center space-x-3">
            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 tracking-tight">
                Admin Dashboard
            </h2>
        </div>
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 dark:from-indigo-700 dark:via-purple-700 dark:to-pink-700 rounded-2xl shadow-xl p-6 sm:p-8">
                <div class="space-y-2">
                    <h3 class="text-xl sm:text-2xl font-bold text-white">System Overview</h3>
                    <p class="text-white/90 text-sm sm:text-base">Tổng quan hệ thống và thống kê người dùng</p>
                    <div class="flex items-center space-x-2 text-white/80 text-sm mt-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Cập nhật: {{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Total Users Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl">
                            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        @if($growthPercentage != 0)
                            <span class="text-xs font-semibold {{ $isGrowth ? 'text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30' : 'text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30' }} px-2 py-1 rounded-full">
                                {{ $isGrowth ? '+' : '' }}{{ $growthPercentage }}%
                            </span>
                        @else
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">
                                0%
                            </span>
                        @endif
                    </div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Tổng Người Dùng</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalUsers) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                        @if($growthPercentage > 0)
                            <span class="text-green-600 dark:text-green-400">↑ {{ number_format($usersThisMonth) }}</span> người tháng này
                        @elseif($growthPercentage < 0)
                            <span class="text-red-600 dark:text-red-400">↓ {{ number_format($usersThisMonth) }}</span> người tháng này
                        @else
                            <span class="text-gray-600 dark:text-gray-400">→ {{ number_format($usersThisMonth) }}</span> người tháng này
                        @endif
                    </p>
                </div>

                <!-- Growth This Month Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-teal-100 dark:bg-teal-900/30 rounded-xl">
                            <svg class="w-8 h-8 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                        </div>
                        @if($yearlyGrowthPercentage != 0)
                            <span class="text-xs font-semibold {{ $isYearlyGrowth ? 'text-teal-600 dark:text-teal-400 bg-teal-100 dark:bg-teal-900/30' : 'text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-900/30' }} px-2 py-1 rounded-full">
                                {{ $isYearlyGrowth ? '+' : '' }}{{ $yearlyGrowthPercentage }}%
                            </span>
                        @else
                            <span class="text-xs font-semibold text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full">
                                Năm {{ date('Y') }}
                            </span>
                        @endif
                    </div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Tăng Trưởng Năm Nay</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ number_format($totalGrowthThisYear) }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                        Tổng người dùng mới năm {{ date('Y') }}
                    </p>
                </div>

                <!-- New Users This Month Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-xl">
                            <svg class="w-8 h-8 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <span class="text-xs font-semibold text-amber-600 dark:text-amber-400 bg-amber-100 dark:bg-amber-900/30 px-2 py-1 rounded-full">
                            Mới
                        </span>
                    </div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">Người Dùng Mới Tháng Này</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                        {{ number_format(\App\Models\User::whereMonth('created_at', now()->month)->count()) }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                        Tháng {{ now()->format('m/Y') }}
                    </p>
                </div>
            </div>

            <!-- Chart & Recent Users Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Chart Card -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span>Biểu Đồ Tăng Trưởng Người Dùng</span>
                        </h4>
                        <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">
                            Năm {{ date('Y') }}
                        </span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl">
                        <canvas id="userGrowthChart" height="100"></canvas>
                    </div>
                </div>

                <!-- Recent Users Card -->
                <div class="lg:col-span-1 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:shadow-xl">
                    <div class="flex items-center justify-between mb-6">
                        <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                            <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span>Người Dùng Mới Nhất</span>
                        </h4>
                    </div>
                    <div class="space-y-3 max-h-[400px] overflow-y-auto">
                        @foreach ($recentUsers as $rUser)
                            <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-900/50 hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors duration-200 border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                        {{ strtoupper(substr($rUser->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-800">{{ $rUser->name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $rUser->email }}</p>
                                    </div>
                                </div>
                                <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                    {{ $rUser->created_at->diffForHumans() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Smart Insights Card -->
            <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 dark:from-indigo-700 dark:via-purple-700 dark:to-pink-700 rounded-2xl shadow-xl p-6 sm:p-8">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 space-y-3">
                        <h3 class="text-2xl font-bold text-white flex items-center space-x-2">
                            <span>✨ Gợi ý tiện ích thông minh</span>
                        </h3>
                        <div class="space-y-2 text-white/90">
                            <p class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Hôm nay <strong>{{ now()->format('d/m/Y') }}</strong>, hệ thống của bạn đang hoạt động ổn định.</span>
                            </p>
                            <p class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>{{ number_format($totalUsers) }}</strong> người dùng đã tham gia!</span>
                            </p>
                            <p class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Thông tin cập nhật mới nhất trong 24h qua.</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Toast System
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
            
            toast.className = `flex items-center space-x-3 px-6 py-4 rounded-xl shadow-2xl border-2 ${colors[type]} transform transition-all duration-300 translate-x-full opacity-0`;
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

        // Check dark mode for chart colors
        const isDarkMode = document.documentElement.classList.contains('dark') || 
                          (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);

        // Chart Configuration
        const ctx = document.getElementById('userGrowthChart');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Th1','Th2','Th3','Th4','Th5','Th6','Th7','Th8','Th9','Th10','Th11','Th12'],
                datasets: [{
                    label: 'Người dùng mới',
                    data: @json($growthData),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    fill: true,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverBackgroundColor: '#6366f1',
                    pointHoverBorderColor: '#fff',
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { 
                        position: 'top',
                        labels: {
                            color: isDarkMode ? '#e5e7eb' : '#374151',
                            font: {
                                size: 12,
                                weight: '600'
                            },
                            padding: 15
                        }
                    },
                    title: { 
                        display: true, 
                        text: 'Thống kê tăng trưởng người dùng năm {{ date('Y') }}',
                        color: isDarkMode ? '#f3f4f6' : '#111827',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            bottom: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: isDarkMode ? '#1f2937' : '#ffffff',
                        titleColor: isDarkMode ? '#f3f4f6' : '#111827',
                        bodyColor: isDarkMode ? '#e5e7eb' : '#374151',
                        borderColor: isDarkMode ? '#374151' : '#e5e7eb',
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.parsed.y + ' người dùng';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: isDarkMode ? '#374151' : '#e5e7eb',
                            drawBorder: false
                        },
                        ticks: {
                            color: isDarkMode ? '#9ca3af' : '#6b7280',
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: isDarkMode ? '#374151' : '#e5e7eb',
                            drawBorder: false
                        },
                        ticks: {
                            color: isDarkMode ? '#9ca3af' : '#6b7280',
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Show flash messages
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
    </script>
</x-app-layout>