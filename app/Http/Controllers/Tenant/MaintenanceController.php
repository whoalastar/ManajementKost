<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceReport;
use App\Services\TenantPortalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function __construct(
        private TenantPortalService $portalService
    ) {}

    /**
     * Display a listing of maintenance reports
     */
    public function index(Request $request): View|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();
        $filters = $request->only(['status', 'per_page']);
        $reports = $this->portalService->getMaintenanceReports($tenant, $filters);
        $statuses = MaintenanceReport::statuses();

        if ($request->wantsJson()) {
            return response()->json($reports);
        }

        return view('tenant.maintenance.index', compact('reports', 'statuses', 'filters'));
    }

    /**
     * Show the form for creating a new report
     */
    public function create(): View
    {
        $priorities = MaintenanceReport::priorities();
        return view('tenant.maintenance.create', compact('priorities'));
    }

    /**
     * Store a newly created report
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();

        if (!$tenant->hasRoom()) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Anda tidak memiliki kamar yang ditempati.'], 422);
            }
            return back()->withErrors(['error' => 'Anda tidak memiliki kamar yang ditempati.']);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'photo' => 'nullable|image|max:2048',
            'priority' => 'nullable|in:low,medium,high',
        ], [
            'title.required' => 'Judul laporan wajib diisi.',
            'title.max' => 'Judul maksimal 255 karakter.',
            'description.required' => 'Deskripsi masalah wajib diisi.',
            'description.max' => 'Deskripsi maksimal 2000 karakter.',
            'photo.image' => 'File harus berupa gambar.',
            'photo.max' => 'Ukuran file maksimal 2MB.',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('maintenance', 'public');
        }

        $report = $this->portalService->createMaintenanceReport($tenant, $validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Laporan pengaduan berhasil dikirim.',
                'report' => $report
            ], 201);
        }

        return redirect()->route('tenant.maintenance.index')
            ->with('success', 'Laporan pengaduan berhasil dikirim.');
    }

    /**
     * Display the specified report
     */
    public function show(int $id): View|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();
        $report = $tenant->maintenanceReports()->with('room')->find($id);

        if (!$report) {
            abort(404, 'Laporan tidak ditemukan.');
        }

        if (request()->wantsJson()) {
            return response()->json($report);
        }

        return view('tenant.maintenance.show', compact('report'));
    }
}
