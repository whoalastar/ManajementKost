<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        private SettingService $settingService
    ) {}

    /**
     * Settings index
     */
    public function index(): View
    {
        $profile = $this->settingService->getProfileSettings();
        $payment = $this->settingService->getPaymentSettings();
        $invoice = $this->settingService->getInvoiceSettings();
        $email = $this->settingService->getEmailSettings();
        $rules = $this->settingService->getKostRules();

        return view('admin.settings.index', compact('profile', 'payment', 'invoice', 'email', 'rules'));
    }

    /**
     * Update profile settings
     */
    public function updateProfile(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'kost_name' => 'required|string|max:255',
            'kost_address' => 'nullable|string',
            'kost_phone' => 'nullable|string|max:20',
            'kost_email' => 'nullable|email|max:255',
            'kost_description' => 'nullable|string',
        ]);

        $this->settingService->updateProfileSettings($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Pengaturan profil berhasil disimpan']);
        }

        return back()->with('success', 'Pengaturan profil berhasil disimpan.');
    }

    /**
     * Upload logo
     */
    public function uploadLogo(Request $request): RedirectResponse|JsonResponse
    {
        $request->validate([
            'logo' => 'required|image|max:2048',
        ]);

        $path = $this->settingService->uploadLogo($request->file('logo'));

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Logo berhasil diunggah', 'path' => $path]);
        }

        return back()->with('success', 'Logo berhasil diunggah.');
    }

    /**
     * Update payment settings
     */
    public function updatePayment(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'bank_name' => 'nullable|string|max:255',
            'bank_account_name' => 'nullable|string|max:255',
            'bank_account_number' => 'nullable|string|max:50',
            'payment_instructions' => 'nullable|string',
        ]);

        $this->settingService->updatePaymentSettings($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Pengaturan pembayaran berhasil disimpan']);
        }

        return back()->with('success', 'Pengaturan pembayaran berhasil disimpan.');
    }

    /**
     * Update invoice settings
     */
    public function updateInvoice(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'invoice_prefix' => 'nullable|string|max:10',
            'invoice_footer' => 'nullable|string',
            'invoice_notes' => 'nullable|string',
            'invoice_due_days' => 'nullable|integer|min:1|max:28',
            'auto_generate_invoice' => 'nullable|boolean',
        ]);

        $this->settingService->updateInvoiceSettings($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Pengaturan invoice berhasil disimpan']);
        }

        return back()->with('success', 'Pengaturan invoice berhasil disimpan.');
    }

    /**
     * Update email settings
     */
    public function updateEmail(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'mail_host' => 'nullable|string|max:255',
            'mail_port' => 'nullable|string|max:10',
            'mail_username' => 'nullable|string|max:255',
            'mail_password' => 'nullable|string|max:255',
            'mail_encryption' => 'nullable|in:tls,ssl,null',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
        ]);

        $this->settingService->updateEmailSettings($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Pengaturan email berhasil disimpan']);
        }

        return back()->with('success', 'Pengaturan email berhasil disimpan.');
    }

    /**
     * Update kost rules
     */
    public function updateRules(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'rules' => 'nullable|array',
            'rules.*' => 'string',
        ]);

        $this->settingService->updateKostRules($validated['rules'] ?? []);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Aturan kost berhasil disimpan']);
        }

        return back()->with('success', 'Aturan kost berhasil disimpan.');
    }

    /**
     * Backup data (placeholder)
     */
    public function backup(): RedirectResponse|JsonResponse
    {
        // TODO: Implement actual backup logic
        // Could use spatie/laravel-backup package

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Backup sedang diproses']);
        }

        return back()->with('success', 'Backup sedang diproses.');
    }
}
