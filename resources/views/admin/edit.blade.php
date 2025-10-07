@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8">

    <h1 class="text-2xl font-bold mb-6">Edit User</h1>

    <!-- Hiển thị thông báo thành công / lỗi -->
    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Form edit user -->
    <form action="{{ route('admin.update', $user->id) }}" method="POST" class="space-y-4 bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')

        <!-- Name -->
        <div>
            <label class="block font-semibold mb-1" for="name">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                   class="w-full border rounded px-3 py-2 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email -->
        <div>
            <label class="block font-semibold mb-1" for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                   class="w-full border rounded px-3 py-2 @error('email') border-red-500 @enderror">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Is Admin -->
        <div class="flex items-center space-x-2">
            <input type="checkbox" name="is_admin" id="is_admin" value="1"
                   {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                   class="rounded border-gray-300">
            <label for="is_admin" class="font-semibold">Admin</label>
        </div>

        <!-- Buttons -->
        <div class="flex space-x-2 mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                Update
            </button>
            <a href="{{ route('admin.users') }}"
               class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 transition">
               Cancel
            </a>
        </div>
    </form>
</div>
@endsection

