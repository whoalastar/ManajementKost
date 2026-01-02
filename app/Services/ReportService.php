<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\MaintenanceReport;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get income report
     */
    public function getIncomeReport(array $filters = []): array
    {
        $query = Payment::whereNotNull('verified_at');

        if (!empty($filters['date_from'])) {
            $query->whereDate('payment_date', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('payment_date', '<=', $filters['date_to']);
        }

        $payments = $query->with(['tenant', 'invoice.room'])->get();

        // Group by date
        $groupedByDate = $payments->groupBy(function ($payment) {
            return $payment->payment_date->format('Y-m-d');
        });

        // Group by month
        $groupedByMonth = $payments->groupBy(function ($payment) {
            return $payment->payment_date->format('Y-m');
        });

        return [
            'total' => $payments->sum('amount'),
            'by_method' => [
                'cash' => $payments->where('payment_method', 'cash')->sum('amount'),
                'transfer' => $payments->where('payment_method', 'transfer')->sum('amount'),
            ],
            'by_date' => $groupedByDate->map(fn($items) => $items->sum('amount')),
            'by_month' => $groupedByMonth->map(fn($items) => $items->sum('amount')),
            'details' => $payments,
        ];
    }

    /**
     * Get arrears/tunggakan report
     */
    public function getArrearsReport(): array
    {
        $unpaidInvoices = Invoice::with(['tenant', 'room'])
            ->unpaid()
            ->orderBy('due_date')
            ->get();

        $overdueInvoices = $unpaidInvoices->where('status', Invoice::STATUS_OVERDUE);

        return [
            'total_unpaid' => $unpaidInvoices->sum('total_amount') - $unpaidInvoices->sum('paid_amount'),
            'total_overdue' => $overdueInvoices->sum('total_amount') - $overdueInvoices->sum('paid_amount'),
            'unpaid_count' => $unpaidInvoices->count(),
            'overdue_count' => $overdueInvoices->count(),
            'invoices' => $unpaidInvoices,
            'by_tenant' => $unpaidInvoices->groupBy('tenant_id')->map(function ($items) {
                return [
                    'tenant' => $items->first()->tenant,
                    'total' => $items->sum('total_amount') - $items->sum('paid_amount'),
                    'count' => $items->count(),
                ];
            }),
        ];
    }

    /**
     * Get occupancy report
     */
    public function getOccupancyReport(): array
    {
        $totalRooms = Room::count();
        $occupiedRooms = Room::occupied()->count();
        $emptyRooms = Room::empty()->count();
        $maintenanceRooms = Room::maintenance()->count();

        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;

        // Room details
        $rooms = Room::with(['roomType', 'currentTenant'])->get();

        // Occupancy by floor
        $byFloor = $rooms->groupBy('floor')->map(function ($items) {
            $total = $items->count();
            $occupied = $items->where('status', 'occupied')->count();
            return [
                'total' => $total,
                'occupied' => $occupied,
                'rate' => $total > 0 ? round(($occupied / $total) * 100, 2) : 0,
            ];
        });

        // Occupancy by room type
        $byType = $rooms->groupBy('room_type_id')->map(function ($items) {
            $total = $items->count();
            $occupied = $items->where('status', 'occupied')->count();
            $type = $items->first()->roomType;
            return [
                'type' => $type ? $type->name : 'Tanpa Tipe',
                'total' => $total,
                'occupied' => $occupied,
                'rate' => $total > 0 ? round(($occupied / $total) * 100, 2) : 0,
            ];
        });

        return [
            'total_rooms' => $totalRooms,
            'occupied_rooms' => $occupiedRooms,
            'empty_rooms' => $emptyRooms,
            'maintenance_rooms' => $maintenanceRooms,
            'occupancy_rate' => $occupancyRate,
            'rooms' => $rooms,
            'by_floor' => $byFloor,
            'by_type' => $byType,
        ];
    }

    /**
     * Get maintenance report
     */
    public function getMaintenanceReport(array $filters = []): array
    {
        $query = MaintenanceReport::with(['room', 'tenant', 'resolvedBy']);

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $reports = $query->get();

        return [
            'total' => $reports->count(),
            'by_status' => [
                'new' => $reports->where('status', 'new')->count(),
                'in_progress' => $reports->where('status', 'in_progress')->count(),
                'resolved' => $reports->where('status', 'resolved')->count(),
            ],
            'by_priority' => [
                'low' => $reports->where('priority', 'low')->count(),
                'medium' => $reports->where('priority', 'medium')->count(),
                'high' => $reports->where('priority', 'high')->count(),
            ],
            'by_room' => $reports->groupBy('room_id')->map(function ($items) {
                return [
                    'room' => $items->first()->room,
                    'count' => $items->count(),
                ];
            }),
            'reports' => $reports,
            'avg_resolution_time' => $this->calculateAvgResolutionTime($reports),
        ];
    }

    /**
     * Calculate average resolution time
     */
    private function calculateAvgResolutionTime(Collection $reports): ?float
    {
        $resolved = $reports->whereNotNull('resolved_at');
        
        if ($resolved->isEmpty()) {
            return null;
        }

        $totalHours = $resolved->sum(function ($report) {
            return $report->created_at->diffInHours($report->resolved_at);
        });

        return round($totalHours / $resolved->count(), 2);
    }
}
