<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class TenantService
{
    /**
     * Get all tenants with filters
     */
    public function getFilteredTenants(array $filters = [])
    {
        $query = Tenant::with(['room', 'room.roomType']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['room_id'])) {
            $query->where('room_id', $filters['room_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Create a new tenant
     */
    public function create(array $data): Tenant
    {
        return DB::transaction(function () use ($data) {
            $tenant = Tenant::create([
                'room_id' => $data['room_id'] ?? null,
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'],
                'id_card_number' => $data['id_card_number'] ?? null,
                'id_card_photo' => $data['id_card_photo'] ?? null,
                'photo' => $data['photo'] ?? null,
                'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                'occupation' => $data['occupation'] ?? null,
                'check_in_date' => $data['check_in_date'] ?? now(),
                'status' => Tenant::STATUS_ACTIVE,
                'notes' => $data['notes'] ?? null,
            ]);

            // Update room status if assigned
            if ($tenant->room_id) {
                Room::find($tenant->room_id)->update(['status' => Room::STATUS_OCCUPIED]);
            }

            ActivityLogService::logCreate($tenant, "Menambahkan penghuni baru: {$tenant->name}");

            return $tenant;
        });
    }

    /**
     * Update a tenant
     */
    public function update(Tenant $tenant, array $data): Tenant
    {
        return DB::transaction(function () use ($tenant, $data) {
            $oldValues = $tenant->toArray();
            $oldRoomId = $tenant->room_id;

            $tenant->update([
                'name' => $data['name'] ?? $tenant->name,
                'email' => $data['email'] ?? $tenant->email,
                'phone' => $data['phone'] ?? $tenant->phone,
                'id_card_number' => $data['id_card_number'] ?? $tenant->id_card_number,
                'emergency_contact_name' => $data['emergency_contact_name'] ?? $tenant->emergency_contact_name,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? $tenant->emergency_contact_phone,
                'occupation' => $data['occupation'] ?? $tenant->occupation,
                'notes' => $data['notes'] ?? $tenant->notes,
            ]);

            ActivityLogService::logUpdate($tenant, $oldValues, "Mengubah data penghuni: {$tenant->name}");

            return $tenant->fresh();
        });
    }

    /**
     * Assign room to tenant
     */
    public function assignRoom(Tenant $tenant, int $roomId): Tenant
    {
        return DB::transaction(function () use ($tenant, $roomId) {
            $oldRoomId = $tenant->room_id;

            // Free old room if exists
            if ($oldRoomId) {
                Room::find($oldRoomId)->update(['status' => Room::STATUS_EMPTY]);
            }

            // Assign new room
            $tenant->update([
                'room_id' => $roomId,
                'check_in_date' => $tenant->check_in_date ?? now(),
            ]);

            // Update room status
            Room::find($roomId)->update(['status' => Room::STATUS_OCCUPIED]);

            ActivityLogService::log(
                'assign_room',
                $tenant,
                description: "Menugaskan kamar ke penghuni {$tenant->name}"
            );

            return $tenant->fresh();
        });
    }

    /**
     * Move tenant to another room
     */
    public function moveRoom(Tenant $tenant, int $newRoomId): Tenant
    {
        return DB::transaction(function () use ($tenant, $newRoomId) {
            $oldRoom = $tenant->room;

            // Free old room
            if ($oldRoom) {
                $oldRoom->update(['status' => Room::STATUS_EMPTY]);
            }

            // Assign new room
            $tenant->update(['room_id' => $newRoomId]);
            Room::find($newRoomId)->update(['status' => Room::STATUS_OCCUPIED]);

            ActivityLogService::log(
                'move_room',
                $tenant,
                description: "Memindahkan penghuni {$tenant->name} ke kamar baru"
            );

            return $tenant->fresh();
        });
    }

    /**
     * Checkout tenant
     */
    public function checkout(Tenant $tenant, array $data = []): Tenant
    {
        return DB::transaction(function () use ($tenant, $data) {
            // Free the room
            if ($tenant->room) {
                $tenant->room->update(['status' => Room::STATUS_EMPTY]);
            }

            // Update tenant
            $tenant->update([
                'status' => Tenant::STATUS_CHECKED_OUT,
                'check_out_date' => $data['check_out_date'] ?? now(),
                'notes' => $data['notes'] ?? $tenant->notes,
            ]);

            ActivityLogService::log(
                'checkout',
                $tenant,
                description: "Checkout penghuni {$tenant->name}"
            );

            return $tenant->fresh();
        });
    }

    /**
     * Get archived tenants
     */
    public function getArchivedTenants(array $filters = [])
    {
        return $this->getFilteredTenants(array_merge($filters, [
            'status' => Tenant::STATUS_CHECKED_OUT,
        ]));
    }
}
