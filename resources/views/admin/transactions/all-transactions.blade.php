<x-app-layout>
    <div id="toast-container" class="fixed top-4 right-4 z-[100] space-y-3"></div>

    <x-slot name="header">
        {{-- <div class="flex items-center justify-between"> --}}

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-indigo-600 rounded-lg shadow-lg shadow-indigo-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-white tracking-tight">
                    Transaction Management
                </h2>
            </div>
            {{-- 
            <div
                class="inline-flex p-1 bg-gray-100 dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.transactions.all-transactions', array_merge(request()->all(), ['type' => 'cash'])) }}"
                    class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request('type', 'cash') == 'cash'
                        ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow-sm ring-1 ring-black/5'
                        : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                    <span class="mr-2">💰</span>
                    Tiền mặt
                </a>

                <a href="{{ route('admin.transactions.all-transactions', array_merge(request()->all(), ['type' => 'coinkey'])) }}"
                    class="flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request('type') == 'coinkey'
                        ? 'bg-white dark:bg-gray-700 text-indigo-600 dark:text-indigo-400 shadow-sm ring-1 ring-black/5'
                        : 'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200' }}">
                    <span class="mr-2">🪙</span>
                    Coinkey
                </a>
            </div> --}}
        </div>

        {{--  Nút Export Excel  --}}
        {{-- <a href="{{ route('admin.transactions.export', request()->query()) }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-700 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green-800 transition-all duration-200 shadow-md">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </a> --}}
        {{-- </div> --}}
    </x-slot>

    <div class="py-8 px-4 sm:px-6 lg:px-8 min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto space-y-6">

            <div class="bg-gradient-to-r from-blue-500 dark:from-blue-700 rounded-2xl shadow-xl p-6 sm:p-8 text-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="space-y-2">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 dark:text-white">Transaction Overview
                        </h3>
                        <p class="text-blue-800 dark:text-white text-sm sm:text-base">Quản lý tất cả giao dịch
                            thanh toán</p>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-800 dark:text-white">{{ $transactions->total() }}
                            </div>
                            <div class="text-xs text-blue-800 dark:text-white uppercase tracking-wide">Tổng GD
                            </div>
                        </div>
                        <div class="h-12 w-px bg-white/30"></div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-gray-800 dark:text-white">
                                {{ number_format($stats['total_amount'] / 1000) }}K
                            </div>
                            <div class="text-xs text-blue-800 dark:text-white uppercase tracking-wide">Tổng Thu
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                <div
                    class="bg-gradient-to-br from-blue-200 to-blue-600 dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-4 sm:p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-800 dark:text-white font-medium">Total</p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                {{ number_format($stats['total']) }}</p>
                            <p class="text-xs text-gray-800 dark:text-white mt-2">Today: <span
                                    class="font-semibold">{{ $stats['today'] }}</span></p>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-full">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600 dark:text-blue-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-green-200 to-green-600 dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-4 sm:p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-800 dark:text-white font-medium">Success</p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                {{ number_format($stats['success']) }}</p>
                            <p class="text-xs text-gray-800 dark:text-white mt-2">
                                {{ number_format($stats['total_amount']) }}</p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-full">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-green-600 dark:text-green-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-yellow-200 to-yellow-600 dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-4 sm:p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-800 dark:text-white font-medium">Pending</p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                {{ number_format($stats['pending']) }}</p>
                            <p class="text-xs text-gray-800 dark:text-white mt-2">
                                {{ number_format($stats['pending_amount'] / 1000) }}K</p>
                        </div>
                        <div class="bg-yellow-100 dark:bg-yellow-900/30 p-3 rounded-full">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-yellow-600 dark:text-yellow-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-red-200 to-red-600 dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl p-4 sm:p-6 border border-gray-200 dark:border-gray-700 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs sm:text-sm text-gray-800 dark:text-white font-medium">Failed</p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100 mt-1">
                                {{ number_format($stats['failed'] + $stats['cancelled']) }}</p>
                            <p class="text-xs text-gray-800 dark:text-white mt-2">Cancelled:
                                {{ $stats['cancelled'] }}</p>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900/30 p-3 rounded-full">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-red-600 dark:text-red-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-4 sm:p-6 transition-all duration-300 hover:shadow-xl">
                <form method="GET" action="{{ route('admin.transactions.all-transactions') }}" class="space-y-4">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4">
                        {{-- Currency Filter --}}
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                                Currency
                            </label>
                            <select name="type"
                                class="w-full px-3 py-2 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all duration-200 cursor-pointer">
                                <option value="cash" {{ request('type', 'cash') === 'cash' ? 'selected' : '' }}>
                                    Cash</option>
                                <option value="coinkey" {{ request('type') === 'coinkey' ? 'selected' : '' }}>
                                    Coinkey</option>
                            </select>
                        </div>
                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                                Status
                            </label>
                            <select name="status"
                                class="w-full px-3 py-2 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all duration-200 cursor-pointer">
                                <option value="">All</option>
                                <option value="pending" {{ ($status ?? '') == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="success" {{ ($status ?? '') == 'success' ? 'selected' : '' }}>Success
                                </option>
                                <option value="failed" {{ ($status ?? '') == 'failed' ? 'selected' : '' }}>Failed
                                </option>
                                <option value="cancelled" {{ ($status ?? '') == 'cancelled' ? 'selected' : '' }}>
                                    Cancelled</option>
                            </select>
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                                From
                            </label>
                            <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}"
                                class="w-full px-3 py-2 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all duration-200">
                        </div>

                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                                To
                            </label>
                            <input type="date" name="date_to" value="{{ $dateTo ?? '' }}"
                                class="w-full px-3 py-2 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all duration-200">
                        </div>



                        <div>
                            <label
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                                Advanced filters
                            </label>
                            <select name="advanced"
                                class="w-full px-3 py-2 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all duration-200 cursor-pointer">
                                <option value="">All</option>
                                <option value="order" {{ request('advanced') == 'order' ? 'selected' : '' }}>Order
                                </option>
                                <option value="user" {{ request('advanced') == 'user' ? 'selected' : '' }}>User
                                </option>
                                <option value="product" {{ request('advanced') == 'product' ? 'selected' : '' }}>
                                    Product
                                </option>
                                <option value="key" {{ request('advanced') == 'key' ? 'selected' : '' }}>Key
                                </option>
                            </select>
                        </div>
                        <div class="pt-6">
                            <a href="{{ route('admin.transactions.all-transactions') }}"
                                class="w-full flex items-center justify-center px-3 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-300 dark:hover:bg-gray-600 font-medium transition-all duration-200 text-sm h-full">
                                Reset
                            </a>
                        </div>

                        <div class="col-span-2 sm:col-span-3 lg:col-span-2">
                            <label
                                class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">
                                Search
                            </label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ $search ?? '' }}"
                                    placeholder="Order , User, Key Code, Sản phẩm..."
                                    class="w-full pl-4 pr-12 py-2 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-sm font-medium text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-4 focus:ring-indigo-500/20 transition-all duration-200">
                                <button type="submit"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 p-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </div>

                        </div>

                    </div>
                </form>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700 border-b-2 border-gray-200 dark:border-gray-600">
                                <th
                                    class="px-3 sm:px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Order
                                </th>
                                <th
                                    class="px-3 sm:px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">
                                    User
                                </th>
                                <th
                                    class="px-3 sm:px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider ">
                                    Product
                                </th>
                                <th
                                    class="px-3 sm:px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">
                                    Amount
                                </th>
                                <th
                                    class="px-3 sm:px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">
                                    Description
                                </th>
                                <th
                                    class="px-3 sm:px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th
                                    class="px-3 sm:px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider hidden lg:table-cell">
                                    Time
                                </th>
                                <th
                                    class="px-3 sm:px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    View
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($transactions as $transaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                    <td class="px-3 sm:px-6 py-4">
                                        <div class="flex flex-col space-y-1">
                                            <div class="max-w-xs">
                                                <span
                                                    class="text-sm font-bold text-indigo-600 dark:text-indigo-400 break-words whitespace-normal">{{ $transaction->order_code }}</span>
                                                {{-- Hiển thị Time chỉ trên Mobile --}}
                                                <span class="text-xs text-gray-500 dark:text-gray-400 lg:hidden">
                                                    <svg class="w-3 h-3 inline mr-1" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $transaction->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y H:i') }}
                                                </span>
                                                <span
                                                    class="lg:hidden text-xs text-gray-700 dark:text-gray-300 font-medium break-words whitespace-normal">
                                                    <svg class="w-3 h-3 inline mr-1" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    {{ $transaction->user->name ?? 'Guest' }}
                                                </span>
                                                <span class="text-xs text-gray-600 dark:text-gray-300 sm:hidden break-words whitespace-normal ">
                                                    <svg class="w-3 h-3 inline mr-1" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 8l7.89 5.26c.45.3.93.3 1.35 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                    @if ($transaction->user?->email)
                                                        {{ $transaction->user->email }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-3 sm:px-6 py-4 hidden lg:table-cell">
                                        @if ($transaction->user)
                                            <div class="flex items-center space-x-3">
                                                <div
                                                    class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center flex-shrink-0">
                                                    <span
                                                        class="text-indigo-600 dark:text-indigo-400 font-semibold text-sm">
                                                        {{ strtoupper(substr($transaction->user->name, 0, 2)) }}
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p
                                                        class="text-sm font-semibold text-gray-900 dark:text-gray-100 truncate">
                                                        {{ $transaction->user->name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                                        {{ $transaction->user->email }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400 dark:text-gray-500 italic">Guest</span>
                                        @endif
                                    </td>

                                    <td class="px-3 sm:px-6 py-4 text-left whitespace-nowrap">
                                        @php
                                            $meta = $transaction->response_data ?? [];
                                            $type = $meta['type'] ?? '';
                                            $description = $transaction->description ?? '';
                                            $last3 = substr($description, -3);
                                            $last2 = substr($description, -2);
                                            $last1 = substr($description, -1);
                                        @endphp

                                        @if ($transaction->product || !empty($meta) || $description)
                                            <div class="flex flex-col space-y-2">
                                                {{-- Tên sản phẩm gốc --}}
                                                @if ($transaction->product)
                                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ Str::limit($transaction->product->name, 30) }}
                                                    </span>
                                                @else
                                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                                        Custom Extension
                                                    </span>
                                                @endif
                                                {{--  LOGIC HIỂN THỊ CHI TIẾT THEO LOẠI --}}
                                                {{-- 1️ GIA HẠN TÙY CHỈNH (CEX) --}}
                                                @if ($type === 'custom_key_extension' || $last3 === 'CEX')
                                                    <div
                                                        class="flex flex-col mt-1 p-3 bg-gradient-to-br from-orange-50 to-blue-50 dark:from-orange-900/20 dark:to-blue-900/20 rounded-xl border-2 border-orange-200 dark:border-orange-700 shadow-sm">
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <span
                                                                class="text-xs font-bold text-orange-700 dark:text-orange-300 uppercase tracking-wide">
                                                                Custom Extension Key
                                                            </span>
                                                        </div>
                                                        <div class="space-y-1">
                                                            <div class="flex items-center justify-between text-xs">
                                                                <span class="text-gray-600 dark:text-gray-400">ID
                                                                    Key:</span>
                                                                <strong
                                                                    class="text-orange-400 dark:text-orange-200">#{{ $meta['key_id'] ?? 'N/A' }}</strong>
                                                            </div>
                                                            @if (isset($meta['key_code']))
                                                                <div class="flex items-center justify-between text-xs">
                                                                    <span class="text-gray-600 dark:text-gray-400">
                                                                        Code:</span>
                                                                    <code
                                                                        class="bg-orange-100 dark:bg-orange-800 px-2 py-1 rounded text-xs font-mono font-bold">
                                                                        {{ $meta['key_code'] }}
                                                                    </code>
                                                                </div>
                                                            @endif
                                                            {{-- @if (isset($meta['package_name']))
                                                                <div class="flex items-center justify-between text-xs">
                                                                    <span
                                                                        class="text-gray-600 dark:text-gray-400">Gói:</span>
                                                                    <span
                                                                        class="font-semibold text-purple-600 dark:text-purple-400">
                                                                        {{ $meta['package_name'] }}
                                                                    </span>
                                                                </div>
                                                            @endif --}}
                                                            @if (isset($meta['days_added']))
                                                                <div class="flex items-center justify-between text-xs">
                                                                    <span class="text-gray-600 dark:text-gray-400">Thời
                                                                        gian:</span>
                                                                    <span
                                                                        class="font-bold text-green-600 dark:text-green-400">
                                                                        +{{ $meta['days_added'] }} ngày
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- 2️ GIA HẠN THƯỜNG (EX) --}}
                                                @elseif ($last2 === 'EX' || $type === 'key_extension')
                                                    <div
                                                        class="flex flex-col mt-1 p-3 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl border-2 border-green-200 dark:border-green-700 shadow-sm">
                                                        <div class="flex items-center gap-2 mb-2">

                                                            <span
                                                                class="text-xs font-bold text-green-700 dark:text-green-300 uppercase tracking-wide">
                                                                Extension Key
                                                            </span>
                                                        </div>
                                                        <div class="space-y-1">
                                                            <div class="flex items-center justify-between text-xs">
                                                                <span class="text-gray-600 dark:text-gray-400">ID
                                                                    Key:</span>
                                                                <strong
                                                                    class="text-green-600 dark:text-green-400">#{{ $meta['key_id'] ?? 'N/A' }}</strong>
                                                            </div>
                                                            @if (isset($meta['key_code']))
                                                                <div class="flex items-center justify-between text-xs">
                                                                    <span class="text-gray-600 dark:text-gray-400">
                                                                        Code:</span>
                                                                    <code
                                                                        class="bg-green-100 dark:bg-green-800 px-2 py-1 rounded text-xs font-mono">
                                                                        {{ $meta['key_code'] }}
                                                                    </code>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    {{-- 3️ CUSTOM KEY PURCHASE --}}
                                                @elseif ($type === 'custom_key_purchase')
                                                    @php
                                                        $keyId = $meta['key_id'] ?? null;
                                                        $customKey = $keyId
                                                            ? \App\Models\ProductKey::find($keyId)
                                                            : $transaction->productKey;
                                                    @endphp

                                                    <div
                                                        class="flex flex-col mt-1 p-3 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl border-2 border-purple-200 dark:border-purple-700 shadow-sm">
                                                        <div class="flex items-center gap-2 mb-2">

                                                            <span
                                                                class="text-xs font-bold text-purple-700 dark:text-purple-300 uppercase tracking-wide">
                                                                Custom Key
                                                            </span>
                                                        </div>
                                                        @if ($customKey)
                                                            <div class="space-y-1">
                                                                <div class="flex items-center justify-between text-xs">
                                                                    <span class="text-gray-600 dark:text-gray-400">ID
                                                                        Key:</span>
                                                                    <strong
                                                                        class="text-purple-600 dark:text-purple-400">#{{ $customKey->id }}</strong>
                                                                </div>
                                                                <div class="flex items-center justify-between text-xs">
                                                                    <span class="text-gray-600 dark:text-gray-400">
                                                                        Code:</span>
                                                                    <code
                                                                        class="bg-purple-100 dark:bg-purple-800 px-2 py-1 rounded text-xs font-mono font-bold">
                                                                        {{ $customKey->key_code }}
                                                                    </code>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="text-xs text-yellow-600 dark:text-yellow-400">
                                                                ⏳ Đang xử lý...
                                                            </div>
                                                        @endif
                                                    </div>

                                                    {{-- 4️ PACKAGE PURCHASE (Standard Key) --}}
                                                @elseif (($last1 === 'K' && !$type) || $type === 'package_purchase')
                                                    @if ($transaction->productKey)
                                                        <div
                                                            class="flex flex-col mt-1 p-3 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border-2 border-blue-200 dark:border-blue-700 shadow-sm">
                                                            <div class="flex items-center gap-2 mb-2">

                                                                <span
                                                                    class="text-xs font-bold text-blue-700 dark:text-blue-300 uppercase tracking-wide">
                                                                    Normally Key
                                                                </span>
                                                            </div>
                                                            <div class="space-y-1">
                                                                <div class="flex items-center justify-between text-xs">
                                                                    <span class="text-gray-600 dark:text-gray-400">ID
                                                                        Key:</span>
                                                                    <strong
                                                                        class="text-blue-600 dark:text-blue-400">#{{ $transaction->productKey->id }}</strong>
                                                                </div>
                                                                <div class="flex items-center justify-between text-xs">
                                                                    <span class="text-gray-600 dark:text-gray-400">
                                                                        Code:</span>
                                                                    <code
                                                                        class="bg-blue-100 dark:bg-blue-800 px-2 py-1 rounded text-xs font-mono">
                                                                        {{ $transaction->productKey->key_code }}
                                                                    </code>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    {{-- 5️ COINKEY DEPOSIT --}}
                                                @elseif ($last1 === 'C' || $transaction->product?->product_type === 'coinkey')
                                                    <div
                                                        class="flex items-center gap-2 p-2 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-700">
                                                        <span class="text-xl">💰</span>
                                                        <span
                                                            class="text-xs font-medium text-yellow-700 dark:text-yellow-400">
                                                            Nạp ví Coinkey
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400 italic">No Info</span>
                                        @endif
                                        <span class="text-xs text-gray-500 dark:text-gray-400 sm:hidden">
                                            Desc: {{ $transaction->order_code }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 hidden md:table-cell">
                                        <div class="flex flex-col items-end space-y-1">
                                            <span
                                                class="text-sm sm:text-base font-bold text-gray-900 dark:text-gray-100">
                                                {{ number_format($transaction->amount, 0, ',', '.') }}
                                            </span>
                                            @if ($transaction->currency === 'COINKEY')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300">
                                                    Coinkey
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300">
                                                    VND
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3 sm:px-6 py-4 hidden lg:table-cell">
                                        <div class="flex flex-col space-y-1">
                                            @php
                                                // 1. Xác định hậu tố (Suffix)
                                                $meta = $transaction->response_data ?? []; // Lấy metadata từ JSON
                                                $type = $meta['type'] ?? '';
                                                $productType = $transaction->product->product_type ?? '';

                                                $suffix = '';
                                                if ($type === 'key_extension') {
                                                    $suffix = 'EX';
                                                } elseif ($productType === 'coinkey') {
                                                    $suffix = 'C';
                                                } elseif ($productType === 'package') {
                                                    $suffix = 'K';
                                                } elseif ($type === 'custom_key_extension') {
                                                    $suffix = 'CEX';
                                                }

                                                $finalOrderCode = $transaction->order_code . $suffix;
                                            @endphp

                                            {{-- Hiển thị Order Code với Hậu tố chuẩn --}}
                                            <span class="text-xs font-bold text-gray-600 dark:text-gray-400">
                                                {{ $finalOrderCode }}
                                            </span>

                                            <span class="text-xs text-gray-500 lg:hidden">
                                                {{ $transaction->created_at->format('d/m H:i') }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-3 sm:px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex flex-col items-end space-y-1">
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
                                                        'bg' => 'bg-gray-100 dark:bg-gray-700',
                                                        'text' => 'text-gray-800 dark:text-gray-300',
                                                        'label' => 'Failed',
                                                        'icon' => '✗',
                                                    ],
                                                    'cancelled' => [
                                                        'bg' => 'bg-red-100 dark:bg-red-900/50',
                                                        'text' => 'text-red-800 dark:text-red-300',
                                                        'label' => 'Cancelled',
                                                        'icon' => '⊘',
                                                    ],
                                                ];
                                                $config =
                                                    $statusConfig[$transaction->status] ?? $statusConfig['pending'];
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold shadow-sm {{ $config['bg'] }} {{ $config['text'] }}">
                                                <span class="mr-1">{{ $config['icon'] }}</span>
                                                {{ $config['label'] }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400 sm:hidden">
                                                {{ number_format($transaction->amount, 0, ',', '.') }}
                                                @if ($transaction->currency === 'COINKEY')
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300">
                                                        Coinkey
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300">
                                                        VND
                                                    </span>
                                                @endif
                                            </span>
                                        </div>
                                    </td>


                                    <td class="px-3 sm:px-6 py-4 hidden sm:table-cell">
                                        <div class="flex flex-col text-sm">
                                            <span
                                                class="text-gray-900 dark:text-gray-100">{{ $transaction->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('d/m/Y') }}</span>
                                            <span
                                                class="text-gray-500 dark:text-gray-400 text-xs">{{ $transaction->created_at->setTimezone('Asia/Ho_Chi_Minh')->format('H:i:s') }}</span>
                                            <span
                                                class="text-gray-400 dark:text-gray-500 text-xs">{{ $transaction->created_at->setTimezone('Asia/Ho_Chi_Minh')->diffForHumans() }}</span>
                                        </div>
                                    </td>

                                    <td class="px-3 sm:px-6 py-4 text-center whitespace-nowrap">
                                        <a href="{{ route('admin.transactions.show', $transaction->id) }}"
                                            title="View details"
                                            class="p-2.5 rounded-lg bg-indigo-500 hover:bg-indigo-600 dark:bg-indigo-600 dark:hover:bg-indigo-700 text-white shadow-md hover:shadow-lg transform hover:scale-110 transition-all duration-200 inline-flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center space-y-4">
                                            <div
                                                class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400 dark:text-gray-500"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                                </svg>
                                            </div>
                                            <div class="space-y-2">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                                    No transactions found
                                                </h3>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                                    Try changing filters or create a new transaction
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($transactions->hasPages())
                    <div
                        class="px-4 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800">
                        {{ $transactions->appends(request()->query())->links('vendor.pagination.tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
