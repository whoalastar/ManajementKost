<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceReport;
use App\Models\Room;
use App\Services\MaintenanceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function __construct(
        private MaintenanceService $maintenanceService
    ) {}

    /**
     * Display a listing of maintenance reports
     */
    public function index(Request $request): View|JsonResponse
    {
        $filters = $request->only(['status', 'priority', 'room_id', 'search', 'sort_by', 'sort_dir', 'per_page']);
        $reports = $this->maintenanceService->getFilteredReports($filters);
        $rooms = Room::all();
        $statuses = MaintenanceReport::statuses();
        $priorities = MaintenanceReport::priorities();

        if ($request->wantsJson()) {
            return response()->json($reports);
        }

        return view('admin.maintenance.index', compact('reports', 'rooms', 'statuses', 'priorities', 'filters'));
    }

    /**
     * Show the form for creating a new report (from admin)
     */
    public function create(): View
    {
        $rooms = Room::all();
        $priorities = MaintenanceReport::priorities();
        return view('admin.maintenance.create', compact('rooms', 'priorities'));
    }

    /**
     * Store a newly created report
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'tenant_id' => 'nullable|exists:tenants,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|max:2048',
            'priority' => 'nullable|in:low,medium,high',
        ]);

        $report = $this->maintenanceService->create($validated, $request->file('photo'));

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Laporan maintenance berhasil dibuat', 'report' => $report], 201);
        }

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Laporan maintenance berhasil dibuat.');
    }

    /**
     * Display the specified report
     */
    public function show(MaintenanceReport $maintenance): View|JsonResponse
    {
        $maintenance->load(['room', 'tenant', 'resolvedBy']);

        if (request()->wantsJson()) {
            return response()->json($maintenance);
        }

        return view('admin.maintenance.show', compact('maintenance'));
    }

    /**
     * Update status
     */
    public function updateStatus(Request $request, MaintenanceReport $maintenance): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:new,in_progress,resolved',
            'admin_notes' => 'nullable|string',
        ]);

        $report = $this->maintenanceService->updateStatus(
            $maintenance, 
            $validated['status'], 
            $validated['admin_notes'] ?? null
        );

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Status berhasil diperbarui', 'report' => $report]);
        }

        return back()->with('success', 'Status berhasil diperbarui.');
    }

    /**
     * Add admin notes
     */
    public function addNotes(Request $request, MaintenanceReport $maintenance): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $report = $this->maintenanceService->addNotes($maintenance, $validated['admin_notes']);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Catatan berhasil ditambahkan', 'report' => $report]);
        }

        return back()->with('success', 'Catatan berhasil ditambahkan.');
    }

    /**
     * Get room maintenance history
     */
    public function roomHistory(Room $room): View|JsonResponse
    {
        $history = $this->maintenanceService->getRoomHistory($room->id);

        if (request()->wantsJson()) {
            return response()->json($history);
        }

        return view('admin.maintenance.room-history', compact('room', 'history'));
    }

    /**
     * Delete report
     */
    public function destroy(MaintenanceReport $maintenance): RedirectResponse|JsonResponse
    {
        $this->maintenanceService->delete($maintenance);

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Laporan berhasil dihapus']);
        }

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}
