<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'path',
        'filename',
        'is_primary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
        ];
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the correct path for the photo.
     * Handles both old storage-linked files and new public-uploaded files.
     */
    public function getPathAttribute($value)
    {
        // If it's a new upload (already has 'uploads/' or 'storage/' prefix), return as is
        if (str_starts_with($value, 'uploads/') || str_starts_with($value, 'storage/')) {
            return $value;
        }

        // For old files stored in storage/app/public but saved without 'storage/' prefix in DB
        // checks if the file exists via storage link
        return 'storage/' . $value;
    }
}
