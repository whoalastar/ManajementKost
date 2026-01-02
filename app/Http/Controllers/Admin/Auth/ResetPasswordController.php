<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\ResetPasswordRequest;
use App\Models\Admin;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    /**
     * Tampilkan halaman reset password
     */
    public function showResetForm(Request $request, string $token): View
    {
        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->input('email'),
        ]);
    }

    /**
     * Proses reset password
     */
    public function reset(ResetPasswordRequest $request): RedirectResponse
    {
        $status = Password::broker('admins')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Admin $admin, string $password) {
                $admin->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($admin));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('admin.login')
                ->with('status', 'Password berhasil direset. Silakan login dengan password baru.');
        }

        return back()->withErrors([
            'email' => $this->getStatusMessage($status),
        ])->withInput($request->only('email'));
    }

    /**
     * Get status message in Indonesian
     */
    private function getStatusMessage(string $status): string
    {
        return match ($status) {
            Password::INVALID_TOKEN => 'Token reset password tidak valid atau sudah kedaluwarsa.',
            Password::INVALID_USER => 'Email tidak terdaftar dalam sistem.',
            Password::RESET_THROTTLED => 'Harap tunggu sebelum mencoba lagi.',
            default => 'Terjadi kesalahan. Silakan coba lagi.',
        };
    }
}
