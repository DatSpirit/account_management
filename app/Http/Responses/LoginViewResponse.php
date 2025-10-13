<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Laravel\Fortify\Contracts\LoginViewResponse as LoginViewResponseContract;

/**
 * Triển khai interface LoginViewResponse của Fortify để trả về view đăng nhập.
 */
class LoginViewResponse implements LoginViewResponseContract
{
    /**
     * Tạo một phản hồi HTTP đại diện cho đối tượng.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        // Trả về view đăng nhập của bạn (thường là resources/views/auth/login.blade.php)
        return view('auth.login');
    }
}
