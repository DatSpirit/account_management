<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <!-- Thanh điều hướng phụ giữa các trang hồ sơ -->
    <div class="pt-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-wrap gap-3 p-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <!-- Nút chuyển sang Profile Settings -->
                <a href="{{ route('profile.edit') }}" 
                   class="px-4 py-2 text-sm font-semibold rounded-lg text-indigo-600 bg-indigo-50 border border-indigo-200 shadow-sm hover:bg-indigo-100 dark:bg-indigo-900 dark:text-indigo-200 dark:hover:bg-indigo-800 transition">
                    {{ __('Profile Settings') }}
                </a>

                <!-- Nút chuyển sang User Profile -->
                <a href="{{ route('user.profile') }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg text-gray-700 bg-gray-50 border border-gray-200 shadow-sm hover:bg-gray-100 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600 transition">
                    {{ __('User Profile') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Nội dung chỉnh sửa thông tin -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Cập nhật thông tin cá nhân -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Cập nhật mật khẩu -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Xóa tài khoản -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
