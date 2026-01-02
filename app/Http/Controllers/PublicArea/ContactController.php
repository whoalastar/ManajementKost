<?php

namespace App\Http\Controllers\PublicArea;

use App\Http\Controllers\Controller;
use App\Services\PublicService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function __construct(
        private PublicService $publicService
    ) {}

    /**
     * Contact page
     */
    public function index(): View|JsonResponse
    {
        $contactInfo = $this->publicService->getContactInfo();
        $seo = $this->publicService->getSeoData('contact');

        if (request()->wantsJson()) {
            return response()->json([
                'contact' => $contactInfo,
                'seo' => $seo,
            ]);
        }

        return view('public.contact', compact('contactInfo', 'seo'));
    }

    /**
     * Redirect to WhatsApp
     */
    public function whatsapp(): \Illuminate\Http\RedirectResponse
    {
        $contactInfo = $this->publicService->getContactInfo();
        $wa = $contactInfo['whatsapp'];

        if (!$wa) {
            return redirect()->back()->with('error', 'Nomor WhatsApp tidak tersedia.');
        }

        $text = urlencode('Halo, saya tertarik untuk menanyakan tentang kamar kost yang tersedia.');
        
        return redirect("https://wa.me/{$wa}?text={$text}");
    }
}
