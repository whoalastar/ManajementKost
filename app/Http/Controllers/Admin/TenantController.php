<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TenantController extends Controller
{
    public function __construct(
        private TenantService $tenantService
    ) {}

    /**
     * Display a listing of tenants
     */
    public function index(Request $request): View|JsonResponse
    {
        $filters = $request->only(['status', 'room_id', 'search', 'sort_by', 'sort_dir', 'per_page']);
        $tenants = $this->tenantService->getFilteredTenants($filters);
        $rooms = Room::all();

        if ($request->wantsJson()) {
            return response()->json($tenants);
        }

        return view('admin.tenants.index', compact('tenants', 'rooms', 'filters'));
    }

    /**
     * Display archived tenants
     */
    public function archived(Request $request): View|JsonResponse
    {
        $filters = $request->only(['room_id', 'search', 'sort_by', 'sort_dir', 'per_page']);
        $tenants = $this->tenantService->getArchivedTenants($filters);

        if ($request->wantsJson()) {
            return response()->json($tenants);
        }

        return view('admin.tenants.archived', compact('tenants', 'filters'));
    }

    /**
     * Show the form for creating a new tenant
     */
    public function create(): View
    {
        $rooms = Room::empty()->get();
        return view('admin.tenants.create', compact('rooms'));
    }

    /**
     * Store a newly created tenant
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'id_card_number' => 'nullable|string|max:30',
            'id_card_photo' => 'nullable|image|max:2048',
            'photo' => 'nullable|image|max:2048',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'room_id' => 'nullable|exists:rooms,id',
            'check_in_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Handle file uploads
        if ($request->hasFile('id_card_photo')) {
            $validated['id_card_photo'] = $request->file('id_card_photo')->store('tenants/id_cards', 'public');
        }

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('tenants/photos', 'public');
        }

        $tenant = $this->tenantService->create($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Penghuni berhasil ditambahkan', 'tenant' => $tenant], 201);
        }

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Penghuni berhasil ditambahkan.');
    }

    /**
     * Display the specified tenant
     */
    public function show(Tenant $tenant): View|JsonResponse
    {
        $tenant->load(['room', 'room.roomType', 'invoices', 'payments', 'maintenanceReports']);

        if (request()->wantsJson()) {
            return response()->json($tenant);
        }

        return view('admin.tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified tenant
     */
    public function edit(Tenant $tenant): View
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified tenant
     */
    public function update(Request $request, Tenant $tenant): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'id_card_number' => 'nullable|string|max:30',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $tenant = $this->tenantService->update($tenant, $validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Data penghuni berhasil diperbarui', 'tenant' => $tenant]);
        }

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Data penghuni berhasil diperbarui.');
    }

    /**
     * Assign room to tenant
     */
    public function assignRoom(Request $request, Tenant $tenant): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);

        $tenant = $this->tenantService->assignRoom($tenant, $validated['room_id']);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Kamar berhasil ditugaskan', 'tenant' => $tenant]);
        }

        return back()->with('success', 'Kamar berhasil ditugaskan.');
    }

    /**
     * Move tenant to another room
     */
    public function moveRoom(Request $request, Tenant $tenant): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);

        $tenant = $this->tenantService->moveRoom($tenant, $validated['room_id']);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Penghuni berhasil dipindahkan', 'tenant' => $tenant]);
        }

        return back()->with('success', 'Penghuni berhasil dipindahkan ke kamar baru.');
    }

    /**
     * Checkout tenant
     */
    public function checkout(Request $request, Tenant $tenant): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'check_out_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $tenant = $this->tenantService->checkout($tenant, $validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Penghuni berhasil checkout', 'tenant' => $tenant]);
        }

        return redirect()->route('admin.tenants.index')
            ->with('success', 'Penghuni berhasil checkout.');
    }
}
