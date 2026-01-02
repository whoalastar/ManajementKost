<?php

namespace App\Http\Controllers\PublicArea;

use App\Http\Controllers\Controller;
use App\Services\PublicService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function __construct(
        private PublicService $publicService
    ) {}

    /**
     * Display list of available rooms
     */
    public function index(Request $request): View|JsonResponse
    {
        $filters = $request->only([
            'min_price',
            'max_price',
            'room_type_id',
            'floor',
            'sort_by',
            'per_page'
        ]);

        $rooms = $this->publicService->getAvailableRooms($filters);
        $roomTypes = $this->publicService->getRoomTypes();
        $floors = $this->publicService->getFloors();
        $priceRange = $this->publicService->getPriceRange();
        $seo = $this->publicService->getSeoData('rooms');

        if ($request->wantsJson()) {
            return response()->json([
                'rooms' => $rooms,
                'filters' => [
                    'room_types' => $roomTypes,
                    'floors' => $floors,
                    'price_range' => $priceRange,
                ],
                'seo' => $seo,
            ]);
        }

        return view('public.rooms.index', compact(
            'rooms',
            'roomTypes',
            'floors',
            'priceRange',
            'filters',
            'seo'
        ));
    }

    /**
     * Display room detail
     */
    public function show(int $id): View|JsonResponse
    {
        $room = $this->publicService->getRoomDetail($id);

        if (!$room) {
            abort(404, 'Kamar tidak ditemukan.');
        }

        $sharedFacilities = $this->publicService->getSharedFacilities();
        $relatedRooms = $this->publicService->getFeaturedRooms(4);
        $contactInfo = $this->publicService->getContactInfo();

        // SEO data for room detail
        $seo = [
            'title' => $room->name . ' - ' . ($room->roomType?->name ?? 'Kamar'),
            'description' => $room->description ?? 'Kamar ' . $room->code . ' dengan harga Rp ' . number_format($room->price, 0, ',', '.') . '/bulan',
            'keywords' => 'kamar kost, ' . $room->code . ', ' . ($room->roomType?->name ?? ''),
        ];

        if (request()->wantsJson()) {
            return response()->json([
                'room' => $room,
                'shared_facilities' => $sharedFacilities,
                'related_rooms' => $relatedRooms,
                'contact' => $contactInfo,
                'seo' => $seo,
            ]);
        }

        return view('public.rooms.show', compact(
            'room',
            'sharedFacilities',
            'relatedRooms',
            'contactInfo',
            'seo'
        ));
    }

    /**
     * Display room detail by code (SEO-friendly URL)
     */
    public function showByCode(string $code): View|JsonResponse
    {
        $room = $this->publicService->getRoomByCode($code);

        if (!$room) {
            abort(404, 'Kamar tidak ditemukan.');
        }

        return $this->show($room->id);
    }
}
