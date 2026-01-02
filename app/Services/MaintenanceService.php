<?php

namespace App\Services;

use App\Models\MaintenanceReport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MaintenanceService
{
    /**
     * Get all maintenance reports with filters
     */
    public function getFilteredReports(array $filters = [])
    {
        $query = MaintenanceReport::with(['room', 'tenant', 'resolvedBy']);

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (!empty($filters['room_id'])) {
            $query->where('room_id', $filters['room_id']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_dir'] ?? 'desc';
        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Create a maintenance report (from tenant or admin)
     */
    public function create(array $data, ?UploadedFile $photo = null): MaintenanceReport
    {
        $photoPath = null;
        if ($photo) {
            $photoPath = $photo->store('maintenance', 'public');
        }

        $report = MaintenanceReport::create([
            'room_id' => $data['room_id'],
            'tenant_id' => $data['tenant_id'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'],
            'photo' => $photoPath ?? ($data['photo'] ?? null),
            'priority' => $data['priority'] ?? MaintenanceReport::PRIORITY_MEDIUM,
            'status' => MaintenanceReport::STATUS_NEW,
        ]);

        ActivityLogService::logCreate($report, "Laporan maintenance baru: {$report->title}");

        return $report;
    }

    /**
     * Update status
     */
    public function updateStatus(MaintenanceReport $report, string $status, ?string $notes = null): MaintenanceReport
    {
        $oldValues = $report->toArray();
        $updateData = [
            'status' => $status,
        ];

        if ($notes) {
            $updateData['admin_notes'] = $notes;
        }

        if ($status === MaintenanceReport::STATUS_RESOLVED) {
            $updateData['resolved_at'] = now();
            $updateData['resolved_by'] = Auth::guard('admin')->id();
        }

        $report->update($updateData);

        ActivityLogService::logUpdate(
            $report, 
            $oldValues, 
            "Mengubah status maintenance menjadi {$status}"
        );

        return $report->fresh();
    }

    /**
     * Add admin notes
     */
    public function addNotes(MaintenanceReport $report, string $notes): MaintenanceReport
    {
        $oldValues = $report->toArray();
        $report->update(['admin_notes' => $notes]);

        ActivityLogService::logUpdate($report, $oldValues, "Menambahkan catatan maintenance");

        return $report;
    }

    /**
     * Get maintenance history for room
     */
    public function getRoomHistory(int $roomId)
    {
        return MaintenanceReport::where('room_id', $roomId)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Delete report
     */
    public function delete(MaintenanceReport $report): bool
    {
        if ($report->photo) {
            Storage::disk('public')->delete($report->photo);
        }

        ActivityLogService::logDelete($report, "Menghapus laporan maintenance: {$report->title}");

        return $report->delete();
    }

    /**
     * Get pending reports count
     */
    public function getPendingCount(): int
    {
        return MaintenanceReport::pending()->count();
    }

    /**
     * Get reports by priority
     */
    public function getByPriority(string $priority)
    {
        return MaintenanceReport::where('priority', $priority)
            ->pending()
            ->orderByDesc('created_at')
            ->get();
    }
}
