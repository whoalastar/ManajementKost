<?php

namespace App\Http\Controllers\PublicArea;

use App\Http\Controllers\Controller;
use App\Http\Requests\PublicArea\BookingRequest;
use App\Mail\NewBookingNotification;
use App\Services\PublicService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private PublicService $publicService
    ) {}

    /**
     * Show booking form
     */
    public function create(Request $request): View|JsonResponse
    {
        $roomId = $request->get('room_id');
        $room = $roomId ? $this->publicService->getRoomDetail($roomId) : null;
        $availableRooms = $this->publicService->getAvailableRooms(['per_page' => 100]);
        $contactInfo = $this->publicService->getContactInfo();

        if ($request->wantsJson()) {
            return response()->json([
                'room' => $room,
                'available_rooms' => $availableRooms,
                'contact' => $contactInfo,
            ]);
        }

        return view('public.booking.create', compact('room', 'availableRooms', 'contactInfo'));
    }

    /**
     * Store booking/inquiry
     */
    public function store(BookingRequest $request): RedirectResponse|JsonResponse
    {
        $booking = $this->publicService->createBooking($request->validated());

        // Send notification email to admin (if configured)
        $this->sendAdminNotification($booking);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Terima kasih! Permintaan Anda telah kami terima. Kami akan segera menghubungi Anda.',
                'booking' => $booking,
            ], 201);
        }

        return redirect()->route('public.booking.success')
            ->with('booking', $booking)
            ->with('success', 'Terima kasih! Permintaan Anda telah kami terima. Kami akan segera menghubungi Anda.');
    }

    /**
     * Booking success page
     */
    public function success(): View
    {
        $contactInfo = $this->publicService->getContactInfo();
        return view('public.booking.success', compact('contactInfo'));
    }

    /**
     * Send notification to admin
     */
    private function sendAdminNotification($booking): void
    {
        try {
            $adminEmail = app(\App\Services\SettingService::class)->get('kost_email');
            
            if ($adminEmail) {
                // We'll create this mail class later or use simple notification
                // Mail::to($adminEmail)->send(new NewBookingNotification($booking));
            }
        } catch (\Exception $e) {
            // Log error but don't fail the booking
            \Log::error('Failed to send booking notification: ' . $e->getMessage());
        }
    }
}
