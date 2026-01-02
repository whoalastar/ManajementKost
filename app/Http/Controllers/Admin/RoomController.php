<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomPhoto;
use App\Models\RoomType;
use App\Services\RoomService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function __construct(
        private RoomService $roomService
    ) {}

    /**
     * Display a listing of rooms
     */
    public function index(Request $request): View|JsonResponse
    {
        $filters = $request->only(['status', 'floor', 'room_type_id', 'search', 'sort_by', 'sort_dir', 'per_page']);
        $rooms = $this->roomService->getFilteredRooms($filters);
        $roomTypes = RoomType::all();
        $floors = $this->roomService->getFloorsList();

        if ($request->wantsJson()) {
            return response()->json($rooms);
        }

        return view('admin.rooms.index', compact('rooms', 'roomTypes', 'floors', 'filters'));
    }

    /**
     * Show the form for creating a new room
     */
    public function create(): View
    {
        $roomTypes = RoomType::all();
        $facilities = \App\Models\Facility::where('type', 'room')->get();
        return view('admin.rooms.create', compact('roomTypes', 'facilities'));
    }

    /**
     * Store a newly created room
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                Rule::unique('rooms', 'code')->whereNull('deleted_at'),
            ],
            'name' => 'required|string|max:255',
            'room_type_id' => 'nullable|exists:room_types,id',
            'floor' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'status' => 'nullable|in:empty,occupied,maintenance',
            'description' => 'nullable|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:2048',
        ]);

        $room = $this->roomService->create($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Kamar berhasil dibuat', 'room' => $room], 201);
        }

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil dibuat.');
    }

    /**
     * Display the specified room
     */
    public function show(Room $room): View|JsonResponse
    {
        $room->load(['roomType', 'photos', 'facilities', 'currentTenant', 'maintenanceReports']);

        if (request()->wantsJson()) {
            return response()->json($room);
        }

        return view('admin.rooms.show', compact('room'));
    }

    /**
     * Show the form for editing the specified room
     */
    public function edit(Room $room): View
    {
        $room->load(['photos', 'facilities']);
        $roomTypes = RoomType::all();
        $facilities = \App\Models\Facility::where('type', 'room')->get();
        return view('admin.rooms.edit', compact('room', 'roomTypes', 'facilities'));
    }

    /**
     * Update the specified room
     */
    public function update(Request $request, Room $room): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                Rule::unique('rooms', 'code')->ignore($room->id)->whereNull('deleted_at'),
            ],
            'name' => 'required|string|max:255',
            'room_type_id' => 'nullable|exists:room_types,id',
            'floor' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'status' => 'nullable|in:empty,occupied,maintenance',
            'description' => 'nullable|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
            'photos' => 'nullable|array',
            'photos.*' => 'image|max:2048',
        ]);

        $room = $this->roomService->update($room, $validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Kamar berhasil diperbarui', 'room' => $room]);
        }

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil diperbarui.');
    }

    /**
     * Remove the specified room (soft delete)
     */
    public function destroy(Room $room): RedirectResponse|JsonResponse
    {
        $this->roomService->delete($room);

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Kamar berhasil dihapus']);
        }

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil dihapus.');
    }

    /**
     * Update room status
     */
    public function updateStatus(Request $request, Room $room): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:empty,occupied,maintenance',
        ]);

        $room = $this->roomService->updateStatus($room, $validated['status']);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Status kamar berhasil diperbarui', 'room' => $room]);
        }

        return back()->with('success', 'Status kamar berhasil diperbarui.');
    }

    /**
     * Delete photo
     */
    public function deletePhoto(RoomPhoto $photo): RedirectResponse|JsonResponse
    {
        $this->roomService->deletePhoto($photo);

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Foto berhasil dihapus']);
        }

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    /**
     * Set primary photo
     */
    public function setPrimaryPhoto(Room $room, RoomPhoto $photo): RedirectResponse|JsonResponse
    {
        $this->roomService->setPrimaryPhoto($room, $photo);

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Foto utama berhasil diatur']);
        }

        return back()->with('success', 'Foto utama berhasil diatur.');
    }
}
