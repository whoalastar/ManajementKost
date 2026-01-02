<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'room_id',
        'name',
        'email',
        'phone',
        'occupation',
        'planned_check_in',
        'message',
        'status',
        'admin_notes',
        'contacted_at',
        'survey_date',
    ];

    protected function casts(): array
    {
        return [
            'planned_check_in' => 'date',
            'contacted_at' => 'datetime',
            'survey_date' => 'datetime',
        ];
    }

    // Status constants
    const STATUS_NEW = 'new';
    const STATUS_CONTACTED = 'contacted';
    const STATUS_SURVEY = 'survey';
    const STATUS_DEAL = 'deal';
    const STATUS_CANCELLED = 'cancelled';

    public static function statuses(): array
    {
        return [
            self::STATUS_NEW => 'Baru',
            self::STATUS_CONTACTED => 'Dihubungi',
            self::STATUS_SURVEY => 'Survey',
            self::STATUS_DEAL => 'Deal',
            self::STATUS_CANCELLED => 'Batal',
        ];
    }

    // Relationships
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // Scopes
    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    public function scopePending($query)
    {
        return $query->whereIn('status', [self::STATUS_NEW, self::STATUS_CONTACTED, self::STATUS_SURVEY]);
    }
}
