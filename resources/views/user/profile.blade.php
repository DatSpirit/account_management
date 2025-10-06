<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-xl text-gray-800 leading-tight">
{{ __('User Personal Page') }}
</h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <!-- $user được truyền từ UserController sang -->
                {{ __("Xin chào") }} <strong>{{ $user->name }}</strong>!
                <p class="mt-2 text-lg font-medium">This is your information:</p>
                <ul class="list-disc ml-5 mt-2 space-y-1">
                    <li>Email: {{ $user->email }}</li>
                    <li>ID Account: {{ $user->id }}</li>
                    <li>Date of joining: {{ $user->created_at->format('d/m/Y') }}</li>
                </ul>
                <p class="mt-4 text-sm text-gray-500">
                   This page is protected: Only logged-in users can access it.
                </p>
            </div>
        </div>
    </div>
</div>


</x-app-layout>