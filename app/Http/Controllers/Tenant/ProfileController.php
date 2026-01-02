<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Auth\ChangePasswordRequest;
use App\Services\TenantPortalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        private TenantPortalService $portalService
    ) {}

    /**
     * Display tenant profile
     */
    public function index(): View|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();
        $data = $this->portalService->getProfile($tenant);

        if (request()->wantsJson()) {
            return response()->json($data);
        }

        return view('tenant.profile.index', $data);
    }

    /**
     * Update profile (limited fields: email, phone)
     */
    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();

        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:tenants,email,' . $tenant->id,
            'phone' => 'required|string|max:20',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'phone.required' => 'Nomor HP wajib diisi.',
        ]);

        $this->portalService->updateProfile($tenant, $validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Profil berhasil diperbarui.']);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Show change password form
     */
    public function showChangePasswordForm(): View
    {
        return view('tenant.profile.change-password');
    }

    /**
     * Change password
     */
    public function changePassword(ChangePasswordRequest $request): RedirectResponse|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();

        $tenant->update([
            'password' => Hash::make($request->password),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Password berhasil diubah.']);
        }

        return back()->with('success', 'Password berhasil diubah.');
    }
}
