<?php

namespace App\Services;

use App\Models\Facility;
use App\Models\Invoice;
use App\Models\MaintenanceReport;
use App\Models\Setting;
use App\Models\Tenant;
use App\Models\TenantNotification;

class TenantPortalService
{
    /**
     * Get dashboard data for tenant
     */
    public function getDashboardData(Tenant $tenant): array
    {
        $tenant->load(['room', 'room.roomType', 'room.facilities']);

        $activeInvoice = $tenant->getActiveInvoice();
        $unpaidTotal = $tenant->getUnpaidInvoicesTotal();
        $pendingPayments = $tenant->getPendingPaymentsCount();
        $unreadNotifications = $tenant->unreadNotifications()->count();

        // Calculate rent status
        $rentStatus = 'active';
        $dueDate = null;
        
        if ($activeInvoice) {
            $dueDate = $activeInvoice->due_date;
            $daysUntilDue = now()->diffInDays($dueDate, false);
            
            if ($daysUntilDue < 0) {
                $rentStatus = 'overdue';
            } elseif ($daysUntilDue <= 7) {
                $rentStatus = 'due_soon';
            }
        }

        return [
            'tenant' => $tenant,
            'room' => $tenant->room,
            'rent_status' => $rentStatus,
            'due_date' => $dueDate,
            'active_invoice' => $activeInvoice,
            'unpaid_total' => $unpaidTotal,
            'pending_payments' => $pendingPayments,
            'unread_notifications' => $unreadNotifications,
            'recent_notifications' => $tenant->unreadNotifications()
                ->orderByDesc('created_at')
                ->take(5)
                ->get(),
        ];
    }

    /**
     * Get invoices for tenant
     */
    public function getInvoices(Tenant $tenant, array $filters = [])
    {
        $query = $tenant->invoices()->with('payments');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['year'])) {
            $query->where('period_year', $filters['year']);
        }

        return $query->orderByDesc('period_year')
            ->orderByDesc('period_month')
            ->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Get single invoice for tenant
     */
    public function getInvoice(Tenant $tenant, int $invoiceId): ?Invoice
    {
        return $tenant->invoices()
            ->with(['room', 'payments'])
            ->find($invoiceId);
    }

    /**
     * Get payments for tenant
     */
    public function getPayments(Tenant $tenant, array $filters = [])
    {
        $query = $tenant->payments()->with('invoice');

        if (!empty($filters['status'])) {
            if ($filters['status'] === 'pending') {
                $query->whereNull('verified_at');
            } elseif ($filters['status'] === 'verified') {
                $query->whereNotNull('verified_at');
            }
        }

        return $query->orderByDesc('created_at')
            ->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Get maintenance reports for tenant
     */
    public function getMaintenanceReports(Tenant $tenant, array $filters = [])
    {
        $query = $tenant->maintenanceReports()->with('room');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderByDesc('created_at')
            ->paginate($filters['per_page'] ?? 10);
    }

    /**
     * Create maintenance report
     */
    public function createMaintenanceReport(Tenant $tenant, array $data): MaintenanceReport
    {
        $report = MaintenanceReport::create([
            'room_id' => $tenant->room_id,
            'tenant_id' => $tenant->id,
            'title' => $data['title'],
            'description' => $data['description'],
            'photo' => $data['photo'] ?? null,
            'priority' => $data['priority'] ?? MaintenanceReport::PRIORITY_MEDIUM,
            'status' => MaintenanceReport::STATUS_NEW,
        ]);

        return $report;
    }

    /**
     * Get notifications for tenant
     */
    public function getNotifications(Tenant $tenant, array $filters = [])
    {
        $query = $tenant->notifications();

        if (!empty($filters['unread_only'])) {
            $query->whereNull('read_at');
        }

        return $query->orderByDesc('created_at')
            ->paginate($filters['per_page'] ?? 15);
    }

    /**
     * Mark notification as read
     */
    public function markNotificationAsRead(Tenant $tenant, int $notificationId): bool
    {
        $notification = $tenant->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->markAsRead();
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead(Tenant $tenant): int
    {
        return $tenant->unreadNotifications()->update(['read_at' => now()]);
    }

    /**
     * Get tenant profile
     */
    public function getProfile(Tenant $tenant): array
    {
        $tenant->load(['room', 'room.roomType']);

        return [
            'tenant' => $tenant,
            'room' => $tenant->room,
        ];
    }

    /**
     * Update tenant profile (limited fields)
     */
    public function updateProfile(Tenant $tenant, array $data): Tenant
    {
        $allowedFields = ['email', 'phone'];
        $updateData = array_intersect_key($data, array_flip($allowedFields));

        $tenant->update($updateData);

        return $tenant->fresh();
    }

    /**
     * Get kost information
     */
    public function getKostInfo(): array
    {
        $settingService = app(SettingService::class);

        return [
            'profile' => $settingService->getProfileSettings(),
            'rules' => $settingService->getKostRules(),
            'shared_facilities' => Facility::where('type', Facility::TYPE_SHARED)->get(),
        ];
    }

    /**
     * Get tenant history
     */
    public function getHistory(Tenant $tenant): array
    {
        return [
            'invoices' => $tenant->invoices()
                ->orderByDesc('period_year')
                ->orderByDesc('period_month')
                ->get(),
            'payments' => $tenant->payments()
                ->with('invoice')
                ->whereNotNull('verified_at')
                ->orderByDesc('payment_date')
                ->get(),
            'maintenance' => $tenant->maintenanceReports()
                ->orderByDesc('created_at')
                ->get(),
        ];
    }
}
