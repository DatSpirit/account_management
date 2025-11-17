<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Hiển thị trang Trung tâm Trợ giúp (Help Center).
     */
    public function helpCenter()
    {
        // Giả định bạn có thể truyền dữ liệu FAQs hoặc Danh mục tại đây
        $faqs = [
            ['question' => 'Làm thế nào để tạo đơn hàng mới?', 'answer' => 'Chi tiết hướng dẫn...'],
            ['question' => 'Quy trình xử lý giao dịch là gì?', 'answer' => 'Chi tiết quy trình...'],
            // Thêm nhiều FAQs khác
        ];

        return view('support.help_center', compact('faqs'));
    }

    /**
     * Hiển thị trang Liên hệ Hỗ trợ (Contact Support).
     */
    public function contactSupport()
    {
        return view('support.contact_support');
    }

    /**
     * Xử lý gửi biểu mẫu liên hệ.
     */
    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // TODO: Xử lý logic gửi email thông báo hoặc lưu vào database

        return back()->with('success', 'Yêu cầu hỗ trợ của bạn đã được gửi thành công!');
    }
}