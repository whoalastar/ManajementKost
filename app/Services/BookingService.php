<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class BookingService
{
    /**
     * Get all bookings with filters
     */
    public function getFilteredBookings(array $filters = [])
    {
        $query = Booking::with('room');

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

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Create a new booking (from landing page)
     */
    public function create(array $data): Booking
    {
        $booking = Booking::create([
            'room_id' => $data['room_id'] ?? null,
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'occupation' => $data['occupation'] ?? null,
            'planned_check_in' => $data['planned_check_in'] ?? null,
            'message' => $data['message'] ?? null,
            'status' => Booking::STATUS_NEW,
        ]);

        return $booking;
    }

    /**
     * Update booking status
     */
    public function updateStatus(Booking $booking, string $status, ?string $notes = null): Booking
    {
        $updateData = ['status' => $status];

        if ($status === Booking::STATUS_CONTACTED) {
            $updateData['contacted_at'] = now();
        }

        if ($notes) {
            $updateData['admin_notes'] = $notes;
        }

        $oldValues = $booking->toArray();
        $booking->update($updateData);

        ActivityLogService::logUpdate(
            $booking, 
            $oldValues, 
            "Mengubah status booking menjadi {$status}"
        );

        return $booking->fresh();
    }

    /**
     * Set survey date
     */
    public function setSurveyDate(Booking $booking, string $surveyDate): Booking
    {
        $booking->update([
            'status' => Booking::STATUS_SURVEY,
            'survey_date' => $surveyDate,
        ]);

        return $booking->fresh();
    }

    /**
     * Convert booking to tenant
     */
    public function convertToTenant(Booking $booking, array $additionalData = []): Tenant
    {
        return DB::transaction(function () use ($booking, $additionalData) {
            // Create tenant from booking data
            $tenant = app(TenantService::class)->create([
                'room_id' => $booking->room_id,
                'name' => $booking->name,
                'email' => $booking->email,
                'phone' => $booking->phone,
                'occupation' => $booking->occupation,
                'check_in_date' => $additionalData['check_in_date'] ?? $booking->planned_check_in ?? now(),
                ...$additionalData,
            ]);

            // Mark booking as deal
            $booking->update(['status' => Booking::STATUS_DEAL]);

            ActivityLogService::log(
                'convert_to_tenant',
                $booking,
                description: "Mengkonversi booking {$booking->name} menjadi penghuni"
            );

            return $tenant;
        });
    }

    /**
     * Cancel booking
     */
    public function cancel(Booking $booking, ?string $reason = null): Booking
    {
        $booking->update([
            'status' => Booking::STATUS_CANCELLED,
            'admin_notes' => $reason ?? $booking->admin_notes,
        ]);

        ActivityLogService::log(
            'cancel',
            $booking,
            description: "Membatalkan booking {$booking->name}"
        );

        return $booking;
    }
}
