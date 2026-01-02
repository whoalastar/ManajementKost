<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RoomType;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of room types
     */
    public function index(Request $request): View|JsonResponse
    {
        $roomTypes = RoomType::withCount('rooms')->paginate(15);

        if ($request->wantsJson()) {
            return response()->json($roomTypes);
        }

        return view('admin.room-types.index', compact('roomTypes'));
    }

    /**
     * Show the form for creating a new room type
     */
    public function create(): View
    {
        return view('admin.room-types.create');
    }

    /**
     * Store a newly created room type
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:room_types,name',
            'description' => 'nullable|string',
        ]);

        $roomType = RoomType::create($validated);
        ActivityLogService::logCreate($roomType, "Membuat tipe kamar: {$roomType->name}");

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Tipe kamar berhasil dibuat', 'room_type' => $roomType], 201);
        }

        return redirect()->route('admin.room-types.index')
            ->with('success', 'Tipe kamar berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified room type
     */
    public function edit(RoomType $roomType): View
    {
        return view('admin.room-types.edit', compact('roomType'));
    }

    /**
     * Update the specified room type
     */
    public function update(Request $request, RoomType $roomType): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:room_types,name,' . $roomType->id,
            'description' => 'nullable|string',
        ]);

        $oldValues = $roomType->toArray();
        $roomType->update($validated);
        ActivityLogService::logUpdate($roomType, $oldValues, "Mengubah tipe kamar: {$roomType->name}");

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Tipe kamar berhasil diperbarui', 'room_type' => $roomType]);
        }

        return redirect()->route('admin.room-types.index')
            ->with('success', 'Tipe kamar berhasil diperbarui.');
    }

    /**
     * Remove the specified room type (soft delete)
     */
    public function destroy(RoomType $roomType): RedirectResponse|JsonResponse
    {
        ActivityLogService::logDelete($roomType, "Menghapus tipe kamar: {$roomType->name}");
        $roomType->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Tipe kamar berhasil dihapus']);
        }

        return redirect()->route('admin.room-types.index')
            ->with('success', 'Tipe kamar berhasil dihapus.');
    }
}
