<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use App\Models\Transaction;
class AdminController extends Controller
{
    /**
     * Hiển thị trang quản lý người dùng (Dashboard Admin).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $filter = $request->input('filter', 'name');

        // Query builder
        $query = User::query();

        if ($search) {
            $query->where($filter, 'LIKE', "%{$search}%")
                ->orderByRaw("
                    CASE 
                        WHEN LOWER($filter) LIKE ? THEN 1
                        WHEN LOWER($filter) LIKE ? THEN 2
                        WHEN LOWER($filter) LIKE ? THEN 3
                        ELSE 4 
                    END, $filter ASC
                ", [
                    strtolower("{$search}%"),
                    strtolower("% {$search}%"),
                    strtolower("%{$search}%")
                ]);
        }

        // Lấy danh sách người dùng, phân trang 20 user mỗi trang
        $users = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        // Trả về view với danh sách user
        return view('admin.users', compact('users', 'search', 'filter'));
    }

    /**
     * Trả về thông tin chi tiết người dùng (dùng cho xem trước qua AJAX).
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Gợi ý người dùng theo tên, email, ID (AJAX).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function suggestions(Request $request): JsonResponse
    {
        $query = trim($request->input('q'));
        $filter = $request->input('filter', 'name');

        // Đảm bảo filter hợp lệ
        if (!in_array($filter, ['id', 'name', 'email'])) {
            $filter = 'name';
        }

        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $users = User::select('id', 'name', 'email')
            ->where($filter, 'like', "%{$query}%")
            ->orderByRaw("
                CASE 
                    WHEN LOWER({$filter}) LIKE ? THEN 1 
                    WHEN LOWER({$filter}) LIKE ? THEN 2 
                    WHEN LOWER({$filter}) LIKE ? THEN 3 
                    ELSE 4 
                END, {$filter} ASC
            ", [
                strtolower("{$query}%"),
                strtolower("% {$query}%"),
                strtolower("%{$query}%")
            ])
            ->limit(30)
            ->get();

        return response()->json($users);
    }

    /**
     * Hiển thị form chỉnh sửa người dùng.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user): View
    {
        return view('admin.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin người dùng.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
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
        return redirect()
            ->route('admin.users')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Xóa người dùng.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        // Không cho phép xóa chính Admin hiện tại
        if (auth()->id() === $user->id) {
            return redirect()
                ->route('admin.users')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users')
            ->with('success', 'User deleted successfully.');
    }
}