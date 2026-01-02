<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    /**
     * Display a listing of bookings
     */
    public function index(Request $request): View|JsonResponse
    {
        $filters = $request->only(['status', 'room_id', 'search', 'date_from', 'date_to', 'sort_by', 'sort_dir', 'per_page']);
        $bookings = $this->bookingService->getFilteredBookings($filters);
        $rooms = Room::all();
        $statuses = Booking::statuses();

        if ($request->wantsJson()) {
            return response()->json($bookings);
        }

        return view('admin.bookings.index', compact('bookings', 'rooms', 'statuses', 'filters'));
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking): View|JsonResponse
    {
        $booking->load('room');

        if (request()->wantsJson()) {
            return response()->json($booking);
        }

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Update booking status
     */
    public function updateStatus(Request $request, Booking $booking): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,survey,deal,cancelled',
            'admin_notes' => 'nullable|string',
        ]);

        $booking = $this->bookingService->updateStatus(
            $booking, 
            $validated['status'], 
            $validated['admin_notes'] ?? null
        );

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Status booking berhasil diperbarui', 'booking' => $booking]);
        }

        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    /**
     * Set survey date
     */
    public function setSurveyDate(Request $request, Booking $booking): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'survey_date' => 'required|date',
        ]);

        $booking = $this->bookingService->setSurveyDate($booking, $validated['survey_date']);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Tanggal survey berhasil diatur', 'booking' => $booking]);
        }

        return back()->with('success', 'Tanggal survey berhasil diatur.');
    }

    /**
     * Convert booking to tenant
     */
    public function convertToTenant(Request $request, Booking $booking): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'nullable|date',
            'id_card_number' => 'nullable|string|max:30',
        ]);

        $tenant = $this->bookingService->convertToTenant($booking, $validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Booking berhasil dikonversi menjadi penghuni', 'tenant' => $tenant], 201);
        }

        return redirect()->route('admin.tenants.show', $tenant)
            ->with('success', 'Booking berhasil dikonversi menjadi penghuni.');
    }

    /**
     * Cancel booking
     */
    public function cancel(Request $request, Booking $booking): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'reason' => 'nullable|string',
        ]);

        $booking = $this->bookingService->cancel($booking, $validated['reason'] ?? null);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Booking berhasil dibatalkan', 'booking' => $booking]);
        }

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * Delete booking
     */
    public function destroy(Booking $booking): RedirectResponse|JsonResponse
    {
        $booking->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Booking berhasil dihapus']);
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking berhasil dihapus.');
    }
}
