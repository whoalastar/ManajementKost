<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct(
        private ReportService $reportService
    ) {}

    /**
     * Report index
     */
    public function index(): View
    {
        return view('admin.reports.index');
    }

    /**
     * Income report
     */
    public function income(Request $request): View
    {
        $filters = $request->only(['date_from', 'date_to']);
        $report = $this->reportService->getIncomeReport($filters);

        return view('admin.reports.income', compact('report', 'filters'));
    }

    /**
     * Export income report as PDF
     */
    public function incomeExportPdf(Request $request): Response
    {
        $filters = $request->only(['date_from', 'date_to']);
        $report = $this->reportService->getIncomeReport($filters);

        $pdf = Pdf::loadView('admin.reports.income-pdf', compact('report', 'filters'));

        return $pdf->download('laporan-pendapatan.pdf');
    }

    /**
     * Arrears/Tunggakan report
     */
    public function arrears(): View
    {
        $report = $this->reportService->getArrearsReport();

        return view('admin.reports.arrears', compact('report'));
    }

    /**
     * Export arrears report as PDF
     */
    public function arrearsExportPdf(): Response
    {
        $report = $this->reportService->getArrearsReport();

        $pdf = Pdf::loadView('admin.reports.arrears-pdf', compact('report'));

        return $pdf->download('laporan-tunggakan.pdf');
    }

    /**
     * Occupancy report
     */
    public function occupancy(): View
    {
        $report = $this->reportService->getOccupancyReport();

        return view('admin.reports.occupancy', compact('report'));
    }

    /**
     * Export occupancy report as PDF
     */
    public function occupancyExportPdf(): Response
    {
        $report = $this->reportService->getOccupancyReport();

        $pdf = Pdf::loadView('admin.reports.occupancy-pdf', compact('report'));

        return $pdf->download('laporan-hunian.pdf');
    }

    /**
     * Maintenance report
     */
    public function maintenance(Request $request): View
    {
        $filters = $request->only(['date_from', 'date_to']);
        $report = $this->reportService->getMaintenanceReport($filters);

        return view('admin.reports.maintenance', compact('report', 'filters'));
    }

    /**
     * Export maintenance report as PDF
     */
    public function maintenanceExportPdf(Request $request): Response
    {
        $filters = $request->only(['date_from', 'date_to']);
        $report = $this->reportService->getMaintenanceReport($filters);

        $pdf = Pdf::loadView('admin.reports.maintenance-pdf', compact('report', 'filters'));

        return $pdf->download('laporan-maintenance.pdf');
    }
}
