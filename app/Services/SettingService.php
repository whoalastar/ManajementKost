<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingService
{
    /**
     * Get all settings by group
     */
    public function getByGroup(string $group): array
    {
        return Setting::getByGroup($group);
    }

    /**
     * Get single setting
     */
    public function get(string $key, $default = null)
    {
        return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
            return Setting::getValue($key, $default);
        });
    }

    /**
     * Set single setting
     */
    public function set(string $key, $value, string $group = 'general'): Setting
    {
        Cache::forget("setting_{$key}");
        return Setting::setValue($key, $value, $group);
    }

    /**
     * Update multiple settings
     */
    public function updateMultiple(array $settings, string $group): void
    {
        foreach ($settings as $key => $value) {
            $this->set($key, $value, $group);
        }
    }

    /**
     * Get profile settings
     */
    public function getProfileSettings(): array
    {
        return [
            'kost_name' => $this->get('kost_name', 'Kost Saya'),
            'kost_address' => $this->get('kost_address', ''),
            'kost_phone' => $this->get('kost_phone', ''),
            'kost_email' => $this->get('kost_email', ''),
            'kost_description' => $this->get('kost_description', ''),
            'kost_logo' => $this->get('kost_logo', ''),
        ];
    }

    /**
     * Update profile settings
     */
    public function updateProfileSettings(array $data): void
    {
        $this->updateMultiple($data, Setting::GROUP_PROFILE);
    }

    /**
     * Get payment settings
     */
    public function getPaymentSettings(): array
    {
        return [
            'bank_name' => $this->get('bank_name', ''),
            'bank_account_name' => $this->get('bank_account_name', ''),
            'bank_account_number' => $this->get('bank_account_number', ''),
            'payment_instructions' => $this->get('payment_instructions', ''),
        ];
    }

    /**
     * Update payment settings
     */
    public function updatePaymentSettings(array $data): void
    {
        $this->updateMultiple($data, Setting::GROUP_PAYMENT);
    }

    /**
     * Get invoice settings
     */
    public function getInvoiceSettings(): array
    {
        return [
            'invoice_prefix' => $this->get('invoice_prefix', 'INV'),
            'invoice_footer' => $this->get('invoice_footer', ''),
            'invoice_notes' => $this->get('invoice_notes', ''),
            'invoice_due_days' => $this->get('invoice_due_days', 10),
            'auto_generate_invoice' => $this->get('auto_generate_invoice', false),
        ];
    }

    /**
     * Update invoice settings
     */
    public function updateInvoiceSettings(array $data): void
    {
        $this->updateMultiple($data, Setting::GROUP_INVOICE);
    }

    /**
     * Get email settings
     */
    public function getEmailSettings(): array
    {
        return [
            'mail_host' => $this->get('mail_host', ''),
            'mail_port' => $this->get('mail_port', '587'),
            'mail_username' => $this->get('mail_username', ''),
            'mail_password' => $this->get('mail_password', ''),
            'mail_encryption' => $this->get('mail_encryption', 'tls'),
            'mail_from_address' => $this->get('mail_from_address', ''),
            'mail_from_name' => $this->get('mail_from_name', ''),
        ];
    }

    /**
     * Update email settings
     */
    public function updateEmailSettings(array $data): void
    {
        $this->updateMultiple($data, Setting::GROUP_EMAIL);
    }

    /**
     * Get kost rules
     */
    public function getKostRules(): array
    {
        return [
            'rules' => $this->get('kost_rules', []),
        ];
    }

    /**
     * Update kost rules
     */
    public function updateKostRules(array $rules): void
    {
        $this->set('kost_rules', $rules, Setting::GROUP_RULES);
    }

    /**
     * Upload logo
     */
    public function uploadLogo($file): string
    {
        // Delete old logo if exists
        $oldLogo = $this->get('kost_logo');
        if ($oldLogo) {
            Storage::disk('public')->delete($oldLogo);
        }

        $path = $file->store('settings', 'public');
        $this->set('kost_logo', $path, Setting::GROUP_PROFILE);

        return $path;
    }

    /**
     * Get location settings
     */
    public function getLocationSettings(): array
    {
        return [
            'google_maps_embed' => $this->get('google_maps_embed', ''),
            'google_maps_link' => $this->get('google_maps_link', ''),
            'latitude' => $this->get('latitude', ''),
            'longitude' => $this->get('longitude', ''),
        ];
    }

    /**
     * Update location settings
     */
    public function updateLocationSettings(array $data): void
    {
        $this->updateMultiple($data, Setting::GROUP_PROFILE);
    }

    /**
     * Get public settings (for landing page)
     */
    public function getPublicSettings(): array
    {
        return [
            'visiting_hours' => $this->get('visiting_hours', '08:00 - 21:00'),
            'survey_hours' => $this->get('survey_hours', '09:00 - 17:00'),
            'show_price_on_landing' => $this->get('show_price_on_landing', true),
        ];
    }

    /**
     * Update public settings
     */
    public function updatePublicSettings(array $data): void
    {
        $this->updateMultiple($data, Setting::GROUP_PROFILE);
    }
}
