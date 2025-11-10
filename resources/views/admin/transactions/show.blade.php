<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.transactions.index') }}" class="text-gray-600 hover:text-gray-900">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Chi tiết giao dịch #{{ $transaction->order_code }}
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Thông tin chính --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Trạng thái & Thời gian --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Trạng thái giao dịch</h3>
                        
                        <div class="flex items-center justify-between mb-6">
                            @php
                                $statusConfig = [
                                    'success' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'label' => 'Thanh toán thành công', 'icon' => '✓'],
                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'label' => 'Đang chờ xử lý', 'icon' => '⏱'],
                                    'failed' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'label' => 'Thanh toán thất bại', 'icon' => '✗'],
                                    'cancelled' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'label' => 'Đã hủy', 'icon' => '⊘'],
                                ];
                                $config = $statusConfig[$transaction->status] ?? $statusConfig['pending'];
                            @endphp
                            
                            <span class="inline-flex items-center px-6 py-3 rounded-lg text-base font-semibold {{ $config['bg'] }} {{ $config['text'] }}">
                                <span class="mr-2 text-xl">{{ $config['icon'] }}</span>
                                {{ $config['label'] }}
                            </span>
                            
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Mã đơn hàng</p>
                                <p class="text-2xl font-bold text-gray-900">#{{ $transaction->order_code }}</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <dl class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Ngày tạo</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</dd>
                                    <dd class="mt-1 text-xs text-gray-500">{{ $transaction->created_at->diffForHumans() }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Cập nhật lần cuối</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $transaction->updated_at->format('d/m/Y H:i:s') }}</dd>
                                    <dd class="mt-1 text-xs text-gray-500">{{ $transaction->updated_at->diffForHumans() }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {{-- Thông tin thanh toán --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin thanh toán</h3>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                <span class="text-sm font-medium text-gray-600">Số tiền giao dịch</span>
                                <span class="text-2xl font-bold text-indigo-600">
                                    {{ number_format($transaction->amount, 0, ',', '.') }} đ
                                </span>
                            </div>
                            
                            @if($transaction->product)
                            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                <span class="text-sm font-medium text-gray-600">Sản phẩm</span>
                                <span class="text-sm text-gray-900 font-semibold">{{ $transaction->product->name }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-200">
                                <span class="text-sm font-medium text-gray-600">Giá sản phẩm</span>
                                <span class="text-sm text-gray-900">{{ number_format($transaction->product->price, 0, ',', '.') }} đ</span>
                            </div>
                            @endif
                            
                            <div class="flex justify-between items-start py-3">
                                <span class="text-sm font-medium text-gray-600">Mô tả</span>
                                <span class="text-sm text-gray-900 text-right max-w-md">{{ $transaction->description }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Thông tin người dùng --}}
                    @if($transaction->user)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Thông tin khách hàng</h3>
                        
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-600 font-bold text-xl">
                                    {{ strtoupper(substr($transaction->user->name, 0, 2)) }}
                                </span>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">{{ $transaction->user->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $transaction->user->email }}</p>
                            </div>
                        </div>

                        <div class="border-t border-gray-200 pt-4">
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">User ID</dt>
                                    <dd class="text-sm text-gray-900">{{ $transaction->user->id }}</dd>
                                </div>
                                @if($transaction->user->phone)
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Số điện thoại</dt>
                                    <dd class="text-sm text-gray-900">{{ $transaction->user->phone }}</dd>
                                </div>
                                @endif
                                <div class="flex justify-between">
                                    <dt class="text-sm font-medium text-gray-500">Ngày đăng ký</dt>
                                    <dd class="text-sm text-gray-900">{{ $transaction->user->created_at->format('d/m/Y') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('admin.users.show', $transaction->user->id) }}" 
                               class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900">
                                Xem hồ sơ khách hàng
                                <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    
                    {{-- Thao tác nhanh --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Thao tác</h3>
                        
                        <div class="space-y-3">
                            @if($transaction->status == 'pending')
                            <form action="{{ route('admin.transactions.update-status', $transaction->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="success">
                                <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition">
                                    ✓ Xác nhận thành công
                                </button>
                            </form>
                            
                            <form action="{{ route('admin.transactions.update-status', $transaction->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="failed">
                                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">
                                    ✗ Đánh dấu thất bại
                                </button>
                            </form>
                            @endif
                            
                            <button onclick="window.print()" class="w-full px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition">
                                🖨️ In hóa đơn
                            </button>
                            
                            <a href="{{ route('admin.transactions.index') }}" class="block w-full px-4 py-2 text-center bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                                ← Quay lại danh sách
                            </a>
                        </div>
                    </div>

                    {{-- Timeline --}}
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Lịch sử thay đổi</h3>
                        
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <li>
                                    <div class="relative pb-8">
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></span>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-900 font-medium">Giao dịch được tạo</p>
                                                    <p class="text-xs text-gray-500">Trạng thái: Pending</p>
                                                </div>
                                                <div class="text-right text-xs text-gray-500 whitespace-nowrap">
                                                    {{ $transaction->created_at->format('H:i d/m') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                
                                @if($transaction->updated_at != $transaction->created_at)
                                <li>
                                    <div class="relative pb-8">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                @php
                                                    $iconColor = match($transaction->status) {
                                                        'success' => 'bg-green-500',
                                                        'failed' => 'bg-red-500',
                                                        'cancelled' => 'bg-gray-500',
                                                        default => 'bg-yellow-500'
                                                    };
                                                @endphp
                                                <span class="h-8 w-8 rounded-full {{ $iconColor }} flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm text-gray-900 font-medium">Cập nhật trạng thái</p>
                                                    <p class="text-xs text-gray-500">Trạng thái: {{ ucfirst($transaction->status) }}</p>
                                                </div>
                                                <div class="text-right text-xs text-gray-500 whitespace-nowrap">
                                                    {{ $transaction->updated_at->format('H:i d/m') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</x-app-layout>