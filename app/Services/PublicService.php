<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Facility;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Setting;

class PublicService
{
    /**
     * Get landing page data
     */
    public function getLandingPageData(): array
    {
        $settingService = app(SettingService::class);
        $profile = $settingService->getProfileSettings();
        $rules = $settingService->getKostRules();

        return [
            'kost' => [
                'name' => $profile['kost_name'] ?? '',
                'description' => $profile['kost_description'] ?? '',
                'address' => $profile['kost_address'] ?? '',
                'phone' => $profile['kost_phone'] ?? '',
                'email' => $profile['kost_email'] ?? '',
                'logo' => $profile['kost_logo'] ?? '',
            ],
            'facilities' => Facility::where('type', Facility::TYPE_SHARED)
                ->orderBy('name')
                ->get(),
            'featured_rooms' => $this->getFeaturedRooms(6),
            'stats' => $this->getPublicStats(),
            'rules' => $rules['rules'] ?? [],
        ];
    }

    /**
     * Get available rooms with filters
     */
    public function getAvailableRooms(array $filters = [])
    {
        $query = Room::with(['roomType', 'photos', 'facilities'])
            ->where('status', Room::STATUS_EMPTY);

        // Filter by price range
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }

        // Filter by room type
        if (!empty($filters['room_type_id'])) {
            $query->where('room_type_id', $filters['room_type_id']);
        }

        // Filter by floor
        if (!empty($filters['floor'])) {
            $query->where('floor', $filters['floor']);
        }

        // Sorting
        $sortBy = $filters['sort_by'] ?? 'price';
        $sortDir = $filters['sort_dir'] ?? 'asc';

        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('price', 'asc');
        }

        return $query->paginate($filters['per_page'] ?? 12);
    }

    /**
     * Get room detail for public view
     */
    public function getRoomDetail(int $roomId): ?Room
    {
        return Room::with(['roomType', 'photos', 'facilities'])
            ->find($roomId);
    }

    /**
     * Get room detail by code
     */
    public function getRoomByCode(string $code): ?Room
    {
        return Room::with(['roomType', 'photos', 'facilities'])
            ->where('code', $code)
            ->first();
    }

    /**
     * Get featured rooms (available, with photos, sorted by price)
     */
    public function getFeaturedRooms(int $limit = 6)
    {
        return Room::with(['roomType', 'photos', 'facilities'])
            ->where('status', Room::STATUS_EMPTY)
            ->has('photos')
            ->orderBy('price', 'asc')
            ->take($limit)
            ->get();
    }

    /**
     * Get all room types
     */
    public function getRoomTypes()
    {
        return RoomType::withCount(['rooms' => function ($query) {
            $query->where('status', Room::STATUS_EMPTY);
        }])->get();
    }

    /**
     * Get floors list
     */
    public function getFloors(): array
    {
        return Room::where('status', Room::STATUS_EMPTY)
            ->distinct('floor')
            ->orderBy('floor')
            ->pluck('floor')
            ->toArray();
    }

    /**
     * Get price range
     */
    public function getPriceRange(): array
    {
        return [
            'min' => Room::where('status', Room::STATUS_EMPTY)->min('price') ?? 0,
            'max' => Room::where('status', Room::STATUS_EMPTY)->max('price') ?? 0,
        ];
    }

    /**
     * Get public stats
     */
    public function getPublicStats(): array
    {
        return [
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('status', Room::STATUS_EMPTY)->count(),
            'room_types' => RoomType::count(),
        ];
    }

    /**
     * Create booking/inquiry
     */
    public function createBooking(array $data): Booking
    {
        return Booking::create([
            'room_id' => $data['room_id'] ?? null,
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'],
            'occupation' => $data['occupation'] ?? null,
            'planned_check_in' => $data['planned_check_in'] ?? null,
            'message' => $data['message'] ?? null,
            'status' => Booking::STATUS_NEW,
        ]);
    }

    /**
     * Get shared facilities
     */
    public function getSharedFacilities()
    {
        return Facility::where('type', Facility::TYPE_SHARED)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get contact info
     */
    public function getContactInfo(): array
    {
        $settingService = app(SettingService::class);
        $profile = $settingService->getProfileSettings();

        return [
            'phone' => $profile['kost_phone'] ?? '',
            'email' => $profile['kost_email'] ?? '',
            'address' => $profile['kost_address'] ?? '',
            'whatsapp' => $this->formatWhatsApp($profile['kost_phone'] ?? ''),
        ];
    }

    /**
     * Format phone number for WhatsApp link
     */
    private function formatWhatsApp(string $phone): string
    {
        // Remove non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Convert 08xx to 628xx
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        return $phone;
    }

    /**
     * Get kost rules
     */
    public function getKostRules(): array
    {
        $settingService = app(SettingService::class);
        $rules = $settingService->getKostRules();

        return $rules['rules'] ?? [];
    }

    /**
     * Get SEO data for pages
     */
    public function getSeoData(string $page = 'home'): array
    {
        $settingService = app(SettingService::class);
        $profile = $settingService->getProfileSettings();
        $kostName = $profile['kost_name'] ?? 'Kost';

        return match ($page) {
            'home' => [
                'title' => $kostName . ' - Kost Nyaman dan Terjangkau',
                'description' => $profile['kost_description'] ?? 'Temukan kamar kost nyaman dengan fasilitas lengkap dan harga terjangkau.',
                'keywords' => 'kost, sewa kamar, kamar kost, ' . ($profile['kost_address'] ?? ''),
            ],
            'rooms' => [
                'title' => 'Daftar Kamar Tersedia - ' . $kostName,
                'description' => 'Lihat daftar kamar kost yang tersedia dengan berbagai tipe dan harga.',
                'keywords' => 'kamar kost tersedia, sewa kamar, harga kost',
            ],
            'contact' => [
                'title' => 'Hubungi Kami - ' . $kostName,
                'description' => 'Hubungi kami untuk informasi lebih lanjut tentang kamar kost yang tersedia.',
                'keywords' => 'kontak kost, hubungi kost',
            ],
            default => [
                'title' => $kostName,
                'description' => $profile['kost_description'] ?? '',
                'keywords' => 'kost, sewa kamar',
            ],
        };
    }

    /**
     * Get location info
     */
    public function getLocationInfo(): array
    {
        $settingService = app(SettingService::class);
        $location = $settingService->getLocationSettings();
        $profile = $settingService->getProfileSettings();

        return [
            'address' => $profile['kost_address'] ?? '',
            'google_maps_embed' => $location['google_maps_embed'] ?? '',
            'google_maps_link' => $location['google_maps_link'] ?? '',
            'latitude' => $location['latitude'] ?? '',
            'longitude' => $location['longitude'] ?? '',
        ];
    }

    /**
     * Get operating hours
     */
    public function getOperatingHours(): array
    {
        $settingService = app(SettingService::class);
        $public = $settingService->getPublicSettings();

        return [
            'visiting_hours' => $public['visiting_hours'] ?? '08:00 - 21:00',
            'survey_hours' => $public['survey_hours'] ?? '09:00 - 17:00',
        ];
    }

    /**
     * Get complete public info (for about/info page)
     */
    public function getCompleteInfo(): array
    {
        return [
            'kost' => $this->getLandingPageData()['kost'],
            'facilities' => $this->getSharedFacilities(),
            'rules' => $this->getKostRules(),
            'contact' => $this->getContactInfo(),
            'location' => $this->getLocationInfo(),
            'hours' => $this->getOperatingHours(),
        ];
    }
}
