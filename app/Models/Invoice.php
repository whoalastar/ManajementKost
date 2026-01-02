<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'tenant_id',
        'room_id',
        'period_month',
        'period_year',
        'due_date',
        'room_price',
        'electricity_fee',
        'water_fee',
        'internet_fee',
        'penalty_fee',
        'other_fee',
        'other_fee_description',
        'total_amount',
        'paid_amount',
        'status',
        'notes',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'room_price' => 'decimal:2',
            'electricity_fee' => 'decimal:2',
            'water_fee' => 'decimal:2',
            'internet_fee' => 'decimal:2',
            'penalty_fee' => 'decimal:2',
            'other_fee' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'sent_at' => 'datetime',
        ];
    }

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_SENT = 'sent';
    const STATUS_DUE = 'due';
    const STATUS_PAID = 'paid';
    const STATUS_OVERDUE = 'overdue';

    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_SENT => 'Terkirim',
            self::STATUS_DUE => 'Jatuh Tempo',
            self::STATUS_PAID => 'Lunas',
            self::STATUS_OVERDUE => 'Terlambat',
        ];
    }

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class);
    }

    // Scopes
    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', [self::STATUS_SENT, self::STATUS_DUE, self::STATUS_OVERDUE]);
    }

    public function scopePaid($query)
    {
        return $query->where('status', self::STATUS_PAID);
    }

    public function scopeCurrentMonth($query)
    {
        return $query->where('period_month', now()->month)
            ->where('period_year', now()->year);
    }

    // Helpers
    public function calculateTotal(): float
    {
        return $this->room_price 
            + $this->electricity_fee 
            + $this->water_fee 
            + $this->internet_fee 
            + $this->penalty_fee 
            + $this->other_fee;
    }

    public function getRemainingAmount(): float
    {
        return $this->total_amount - $this->paid_amount;
    }

    public static function generateInvoiceNumber(): string
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $lastInvoice = self::whereDate('created_at', today())->latest()->first();
        $sequence = $lastInvoice ? (int)substr($lastInvoice->invoice_number, -4) + 1 : 1;
        
        return $prefix . $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
