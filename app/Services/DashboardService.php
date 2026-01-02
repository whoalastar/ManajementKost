<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Invoice;
use App\Models\MaintenanceReport;
use App\Models\Payment;
use App\Models\Room;
use App\Models\Tenant;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Get dashboard statistics
     */
    public function getStats(): array
    {
        return [
            'total_rooms' => Room::count(),
            'empty_rooms' => Room::empty()->count(),
            'occupied_rooms' => Room::occupied()->count(),
            'maintenance_rooms' => Room::maintenance()->count(),
            'active_tenants' => Tenant::active()->count(),
            'total_invoices_this_month' => Invoice::currentMonth()->count(),
            'unpaid_invoices' => Invoice::unpaid()->count(),
            'total_revenue_this_month' => $this->getTotalRevenueThisMonth(),
            'pending_bookings' => Booking::pending()->count(),
            'pending_maintenance' => MaintenanceReport::pending()->count(),
        ];
    }

    /**
     * Get monthly revenue for chart
     */
    public function getMonthlyRevenue(int $year = null): array
    {
        $year = $year ?? now()->year;
        
        $revenues = Payment::query()
            ->whereYear('payment_date', $year)
            ->whereNotNull('verified_at')
            ->select(
                DB::raw('MONTH(payment_date) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Fill all months
        $result = [];
        for ($i = 1; $i <= 12; $i++) {
            $result[$i] = $revenues[$i] ?? 0;
        }

        return $result;
    }

    /**
     * Get occupancy rate for chart
     */
    public function getOccupancyRate(int $months = 6): array
    {
        $result = [];
        $totalRooms = Room::count();

        if ($totalRooms === 0) {
            return array_fill(0, $months, 0);
        }

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
            // Get occupied rooms count at end of each month
            $occupiedCount = Tenant::where('status', 'active')
                ->where('check_in_date', '<=', $date->endOfMonth())
                ->where(function ($query) use ($date) {
                    $query->whereNull('check_out_date')
                        ->orWhere('check_out_date', '>=', $date->startOfMonth());
                })
                ->count();

            $result[$date->format('M Y')] = round(($occupiedCount / $totalRooms) * 100, 1);
        }

        return $result;
    }

    /**
     * Get total revenue this month
     */
    private function getTotalRevenueThisMonth(): float
    {
        return Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->whereNotNull('verified_at')
            ->sum('amount');
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities(int $limit = 10): array
    {
        return [
            'recent_bookings' => Booking::with('room')
                ->latest()
                ->take($limit)
                ->get(),
            'recent_payments' => Payment::with(['tenant', 'invoice'])
                ->latest()
                ->take($limit)
                ->get(),
            'recent_maintenance' => MaintenanceReport::with(['room', 'tenant'])
                ->latest()
                ->take($limit)
                ->get(),
        ];
    }
}
