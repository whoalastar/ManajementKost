<?php

namespace App\Http\Controllers\PublicArea;

use App\Http\Controllers\Controller;
use App\Services\PublicService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        private PublicService $publicService
    ) {}

    /**
     * Landing page / Homepage
     */
    public function index(): View|JsonResponse
    {
        $data = $this->publicService->getLandingPageData();
        $seo = $this->publicService->getSeoData('home');

        if (request()->wantsJson()) {
            return response()->json(array_merge($data, ['seo' => $seo]));
        }

        return view('public.home', array_merge($data, ['seo' => $seo]));
    }

    /**
     * About page
     */
    public function about(): View|JsonResponse
    {
        $data = $this->publicService->getLandingPageData();
        $seo = $this->publicService->getSeoData('about');

        if (request()->wantsJson()) {
            return response()->json($data);
        }

        return view('public.about', array_merge($data, ['seo' => $seo]));
    }
}
