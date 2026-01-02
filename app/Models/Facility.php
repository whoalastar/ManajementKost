<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'icon',
        'description',
    ];

    // Type constants
    const TYPE_ROOM = 'room';
    const TYPE_SHARED = 'shared';

    public static function types(): array
    {
        return [
            self::TYPE_ROOM => 'Fasilitas Kamar',
            self::TYPE_SHARED => 'Fasilitas Umum',
        ];
    }

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'room_facilities')
            ->withTimestamps();
    }
}
