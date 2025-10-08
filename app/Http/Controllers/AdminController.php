<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    /**
     * Hiển thị trang quản lý người dùng (Dashboard Admin).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {

        $search = $request->input('search');
        $filter = $request->input('filter', 'name');

        // Query builder
        $query = User::query();

  
        if ($search) {
            $query->where($filter, 'LIKE', "%{$search}%"); 
        }


        // Lấy danh sách người dùng, phân trang 20 user mỗi trang
        $users = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Trả về view với danh sách user
        return view('admin.users', compact('users', 'search', 'filter'));
    
    }
    /**
     * API Gợi ý tìm kiếm (Autocomplete)
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $filter = $request->input('filter', 'name');
        $term = $request->input('term', '');

        $results = User::where($filter, 'LIKE', "%{$term}%")
            ->take(5)
            ->get([$filter])
            ->pluck($filter);

        return response()->json($results);
    }

    
    /**
     * Hiển thị form chỉnh sửa người dùng.
     */
    public function edit(User $user): View
    {
        return view('admin.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin người dùng.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // Validate dữ liệu nhập vào
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'notes' => 'nullable|string|max:1000',
        ]);

        // Cập nhật user
        $user->update($validated);

        // Chuyển hướng về danh sách user với thông báo
        return redirect()->route('admin.users')
                         ->with('success', 'User updated successfully.');
    }

    /**
     * Xóa người dùng.
     */
    public function destroy(User $user): RedirectResponse
    {
        // Không cho phép xóa chính Admin hiện tại
        if (auth()->id() === $user->id) {
            return redirect()->route('admin.users')
                             ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users')
                         ->with('success', 'User deleted successfully.');
    }
}
