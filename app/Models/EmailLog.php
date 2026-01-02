<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'email_to',
        'subject',
        'status',
        'error_message',
        'sent_at',
    ];

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
        ];
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_SENT = 'sent';
    const STATUS_FAILED = 'failed';

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu',
            self::STATUS_SENT => 'Terkirim',
            self::STATUS_FAILED => 'Gagal',
        ];
    }

    // Relationships
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
