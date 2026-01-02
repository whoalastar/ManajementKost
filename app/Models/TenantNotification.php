<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenantNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'type',
        'title',
        'message',
        'link',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    // Type constants
    const TYPE_INVOICE_CREATED = 'invoice_created';
    const TYPE_INVOICE_DUE = 'invoice_due';
    const TYPE_INVOICE_OVERDUE = 'invoice_overdue';
    const TYPE_PAYMENT_VERIFIED = 'payment_verified';
    const TYPE_PAYMENT_REJECTED = 'payment_rejected';
    const TYPE_MAINTENANCE_UPDATED = 'maintenance_updated';
    const TYPE_MAINTENANCE_RESOLVED = 'maintenance_resolved';
    const TYPE_GENERAL = 'general';

    public static function types(): array
    {
        return [
            self::TYPE_INVOICE_CREATED => 'Invoice Dibuat',
            self::TYPE_INVOICE_DUE => 'Invoice Jatuh Tempo',
            self::TYPE_INVOICE_OVERDUE => 'Invoice Terlambat',
            self::TYPE_PAYMENT_VERIFIED => 'Pembayaran Diverifikasi',
            self::TYPE_PAYMENT_REJECTED => 'Pembayaran Ditolak',
            self::TYPE_MAINTENANCE_UPDATED => 'Pengaduan Diperbarui',
            self::TYPE_MAINTENANCE_RESOLVED => 'Pengaduan Selesai',
            self::TYPE_GENERAL => 'Umum',
        ];
    }

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // Helpers
    public function markAsRead(): void
    {
        if (!$this->read_at) {
            $this->update(['read_at' => now()]);
        }
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    // Static helpers
    public static function createForTenant(
        Tenant $tenant,
        string $type,
        string $title,
        string $message,
        ?string $link = null
    ): self {
        return self::create([
            'tenant_id' => $tenant->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
        ]);
    }
}
