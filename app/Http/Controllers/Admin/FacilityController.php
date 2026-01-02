<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacilityController extends Controller
{
    /**
     * Display a listing of facilities
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Facility::withCount('rooms');

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $facilities = $query->paginate(15);

        if ($request->wantsJson()) {
            return response()->json($facilities);
        }

        return view('admin.facilities.index', compact('facilities'));
    }

    /**
     * Show the form for creating a new facility
     */
    public function create(): View
    {
        $types = Facility::types();
        return view('admin.facilities.create', compact('types'));
    }

    /**
     * Store a newly created facility
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:room,shared',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $facility = Facility::create($validated);
        ActivityLogService::logCreate($facility, "Membuat fasilitas: {$facility->name}");

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Fasilitas berhasil dibuat', 'facility' => $facility], 201);
        }

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Fasilitas berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified facility
     */
    /**
     * Show the form for editing the specified facility
     */
    public function edit(Facility $facility): View
    {
        $facility->loadCount('rooms');
        $types = Facility::types();
        return view('admin.facilities.edit', compact('facility', 'types'));
    }

    /**
     * Update the specified facility
     */
    public function update(Request $request, Facility $facility): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:room,shared',
            'icon' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ]);

        $oldValues = $facility->toArray();
        $facility->update($validated);
        ActivityLogService::logUpdate($facility, $oldValues, "Mengubah fasilitas: {$facility->name}");

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Fasilitas berhasil diperbarui', 'facility' => $facility]);
        }

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    /**
     * Remove the specified facility (soft delete)
     */
    public function destroy(Facility $facility): RedirectResponse|JsonResponse
    {
        ActivityLogService::logDelete($facility, "Menghapus fasilitas: {$facility->name}");
        $facility->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Fasilitas berhasil dihapus']);
        }

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Fasilitas berhasil dihapus.');
    }
}
