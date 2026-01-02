<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Services\PublicService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private PublicService $publicService
    ) {}

    /**
     * Display list of user's bookings
     */
    public function index(): View|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();
        $bookings = Booking::where('email', $tenant->email)
            ->orWhere('phone', $tenant->phone)
            ->orderByDesc('created_at')
            ->paginate(10);

        if (request()->wantsJson()) {
            return response()->json($bookings);
        }

        return view('tenant.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new booking
     */
    public function create(Request $request): View|JsonResponse
    {
        $roomId = $request->get('room_id');
        $room = $roomId ? $this->publicService->getRoomDetail($roomId) : null;
        $availableRooms = Room::with(['roomType', 'photos'])
            ->where('status', Room::STATUS_EMPTY)
            ->get();
        $contactInfo = $this->publicService->getContactInfo();

        if ($request->wantsJson()) {
            return response()->json([
                'room' => $room,
                'available_rooms' => $availableRooms,
                'contact' => $contactInfo,
            ]);
        }

        return view('tenant.bookings.create', compact('room', 'availableRooms', 'contactInfo'));
    }

    /**
     * Store a newly created booking
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();

        $validated = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'planned_check_in' => ['nullable', 'date', 'after_or_equal:today'],
            'message' => ['nullable', 'string', 'max:1000'],
        ], [
            'room_id.required' => 'Pilih kamar yang diinginkan.',
            'room_id.exists' => 'Kamar tidak valid.',
            'planned_check_in.date' => 'Format tanggal tidak valid.',
            'planned_check_in.after_or_equal' => 'Tanggal check-in tidak boleh sebelum hari ini.',
            'message.max' => 'Pesan maksimal 1000 karakter.',
        ]);

        // Check if room is still available
        $room = Room::find($validated['room_id']);
        if ($room->status !== Room::STATUS_EMPTY) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Maaf, kamar tersebut sudah tidak tersedia.'
                ], 422);
            }
            return back()->withErrors(['room_id' => 'Maaf, kamar tersebut sudah tidak tersedia.']);
        }

        // Create booking with tenant data
        $booking = Booking::create([
            'room_id' => $validated['room_id'],
            'name' => $tenant->name,
            'email' => $tenant->email,
            'phone' => $tenant->phone,
            'occupation' => $tenant->occupation,
            'planned_check_in' => $validated['planned_check_in'] ?? null,
            'message' => $validated['message'] ?? null,
            'status' => Booking::STATUS_NEW,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Booking berhasil dibuat. Kami akan segera menghubungi Anda.',
                'booking' => $booking,
            ], 201);
        }

        return redirect()->route('tenant.bookings.index')
            ->with('success', 'Booking berhasil dibuat. Kami akan segera menghubungi Anda.');
    }

    /**
     * Display the specified booking
     */
    public function show(int $id): View|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();
        $booking = Booking::where('id', $id)
            ->where(function ($query) use ($tenant) {
                $query->where('email', $tenant->email)
                    ->orWhere('phone', $tenant->phone);
            })
            ->with('room')
            ->first();

        if (!$booking) {
            abort(404, 'Booking tidak ditemukan.');
        }

        if (request()->wantsJson()) {
            return response()->json($booking);
        }

        return view('tenant.bookings.show', compact('booking'));
    }

    /**
     * Cancel booking (only if status is still 'new')
     */
    public function cancel(int $id): RedirectResponse|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();
        $booking = Booking::where('id', $id)
            ->where(function ($query) use ($tenant) {
                $query->where('email', $tenant->email)
                    ->orWhere('phone', $tenant->phone);
            })
            ->where('status', Booking::STATUS_NEW)
            ->first();

        if (!$booking) {
            if (request()->wantsJson()) {
                return response()->json([
                    'message' => 'Booking tidak ditemukan atau sudah tidak dapat dibatalkan.'
                ], 404);
            }
            return back()->withErrors(['error' => 'Booking tidak ditemukan atau sudah tidak dapat dibatalkan.']);
        }

        $booking->update(['status' => Booking::STATUS_CANCELLED]);

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Booking berhasil dibatalkan.']);
        }

        return redirect()->route('tenant.bookings.index')
            ->with('success', 'Booking berhasil dibatalkan.');
    }
}
