<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

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
 * Gợi ý người dùng theo tên, email, ID (AJAX)
 */
public function suggestions(Request $request)
{
    $query = $request->input('q', '');
    $filter = $request->input('filter', 'name'); // ID, name hoặc email

    if (strlen($query) < 2) {
        return response()->json([]); // chỉ gợi ý khi gõ >= 2 ký tự
    }

    $users = User::query();

    // Chỉ tìm trong cột được chọn (name, email, id)
    if ($filter === 'email') {
        $users->where('email', 'LIKE', "%{$query}%");
    } elseif ($filter === 'id') {
        $users->where('id', $query);
    } else {
        $users->where('name', 'LIKE', "%{$query}%");
    }

    $results = $users->take(5)->get(['id', 'name', 'email']);

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
