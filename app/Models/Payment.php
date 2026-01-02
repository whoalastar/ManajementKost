<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'tenant_id',
        'amount',
        'payment_method',
        'payment_date',
        'proof_image',
        'notes',
        'verified_by',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_date' => 'date',
            'verified_at' => 'datetime',
        ];
    }

    // Payment method constants
    const METHOD_CASH = 'cash';
    const METHOD_TRANSFER = 'transfer';

    public static function methods(): array
    {
        return [
            self::METHOD_CASH => 'Tunai',
            self::METHOD_TRANSFER => 'Transfer Bank',
        ];
    }

    // Relationships
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }
}
