<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Auth\ResetPasswordRequest;
use App\Models\Tenant;
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
        return view('tenant.auth.reset-password', [
            'token' => $token,
            'email' => $request->input('email'),
        ]);
    }

    /**
     * Proses reset password
     */
    public function reset(ResetPasswordRequest $request): RedirectResponse
    {
        $status = Password::broker('tenants')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (Tenant $tenant, string $password) {
                $tenant->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($tenant));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('tenant.login')
                ->with('status', 'Password berhasil direset. Silakan login dengan password baru.');
        }

        return back()->withErrors([
            'email' => $this->getStatusMessage($status),
        ])->withInput($request->only('email'));
    }

    private function getStatusMessage(string $status): string
    {
        return match ($status) {
            Password::INVALID_TOKEN => 'Token reset password tidak valid atau sudah kedaluwarsa.',
            Password::INVALID_USER => 'Email tidak terdaftar.',
            Password::RESET_THROTTLED => 'Harap tunggu sebelum mencoba lagi.',
            default => 'Terjadi kesalahan. Silakan coba lagi.',
        };
    }
}
