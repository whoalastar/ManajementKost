<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $dashboardService
    ) {}

    /**
     * Tampilkan dashboard admin
     */
    public function index(): View
    {
        $stats = $this->dashboardService->getStats();
        $monthlyRevenue = $this->dashboardService->getMonthlyRevenue();
        $occupancyRate = $this->dashboardService->getOccupancyRate();
        $recentActivities = $this->dashboardService->getRecentActivities();

        return view('admin.dashboard', compact(
            'stats',
            'monthlyRevenue',
            'occupancyRate',
            'recentActivities'
        ));
    }

    /**
     * Get chart data via API
     */
    public function chartData()
    {
        return response()->json([
            'monthly_revenue' => $this->dashboardService->getMonthlyRevenue(),
            'occupancy_rate' => $this->dashboardService->getOccupancyRate(),
        ]);
    }
}
