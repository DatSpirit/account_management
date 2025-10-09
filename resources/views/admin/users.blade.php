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
                <form method="GET" action="{{ route('admin.users') }}"
                    class="mt-4 sm:mt-0 flex items-center space-x-2 relative">
                    <!-- Bộ lọc -->
                    <select name="filter"
                        class="appearance-none px-7 py-2 font-semibold text-black bg-black 
                       border border-gray-600 rounded-md shadow-md pr-10
                       focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="name" {{ request('filter') === 'name' ? 'selected' : '' }}>Tên</option>
                        <option value="email" {{ request('filter') === 'email' ? 'selected' : '' }}>Email</option>
                        <option value="id" {{ request('filter') === 'id' ? 'selected' : '' }}>ID</option>
                    </select>

                    <!-- Ô tìm kiếm -->

                    <div class="relative w-72">
                        <input id="search-input" type="text" name="search" placeholder="Nhập từ khóa tìm kiếm..."
                            value="{{ request('search') }}"
                            class="pl-10 pr-4 py-2 w-full font-medium text-black bg-black 
                            border border-gray-600 rounded-md shadow-md
                            focus:outline-none focus:ring-2 focus:ring-indigo-500 
                            placeholder-gray-200 appearance-none">
                        <!-- Dropdown gợi ý -->
                        <ul id="suggestions"
                            class="absolute z-50 mt-1 w-full bg-gray-900 border border-gray-700 rounded-md hidden text-left">
                        </ul>
                    </div>
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
            <div
                class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg ring-1 ring-gray-200 dark:ring-gray-700">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-center">
                    <thead class="bg-indigo-100 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-xs font-semibold uppercase tracking-wider
                                {{ $filter === 'id' ? 'bg-indigo-100 dark:bg-indigo-700 text-indigo-800 dark:text-indigo-100' 
                                : 'text-gray-700 dark:text-gray-200' }}">
                                ID
                            </th>

                            <th
                                class="px-6 py-3 text-xs font-semibold uppercase tracking-wider
                                {{ $filter === 'name' ? 'bg-indigo-100 dark:bg-indigo-700 text-indigo-800 dark:text-indigo-100' 
                                : 'text-gray-700 dark:text-gray-200' }}">
                                Tên
                            </th>

                            <th
                                class="px-6 py-3 text-xs font-semibold uppercase tracking-wider
                                {{ $filter === 'email' ? 'bg-indigo-100 dark:bg-indigo-700 text-indigo-800 dark:text-indigo-100' 
                                : 'text-gray-700 dark:text-gray-200' }}">
                                Email
                            </th>

                            <th
                                class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                Ngày tham gia</th>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                Ghi chú</th>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                Vai trò</th>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                Hành động</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($users as $user)
                            <tr class="hover:bg-indigo-50 dark:hover:bg-gray-700 transition">
                                <td
                                    class="px-6 py-4 text-sm {{ $filter === 'id' ? 'bg-indigo-50 dark:bg-indigo-900 font-semibold' : 'text-gray-500 dark:text-gray-300' }}">
                                    {{ $user->id }}</td>
                                <td
                                    class="px-6 py-4 text-sm {{ $filter === 'name' ? 'bg-indigo-50 dark:bg-indigo-900 font-semibold' : 'text-gray-500 dark:text-gray-300' }}">
                                    {{ $user->name }}</td>
                                <td
                                    class="px-6 py-4 text-sm {{ $filter === 'email' ? 'bg-indigo-50 dark:bg-indigo-900 font-semibold' : 'text-gray-500 dark:text-gray-300' }}">
                                    {{ $user->email }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                    {{ $user->created_at->format('d/m/Y') }}</td>
                                <td
                                    class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300 {{ $user->notes ? 'bg-yellow-50 font-medium' : '' }}">
                                    @if ($user->notes)
                                        {{ \Illuminate\Support\Str::limit($user->notes, 20, '...') }}
                                    @else
                                        —
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    @if ($user->is_admin)
                                        <span
                                            class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                            Admin
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 inline-flex text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100">
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
                {{ $users->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
    <!-- Script Autocomplete Tối ưu -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('search-input');
            const suggestionsBox = document.getElementById('suggestions');
            const filterSelect = document.getElementById('filter-select');
            let debounceTimer;

            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                const filter = filterSelect.value;

                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    if (query.length < 2) {
                        suggestionsBox.classList.add('hidden');
                        suggestionsBox.innerHTML = '';
                        return;
                    }

                    // Gọi API suggestions
                    fetch(
                            `/admin/users/suggestions?q=${encodeURIComponent(query)}&filter=${filter}`)
                        .then(response => response.json())
                        .then(data => {
                            suggestionsBox.innerHTML = '';

                            if (data.length === 0) {
                                suggestionsBox.classList.add('hidden');
                                return;
                            }

                            // Hiển thị gợi ý
                            data.forEach(user => {
                                const li = document.createElement('li');
                                li.className =
                                    "px-4 py-2 cursor-pointer hover:bg-gray-800 hover:text-white transition duration-150";
                                li.textContent =
                                    filter === 'email' ? user.email :
                                    filter === 'id' ? `#${user.id}` :
                                    user.name + ' — ' + user.email;

                                //  Khi chọn → điền vào ô input
                                li.addEventListener('click', () => {
                                    searchInput.value =
                                        filter === 'email' ? user.email :
                                        filter === 'id' ? user.id :
                                        user.name;
                                    suggestionsBox.classList.add('hidden');
                                });

                                suggestionsBox.appendChild(li);
                            });

                            suggestionsBox.classList.remove('hidden');
                        })
                        .catch(() => suggestionsBox.classList.add('hidden'));
                }, 300);
            });

            //  Ẩn khi click ra ngoài
            document.addEventListener('click', (e) => {
                if (!suggestionsBox.contains(e.target) && e.target !== searchInput) {
                    suggestionsBox.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>
