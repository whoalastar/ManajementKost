<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\ForgotPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan halaman forgot password
     */
    public function showForgotPasswordForm(): View
    {
        return view('admin.auth.forgot-password');
    }

    /**
     * Kirim email reset password
     */
    public function sendResetLinkEmail(ForgotPasswordRequest $request): RedirectResponse
    {
        $status = Password::broker('admins')->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', 'Link reset password telah dikirim ke email Anda.');
        }

        return back()->withErrors([
            'email' => $this->getStatusMessage($status),
        ])->withInput();
    }

    /**
     * Get status message in Indonesian
     */
    private function getStatusMessage(string $status): string
    {
        return match ($status) {
            Password::RESET_THROTTLED => 'Harap tunggu sebelum mencoba lagi.',
            Password::INVALID_USER => 'Email tidak terdaftar dalam sistem.',
            default => 'Terjadi kesalahan. Silakan coba lagi.',
        };
    }
}
