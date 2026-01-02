<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'room_type_id',
        'floor',
        'price',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    // Status constants
    const STATUS_EMPTY = 'empty';
    const STATUS_OCCUPIED = 'occupied';
    const STATUS_MAINTENANCE = 'maintenance';

    public static function statuses(): array
    {
        return [
            self::STATUS_EMPTY => 'Kosong',
            self::STATUS_OCCUPIED => 'Terisi',
            self::STATUS_MAINTENANCE => 'Maintenance',
        ];
    }

    // Relationships
    public function roomType()
    {
        return $this->belongsTo(RoomType::class);
    }

    public function photos()
    {
        return $this->hasMany(RoomPhoto::class);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'room_facilities')
            ->withTimestamps();
    }

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }

    public function currentTenant()
    {
        return $this->hasOne(Tenant::class)->where('status', Tenant::STATUS_ACTIVE);
    }

    public function maintenanceReports()
    {
        return $this->hasMany(MaintenanceReport::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // Scopes
    public function scopeEmpty($query)
    {
        return $query->where('status', self::STATUS_EMPTY);
    }

    public function scopeOccupied($query)
    {
        return $query->where('status', self::STATUS_OCCUPIED);
    }

    public function scopeMaintenance($query)
    {
        return $query->where('status', self::STATUS_MAINTENANCE);
    }
}
