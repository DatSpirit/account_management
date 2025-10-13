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
                        {{ __('Quản lý tất cả người dùng đã đăng ký trong hệ thống.') }}
                    </h3>
                </div>

                <!-- Search + Filter -->
                <form method="GET" action="{{ route('admin.users') }}"
                    class="mt-4 sm:mt-0 flex items-center space-x-2 relative">

                    <!-- Bộ lọc -->
                    <select id="filter-select" name="filter"
                        class="appearance-none px-7 py-2 font-semibold 
                                bg-white text-gray-800 border border-gray-300 rounded-md shadow-md pr-10
                                focus:outline-none focus:ring-2 focus:ring-indigo-500
                                dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600">
                        <option value="name" {{ request('filter') === 'name' ? 'selected' : '' }}>Tên</option>
                        <option value="email" {{ request('filter') === 'email' ? 'selected' : '' }}>Email</option>
                        <option value="id" {{ request('filter') === 'id' ? 'selected' : '' }}>ID</option>
                    </select>

                    <!-- Ô tìm kiếm -->
                    <div class="relative w-72">
                        <input id="search-input" type="text" name="search" placeholder="Nhập từ khóa tìm kiếm..."
                            value="{{ request('search') }}"
                            class="pl-10 pr-4 py-2 w-full font-medium
                                     bg-white text-gray-800 border border-gray-300 rounded-md shadow-md
                                     focus:outline-none focus:ring-2 focus:ring-indigo-500 placeholder-gray-400
                                     dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:placeholder-gray-400">

                        <!-- Dropdown gợi ý -->
                        <ul id="suggestions"
                            class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-800 
                                border border-gray-300 dark:border-gray-700 rounded-md 
                                hidden text-left text-gray-800 dark:text-gray-100 shadow-lg">
                        </ul>
                    </div>

                    <!-- Nút tìm -->
                    <button type="submit"
                        class="px-5 py-2 font-semibold rounded-md shadow-md 
                                bg-indigo-600 text-white hover:bg-indigo-700
                                focus:outline-none focus:ring-2 focus:ring-indigo-500
                                dark:bg-indigo-500 dark:hover:bg-indigo-400">
                        {{ __('Search') }}
                    </button>
                </form>
            </div>

            <!-- Bảng danh sách -->
            <div
                class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow-lg ring-1 ring-gray-200 dark:ring-gray-700">
                <table class=" w-full divide-y divide-gray-200 dark:divide-gray-700 text-center">
                    <thead class="bg-indigo-100 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-xs font-semibold uppercase tracking-wider 
                                        {{ $filter === 'id'
                                            ? 'bg-indigo-100 dark:bg-indigo-700 text-indigo-800 dark:text-indigo-100'
                                            : 'text-gray-700 dark:text-gray-200' }}">
                                ID
                            </th>
                            <th
                                class="px-6 py-3 text-xs font-semibold uppercase tracking-wider 
                                        {{ $filter === 'name'
                                            ? 'bg-indigo-100 dark:bg-indigo-700 text-indigo-800 dark:text-indigo-100'
                                            : 'text-gray-700 dark:text-gray-200' }}">
                                Tên
                            </th>
                            <th
                                class="px-6 py-3 text-xs font-semibold uppercase tracking-wider 
                                        {{ $filter === 'email'
                                            ? 'bg-indigo-100 dark:bg-indigo-700 text-indigo-800 dark:text-indigo-100'
                                            : 'text-gray-700 dark:text-gray-200' }}">
                                Email
                            </th>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                Ngày tham gia
                            </th>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                Ghi chú
                            </th>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                Vai trò
                            </th>
                            <th
                                class="px-6 py-3 text-xs font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                Hành động
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($users as $user)
                            <tr class="hover:bg-indigo-50 dark:hover:bg-gray-700 transition">
                                <td
                                    class="px-6 py-4 text-sm {{ $filter === 'id' ? 'bg-indigo-50 dark:bg-indigo-900 font-semibold' : 'text-gray-500 dark:text-gray-300' }}">
                                    {{ $user->id }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm {{ $filter === 'name' ? 'bg-indigo-50 dark:bg-indigo-900 font-semibold' : 'text-gray-500 dark:text-gray-300' }}">
                                    {{ $user->name }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm {{ $filter === 'email' ? 'bg-indigo-50 dark:bg-indigo-900 font-semibold' : 'text-gray-500 dark:text-gray-300' }}">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                    {{ $user->created_at->format('d/m/Y') }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300 {{ $user->notes ? 'bg-yellow-50 dark:bg-yellow-900 font-medium' : '' }}">
                                    @if ($user->notes)
                                        {{ \Illuminate\Support\Str::limit($user->notes, 20, '...') }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if ($user->is_admin)
                                        <span
                                            class="px-3 py-1 inline-flex text-xs font-semibold rounded-full 
                                                     bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                            Admin
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 inline-flex text-xs font-semibold rounded-full 
                                                     bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100">
                                            User
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center space-x-2">

                                        <!-- NÚT Xem chi tiết (Đã sửa) -->
                                        <button type="button" data-id="{{ $user->id }}"
                                            title="Xem chi tiết người dùng"
                                            class="view-btn p-2 text-xs font-semibold rounded-full text-white 
                                            bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400 shadow-sm transition flex items-center justify-center aspect-square">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-eye">
                                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </button>

                                        <!-- Nút Edit -->
                                        <a href="{{ route('admin.edit', $user->id) }}" title="Chỉnh sửa người dùng"
                                            class="p-2 text-xs font-semibold rounded-full text-white 
                                                    bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-400 shadow-sm transition flex items-center justify-center aspect-square">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="lucide lucide-pencil">
                                                <path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z" />
                                            </svg>
                                        </a>

                                        <!-- Nút Delete -->
                                        <form action="{{ route('admin.destroy', $user->id) }}" method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này không?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Xóa người dùng"
                                                class="p-2 text-xs font-semibold rounded-full text-white 
                                                                    bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-400 shadow-sm transition flex items-center justify-center aspect-square">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-trash-2">
                                                    <path d="M3 6h18" />
                                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                                    <line x1="10" x2="10" y1="11" y2="17" />
                                                    <line x1="14" x2="14" y1="11"
                                                        y2="17" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-300">
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

    <!-- Modal xem chi tiết với thông tin đầy đủ -->
    <div id="previewModal" class="hidden fixed inset-0 flex items-center justify-center z-50">
        <!-- Overlay -->
        <div class="absolute inset-0 bg-gray-900 opacity-70 transition-opacity"
            onclick="document.getElementById('previewModal').classList.add('hidden');"></div>

        <!-- Modal Content -->
        <div
            class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-1/2 max-w-md p-6 z-10 transform transition-all duration-300">
            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 border-b pb-2">
                Thông tin chi tiết người dùng
            </h3>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between items-center pb-2 border-b border-gray-100 dark:border-gray-700">
                    <p class="font-medium text-gray-600 dark:text-gray-400">ID:</p>
                    <span id="preview-id" class="font-semibold text-gray-800 dark:text-gray-100"></span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-gray-100 dark:border-gray-700">
                    <p class="font-medium text-gray-600 dark:text-gray-400">Tên:</p>
                    <span id="preview-name" class="font-semibold text-gray-800 dark:text-gray-100"></span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-gray-100 dark:border-gray-700">
                    <p class="font-medium text-gray-600 dark:text-gray-400">Email:</p>
                    <span id="preview-email" class="font-semibold text-gray-800 dark:text-gray-100"></span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-gray-100 dark:border-gray-700">
                    <p class="font-medium text-gray-600 dark:text-gray-400">Vai trò Admin:</p>
                    <span id="preview-is-admin" class="font-semibold"></span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-gray-100 dark:border-gray-700">
                    <p class="font-medium text-gray-600 dark:text-gray-400">Ngày hết hạn TK:</p>
                    <span id="preview-expires-at" class="font-semibold"></span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-gray-100 dark:border-gray-700">
                    <p class="font-medium text-gray-600 dark:text-gray-400">Xác thực 2 yếu tố (2FA):</p>
                    <span id="preview-2fa" class="font-semibold"></span>
                </div>
                <div class="flex justify-between items-center pb-2 border-b border-gray-100 dark:border-gray-700">
                    <p class="font-medium text-gray-600 dark:text-gray-400">Ngày tham gia:</p>
                    <span id="preview-created" class="font-semibold text-gray-800 dark:text-gray-100"></span>
                </div>
                <div class="flex justify-between items-center pb-2">
                    <p class="font-medium text-gray-600 dark:text-gray-400">Cập nhật gần nhất:</p>
                    <span id="preview-updated" class="font-semibold text-gray-800 dark:text-gray-100"></span>
                </div>
            </div>

            <div class="mt-4 flex justify-center">
                <button id="closeModal"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-lg transition duration-150 ease-in-out">
                    Đóng
                </button>
            </div>
        </div>
    </div>

    <!-- Script xem chi tiết -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.view-btn');
            const modal = document.getElementById('previewModal');
            const closeModal = document.getElementById('closeModal');
            const eyeIconSvg =
                '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>';


            // Hàm helper để định dạng thời gian
            const formatDateTime = (isoString) => {
                if (!isoString) return 'N/A';
                try {
                    const date = new Date(isoString);
                    // Định dạng theo chuẩn Việt Nam (vi-VN)
                    // Bao gồm ngày, tháng, năm, giờ, phút, giây
                    return date.toLocaleString('vi-VN', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: false // Dùng định dạng 24h
                    });
                } catch (e) {
                    console.error("Lỗi định dạng ngày:", e);
                    return isoString; // Trả về chuỗi gốc nếu lỗi
                }
            };


            // Hiển thị thông tin người dùng trong Modal
            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;

                    // Lưu trữ nội dung gốc (chỉ SVG icon)
                    const originalContent = btn.innerHTML; 
                    // Lưu trữ các class gốc (ngoại trừ loading/error)
                    const originalClasses = 'view-btn p-2 text-xs font-semibold rounded-full text-white bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400 shadow-sm transition flex items-center justify-center aspect-square';
                    
                    // Thêm loading state
                    btn.innerHTML =
                        '<svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
                    btn.disabled = true;

                    fetch(`/admin/users/${id}/show`)
                        .then(res => {
                            // Kiểm tra HTTP status code (404, 500, v.v.)
                            if (!res.ok) {
                                throw new Error(
                                    `Lỗi HTTP ${res.status}: Không thể tải dữ liệu người dùng.`
                                );
                            }
                            return res.json();
                        })
                        .then(user => {
                            // *** CẬP NHẬT: Thêm kiểm tra an toàn cho các thuộc tính JSON ***
                            document.getElementById('preview-id').textContent = user.id ||
                                'N/A';
                            document.getElementById('preview-name').textContent = user.name ||
                                'N/A';
                            document.getElementById('preview-email').textContent = user.email ||
                                'N/A';

                            // Trạng thái Admin
                            const isAdmin = user.is_admin || false;
                            const isAdminSpan = document.getElementById('preview-is-admin');
                            isAdminSpan.textContent = isAdmin ? 'Có' : 'Không';
                            isAdminSpan.className = isAdmin ?
                                'font-bold text-green-600 dark:text-green-400' :
                                'font-bold text-red-600 dark:text-red-400';

                            // Ngày hết hạn
                            const expiresAtValue = user.expires_at; // Lấy giá trị gốc
                            const expiresAt = expiresAtValue ? expiresAtValue :
                                'Không giới hạn';
                            const expiresAtSpan = document.getElementById('preview-expires-at');
                            expiresAtSpan.textContent = expiresAt;
                            expiresAtSpan.className = expiresAt !== 'Không giới hạn' ?
                                'font-bold text-green-600 dark:text-green-400' :
                                'font-bold text-red-600 dark:text-red-400';

                            // 2FA
                            const twoFaValue = user.two_factor_enabled; // Lấy giá trị gốc
                            const twoFa = twoFaValue ? 'Có' : 'Không';
                            const twoFaSpan = document.getElementById('preview-2fa');
                            twoFaSpan.textContent = twoFa;
                            twoFaSpan.className = twoFaValue ?
                                'font-bold text-green-600 dark:text-green-400' :
                                'font-bold text-red-600 dark:text-red-400';

                            document.getElementById('preview-created').textContent =
                                formatDateTime(user.created_at);
                            document.getElementById('preview-updated').textContent =
                                formatDateTime(user.updated_at);


                            // Hiển thị modal sau khi load dữ liệu thành công
                            modal.classList.remove('hidden');

                            // *** KHÔI PHỤC NÚT SAU KHI THÀNH CÔNG ***
                            btn.innerHTML = originalContent;
                            btn.disabled = false;
                            btn.className = originalClasses;
                           

                        })
                        .catch(error => {
                            // Hiển thị lỗi ra console để debug
                            console.error('LỖI TẢI THÔNG TIN NGƯỜI DÙNG:', error);

                            // Đặt class lỗi và nội dung lỗi tạm thời
                            btn.innerHTML = '❌';
                            btn.className = originalClasses.replace(/bg-indigo-\d{3,4}|hover:bg-indigo-\d{3,4}|dark:bg-indigo-\d{3,4}|dark:hover:bg-indigo-\d{3,4}/g, 'bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-400');
                            
                            // Khôi phục nút sau 1.5 giây
                            setTimeout(() => {
                                btn.innerHTML = originalContent;
                                btn.className = originalClasses;
                                btn.disabled = false;
                            }, 1500);
                        });
                });
            });

            closeModal.addEventListener('click', () => modal.classList.add('hidden'));
            // Đóng Modal khi click ra bên ngoài overlay
            modal.addEventListener('click', e => {
                if (e.target.id === 'previewModal') {
                    modal.classList.add('hidden');
                }
            });

            // Xử lý đóng bằng phím ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                }
            });
        });
    </script>

    <!-- Script Autocomplete  -->
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

                    fetch(
                            `/admin/users/suggestions?q=${encodeURIComponent(query)}&filter=${filter}`
                            )
                        .then(response => response.json())
                        .then(data => {
                            suggestionsBox.innerHTML = '';

                            if (data.length === 0) {
                                suggestionsBox.classList.add('hidden');
                                return;
                            }

                            data.forEach(user => {
                                const li = document.createElement('li');
                                li.className =
                                    "px-4 py-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition";
                                li.textContent = filter === 'email' ? user.email :
                                    filter === 'id' ? `#${user.id}` :
                                    `${user.name} — ${user.email}`;

                                li.addEventListener('click', () => {
                                    searchInput.value = filter === 'email' ?
                                        user.email :
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

            document.addEventListener('click', (e) => {
                if (!suggestionsBox.contains(e.target) && e.target !== searchInput) {
                    suggestionsBox.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>
