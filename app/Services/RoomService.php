<?php

namespace App\Services;

use App\Models\Room;
use App\Models\RoomPhoto;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RoomService
{
    /**
     * Get all rooms with filters
     */
    public function getFilteredRooms(array $filters = [])
    {
        $query = Room::with(['roomType', 'photos', 'facilities', 'currentTenant']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['floor'])) {
            $query->where('floor', $filters['floor']);
        }

        if (!empty($filters['room_type_id'])) {
            $query->where('room_type_id', $filters['room_type_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            });
        }

        $sortBy = $filters['sort_by'] ?? 'code';
        $sortDir = $filters['sort_dir'] ?? 'asc';
        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Create a new room
     */
    public function create(array $data): Room
    {
        return DB::transaction(function () use ($data) {
            $room = Room::create([
                'code' => $data['code'],
                'name' => $data['name'],
                'room_type_id' => $data['room_type_id'] ?? null,
                'floor' => $data['floor'] ?? 1,
                'price' => $data['price'],
                'status' => $data['status'] ?? Room::STATUS_EMPTY,
                'description' => $data['description'] ?? null,
            ]);

            // Sync facilities
            if (!empty($data['facilities'])) {
                $room->facilities()->sync($data['facilities']);
            }

            // Upload photos
            if (!empty($data['photos'])) {
                $this->uploadPhotos($room, $data['photos']);
            }

            ActivityLogService::logCreate($room, "Membuat kamar baru: {$room->code}");

            return $room;
        });
    }

    /**
     * Update a room
     */
    public function update(Room $room, array $data): Room
    {
        return DB::transaction(function () use ($room, $data) {
            $oldValues = $room->toArray();

            $room->update([
                'code' => $data['code'] ?? $room->code,
                'name' => $data['name'] ?? $room->name,
                'room_type_id' => $data['room_type_id'] ?? $room->room_type_id,
                'floor' => $data['floor'] ?? $room->floor,
                'price' => $data['price'] ?? $room->price,
                'status' => $data['status'] ?? $room->status,
                'description' => $data['description'] ?? $room->description,
            ]);

            // Sync facilities
            if (isset($data['facilities'])) {
                $room->facilities()->sync($data['facilities']);
            }

            // Upload new photos
            if (!empty($data['photos'])) {
                $this->uploadPhotos($room, $data['photos']);
            }

            ActivityLogService::logUpdate($room, $oldValues, "Mengubah kamar: {$room->code}");

            return $room->fresh();
        });
    }

    /**
     * Delete a room (soft delete)
     */
    public function delete(Room $room): bool
    {
        ActivityLogService::logDelete($room, "Menghapus kamar: {$room->code}");
        return $room->delete();
    }

    /**
     * Update room status
     */
    public function updateStatus(Room $room, string $status): Room
    {
        $oldValues = $room->toArray();
        $room->update(['status' => $status]);
        
        ActivityLogService::logUpdate(
            $room, 
            $oldValues, 
            "Mengubah status kamar {$room->code} menjadi {$status}"
        );

        return $room;
    }

    /**
     * Upload photos
     */
    public function uploadPhotos(Room $room, array $files): void
    {
        foreach ($files as $index => $file) {
            if ($file instanceof UploadedFile) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = 'uploads/rooms/' . $room->id;
                
                // Ensure directory exists
                if (!file_exists(public_path($path))) {
                    mkdir(public_path($path), 0755, true);
                }

                $file->move(public_path($path), $filename);
                
                RoomPhoto::create([
                    'room_id' => $room->id,
                    'path' => $path . '/' . $filename,
                    'filename' => $file->getClientOriginalName(),
                    'is_primary' => $index === 0 && $room->photos()->count() === 0,
                    'sort_order' => $room->photos()->count() + $index,
                ]);
            }
        }
    }

    /**
     * Delete a photo
     */
    public function deletePhoto(RoomPhoto $photo): bool
    {
        if (file_exists(public_path($photo->path))) {
            unlink(public_path($photo->path));
        }
        return $photo->delete();
    }

    /**
     * Set primary photo
     */
    public function setPrimaryPhoto(Room $room, RoomPhoto $photo): void
    {
        DB::transaction(function () use ($room, $photo) {
            // Reset all photos
            $room->photos()->update(['is_primary' => false]);
            // Set new primary
            $photo->update(['is_primary' => true]);
        });
    }

    /**
     * Get floors list
     */
    public function getFloorsList(): array
    {
        return Room::distinct('floor')
            ->orderBy('floor')
            ->pluck('floor')
            ->toArray();
    }
}
