<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('User Personal') }}
</h2>
</x-slot>



 <!-- THANH ĐIỀU HƯỚNG PROFILE -->
  <div class="py-4"> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- thanh nav hiển thị đúng Active Tab -->
            <div class="flex space-x-2 p-3 bg-gray-50 border border-gray-200 rounded-xl shadow-inner">
                
                <!-- Nút Thụ động (Profile Settings) -->
                <a href="{{ route('profile.edit') }}" 
                   class="px-5 py-2 text-sm font-medium rounded-lg 
                          text-gray-700 hover:text-indigo-600 
                          hover:bg-white transition duration-150 ease-in-out">
                    {{ __('Profile Settings') }}
                </a>
                
                <!-- Nút Active (User Profile) -->
                <!-- Thể hiện người dùng đang ở đây -->
                <a href="{{ route('user.profile') }}" 
                   class="px-5 py-2 text-sm font-bold rounded-lg 
                          text-indigo-800 bg-indigo-200 shadow-md 
                          hover:bg-indigo-300 transition duration-150 ease-in-out">
                    {{ __('User Profile') }}
                </a>
            </div>
        </div>
  </div>
  

 <!-- NỘI DUNG TRANG -->
<div class="py-4">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Box trình bày-->
        <div class="bg-white overflow-hidden shadow-x1 sm:rounded-lg p-6 lg:p-8">

            <h3 class="text-2xl font-bold text-gray-900 mb-4 border-b pb-2">
                 {{ __('Personal Information') }}
            </h3>

            <!-- Hiển thị Avatar Placeholder -->
            <div class="flex items-center space-x-6 mb-6">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 text-3xl font-semibold border-4 border-indigo-300 shadow-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                <!-- $user được truyền từ UserController sang -->
                <div>
                    <p class="text-3xl font-extrabold text-gray-800">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">Member since {{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Danh sách thông tin chi tiết -->
            <div class="space-y-3 text-lg text-gray-700">

                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="font-semibold w-32">{{ __('Email:') }}</span>
                    <span class="text-gray-900">{{ $user->email }}</span>
                </div>
                
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h2l-2 5h-2l2-5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7l-2 5h4" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 18c-4.418 0-8-3.582-8-8s3.582-8 8-8 8 3.582 8 8-3.582 8-8 8z" />
                    </svg>
                    <span class="font-semibold w-32">{{ __('User ID:') }}</span>
                    <span class="text-gray-900">{{ $user->id }}</span>
                </div>

                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="font-semibold w-32">{{ __('Join Date:') }}</span>
                    <span class="text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="mt-8 pt-4 border-t text-sm text-gray-500 italic">
                {{ __('This information is protected. Only logged-in users can access it.') }}
            </div>
        </div>
    </div>
</div>

</x-app-layout>
