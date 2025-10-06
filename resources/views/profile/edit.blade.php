<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

     <!-- thêm liên kết của trang User-->
    <div class="pt-4">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex space-x-4 p-4 bg-white shadow-sm sm:rounded-lg border border-gray-200"> <!--chỉnh sửa Css cho dễ nhìn hơn -->
                <!-- Nút chuyển sang Profile Settings -->
                <a href="{{ route('profile.edit') }}" 
                   class="px-4 py-2 text-sm font-semibold rounded-lg text-indigo-600 bg-indigo-50 border border-indigo-200 shadow-md">
                    {{ __('Account Settings') }}
                </a>
                <!-- Nút chuyển sang User Profile -->
                <a href="{{ route('user.profile') }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition duration-150 ease-in-out">
                    {{ __('View Personal Page') }}
                </a>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
