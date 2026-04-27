<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Display the admin login view.
     */
    public function showLogin(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle an admin login request.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate('admin');

        $request->session()->regenerate();

        if (Auth::guard('admin')->user()->role !== 'admin') {
            Auth::guard('admin')->logout();
            
            return redirect()->route('admin.login')->withErrors([
                'email' => 'Tài khoản này không có quyền truy cập trang quản trị.',
            ]);
        }

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated admin session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        return redirect()->route('admin.login');
    }
}
