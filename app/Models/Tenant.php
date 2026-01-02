<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Tenant extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Guard untuk autentikasi tenant
     */
    protected $guard = 'tenant';

    protected $fillable = [
        'room_id',
        'name',
        'email',
        'password',
        'phone',
        'id_card_number',
        'id_card_photo',
        'photo',
        'emergency_contact_name',
        'emergency_contact_phone',
        'occupation',
        'check_in_date',
        'check_out_date',
        'status',
        'notes',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'check_in_date' => 'date',
            'check_out_date' => 'date',
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
        ];
    }

    // Status constants
    const STATUS_ACTIVE = 'active';
    const STATUS_CHECKED_OUT = 'checked_out';

    public static function statuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_CHECKED_OUT => 'Keluar',
        ];
    }

    // Relationships
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function maintenanceReports()
    {
        return $this->hasMany(MaintenanceReport::class);
    }

    public function notifications()
    {
        return $this->hasMany(TenantNotification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeCheckedOut($query)
    {
        return $query->where('status', self::STATUS_CHECKED_OUT);
    }

    public function scopeCanLogin($query)
    {
        return $query->active()->whereNotNull('email')->whereNotNull('password');
    }

    // Helpers
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function hasRoom(): bool
    {
        return $this->room_id !== null;
    }

    public function getActiveInvoice()
    {
        return $this->invoices()
            ->whereIn('status', [Invoice::STATUS_SENT, Invoice::STATUS_DUE, Invoice::STATUS_OVERDUE])
            ->orderBy('due_date', 'asc')
            ->first();
    }

    public function getPendingPaymentsCount(): int
    {
        return $this->payments()->whereNull('verified_at')->count();
    }

    public function getUnpaidInvoicesTotal(): float
    {
        return $this->invoices()
            ->whereIn('status', [Invoice::STATUS_SENT, Invoice::STATUS_DUE, Invoice::STATUS_OVERDUE])
            ->sum('total_amount') - $this->invoices()
            ->whereIn('status', [Invoice::STATUS_SENT, Invoice::STATUS_DUE, Invoice::STATUS_OVERDUE])
            ->sum('paid_amount');
    }
}
