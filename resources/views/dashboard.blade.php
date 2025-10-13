<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Thông báo chào mừng -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                <h3 class="text-lg font-medium mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                <p>You are logged in as an <span class="font-semibold">Administrator</span>.</p>
            </div>

            <!-- Thống kê nhanh -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Users</h4>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $userCount ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Orders</h4>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $orderCount ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Revenue</h4>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">${{ $revenue ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center">
                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Products</h4>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $productCount ?? 0 }}</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium mb-4">Quick Actions</h3>
                {{-- <div class="flex flex-wrap gap-4">
                    <a href="{{ route('admin.users.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow">Manage Users</a>
                    <a href="{{ route('admin.orders.index') }}" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded shadow">View Orders</a>
                    <a href="{{ route('admin.products.index') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded shadow">Manage Products</a>
                    <a href="{{ route('admin.reports.index') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded shadow">Reports</a>
                </div> --}}
            </div>

        </div>
    </div>
</x-app-layout>
