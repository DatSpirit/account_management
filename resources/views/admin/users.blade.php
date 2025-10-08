<x-app-layout>
    <!-- Flash Messages -->
    <x-flash-message type="success" :message="session('success')" />
    <x-flash-message type="error" :message="session('error')" />

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Admin - User Management Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <div class="text-center sm:text-left">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ __('Danh sách người dùng') }}
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Quản lý tất cả người dùng đã đăng ký trong hệ thống.') }}
                    </p>
                </div>

                        <!-- Search + Filter -->
                <form method="GET" action="{{ route('admin.users') }}" class="mt-4 sm:mt-0 flex items-center space-x-2 relative">
                    <!-- Bộ lọc -->
                    <select name="filter" 
                    class="appearance-none px-5 py-2 font-semibold text-black bg-black 
                       border border-gray-600 rounded-md shadow-md pr-10
                       focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="name" {{ request('filter') === 'name' ? 'selected' : '' }}>Tên</option>
                        <option value="email" {{ request('filter') === 'email' ? 'selected' : '' }}>Email</option>
                        <option value="id" {{ request('filter') === 'id' ? 'selected' : '' }}>ID</option>
                    </select>

                    <!-- Ô tìm kiếm -->
                   
                        
                    <input id="search-input" type="text" name="search"
                        placeholder="Nhập từ khóa tìm kiếm..."
                        value="{{ request('search') }}"
                        class="pl-10 pr-4 py-2 w-full font-medium text-black bg-black 
                            border border-gray-600 rounded-md shadow-md
                            focus:outline-none focus:ring-2 focus:ring-indigo-500 
                            placeholder-gray-200 appearance-none">
                    
                    <!-- Nút tìm -->
                    <button type="submit"
                            class="appearance-none px-5 py-2 font-semibold text-black bg-black 
                            border border-gray-600 rounded-md shadow-md pr-10
                            focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <span>{{ __('Search') }}</span>
                    </button>
                </form>
            </div>


            <!-- Bảng danh sách -->
            <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg ring-1 ring-gray-200 dark:ring-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-center">
                    <thead class="bg-indigo-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Tên</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Ngày tham gia</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Ghi chú</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Vai trò</th>
                            <th class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Hành động</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($users as $user)
                            <tr class="hover:bg-indigo-50 dark:hover:bg-gray-700 transition">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->id }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $user->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $user->email }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300 {{ $user->notes ? 'bg-yellow-50 font-medium' : '' }}">
                                    @if ($user->notes)
                                        {{ \Illuminate\Support\Str::limit($user->notes, 20, '...') }}
                                    @else
                                           —
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if ($user->is_admin)
                                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                            Admin
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100">
                                            User
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-center space-x-2">
                                        <!-- Nút Edit -->
                                        <a href="{{ route('admin.edit', $user->id) }}" 
                                           class="px-3 py-1 text-xs font-semibold rounded-md text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
                                            {{ __('Edit') }}
                                        </a>

                                        <!-- Nút Delete -->
                                        <form action="{{ route('admin.destroy', $user->id) }}" method="POST" 
                                              onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này không?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-3 py-1 text-xs font-semibold rounded-md text-white bg-red-600 hover:bg-red-700 shadow-sm transition">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-300">
                                    {{ __('Không có người dùng nào được tìm thấy.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Phân trang -->
            <div class="mt-6 flex justify-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>
    <!-- ✨ Script Autocomplete -->
    <script>
        const input = document.getElementById('search-input');
        const results = document.getElementById('autocomplete-results');
        const filterSelect = document.querySelector('select[name="filter"]');

        input.addEventListener('input', async () => {
            const query = input.value.trim();
            const filter = filterSelect.value;
            if (!query) {
                results.innerHTML = '';
                results.classList.add('hidden');
                return;
            }

            const response = await fetch(`/admin/users/autocomplete?filter=${filter}&search=${encodeURIComponent(query)}`);
            const data = await response.json();

            if (data.length > 0) {
                results.innerHTML = data.map(item => `<li class="px-4 py-2 cursor-pointer hover:bg-indigo-100 dark:hover:bg-gray-700">${item}</li>`).join('');
                results.classList.remove('hidden');
            } else {
                results.innerHTML = '';
                results.classList.add('hidden');
            }
        });

        // Gán sự kiện chọn
        results.addEventListener('click', e => {
            if (e.target.tagName === 'LI') {
                input.value = e.target.textContent;
                results.classList.add('hidden');
            }
        });

        // Ẩn khi click ra ngoài
        document.addEventListener('click', e => {
            if (!results.contains(e.target) && e.target !== input) {
                results.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
