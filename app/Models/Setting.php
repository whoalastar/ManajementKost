<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    // Setting groups
    const GROUP_PROFILE = 'profile';
    const GROUP_PAYMENT = 'payment';
    const GROUP_INVOICE = 'invoice';
    const GROUP_EMAIL = 'email';
    const GROUP_RULES = 'rules';

    public static function groups(): array
    {
        return [
            self::GROUP_PROFILE => 'Profil Kost',
            self::GROUP_PAYMENT => 'Pembayaran',
            self::GROUP_INVOICE => 'Invoice',
            self::GROUP_EMAIL => 'Email',
            self::GROUP_RULES => 'Aturan',
        ];
    }

    // Helper to get setting value
    public static function getValue(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    // Helper to set setting value
    public static function setValue(string $key, $value, string $group = 'general'): self
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }

    // Get all settings by group
    public static function getByGroup(string $group): array
    {
        return self::where('group', $group)
            ->pluck('value', 'key')
            ->toArray();
    }
}
