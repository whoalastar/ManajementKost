<?php

namespace App\Http\Controllers\PublicArea;

use App\Http\Controllers\Controller;
use App\Services\PublicService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class InfoController extends Controller
{
    public function __construct(
        private PublicService $publicService
    ) {}

    /**
     * Kost rules page
     */
    public function rules(): View|JsonResponse
    {
        $rules = $this->publicService->getKostRules();
        $contactInfo = $this->publicService->getContactInfo();

        $seo = [
            'title' => 'Aturan Kost',
            'description' => 'Aturan dan ketentuan untuk penghuni kost.',
        ];

        if (request()->wantsJson()) {
            return response()->json([
                'rules' => $rules,
                'contact' => $contactInfo,
                'seo' => $seo,
            ]);
        }

        return view('public.info.rules', compact('rules', 'contactInfo', 'seo'));
    }

    /**
     * Facilities page
     */
    public function facilities(): View|JsonResponse
    {
        $facilities = $this->publicService->getSharedFacilities();
        $contactInfo = $this->publicService->getContactInfo();

        $seo = [
            'title' => 'Fasilitas Kost',
            'description' => 'Daftar fasilitas yang tersedia di kost.',
        ];

        if (request()->wantsJson()) {
            return response()->json([
                'facilities' => $facilities,
                'contact' => $contactInfo,
                'seo' => $seo,
            ]);
        }

        return view('public.info.facilities', compact('facilities', 'contactInfo', 'seo'));
    }
}
