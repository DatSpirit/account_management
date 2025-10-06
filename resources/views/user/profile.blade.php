<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('User Personal') }}
</h2>
</x-slot>



 <!-- THANH ĐIỀU HƯỚNG  -->
  <div class="py-4"> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- thanh nav hiển thị đúng Active Tab -->
               <!-- Sử dụng classes của trang Profile gốc để đồng bộ giao diện container -->
            <div class="flex space-x-4 p-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                
                <!-- Nút Thụ động (Profile Settings) -->
                <a href="{{ route('profile.edit') }}" 
                   class="px-4 py-2 text-sm font-medium rounded-lg 
                          text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 
                          hover:bg-gray-50 dark:hover:bg-gray-700 
                          transition duration-150 ease-in-out">
                    {{ __('Profile Settings') }}
                </a>
                
                <!-- Nút Active (User Profile) -->
                <!-- Thể hiện người dùng đang ở đây -->
                <a href="{{ route('user.profile') }}" 
                   class="px-4 py-2 text-sm font-semibold rounded-lg 
                          text-indigo-600 dark:text-indigo-200 
                          bg-indigo-50 dark:bg-indigo-900 
                          border border-indigo-200 dark:border-indigo-700 
                          shadow-md">
                    {{ __('User Profile') }}
                </a>
            </div>
        </div>
   </div>
  

 <!-- NỘI DUNG TRANG -->
<div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Box trình bày-->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 lg:p-8">

            <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4 border-b dark:border-gray-700 pb-2">
                 {{ __('Personal Information') }}
            </h3>

            <!-- Hiển thị Avatar Placeholder -->
            <div class="flex items-center space-x-6 mb-8">
                    <div class="
                        w-20 h-20 
                        bg-indigo-50 dark:bg-indigo-700 
                        rounded-full flex items-center justify-center 
                        text-indigo-600 dark:text-indigo-100 
                        text-4xl font-extrabold 
                        ring-4 ring-indigo-200 dark:ring-indigo-600 
                        shadow-xl transition-all duration-300
                    ">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                <!-- $user được truyền từ UserController sang -->
                <div>
                    <p class="text-3xl font-extrabold text-gray-800 dark:text-gray-100">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Member since {{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Danh sách thông tin chi tiết -->
            <div class="space-y-3 text-lg text-gray-700 dark:text-gray-300">

                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="font-semibold w-32">{{ __('Email:') }}</span>
                    <span class="text-gray-900 dark:text-gray-100">{{ $user->email }}</span>
                </div>
                
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h2l-2 5h-2l2-5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7l-2 5h4" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8z" />
                    </svg>
                    <span class="font-semibold w-32">{{ __('User ID:') }}</span>
                    <span class="text-gray-900 dark:text-gray-100">{{ $user->id }}</span>
                </div>

                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="font-semibold w-32">{{ __('Join Date:') }}</span>
                    <span class="text-gray-900 dark:text-gray-100">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t dark:border-gray-700 text-sm text-gray-500 dark:text-gray-400 italic">
                {{ __('This information is protected. Only logged-in users can access it.') }}
            </div>
        </div>
    </div>
</div>

</x-app-layout>
