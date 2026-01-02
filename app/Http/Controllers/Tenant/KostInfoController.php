<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\TenantPortalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KostInfoController extends Controller
{
    public function __construct(
        private TenantPortalService $portalService
    ) {}

    /**
     * Display kost information
     */
    public function index(): View|JsonResponse
    {
        $info = $this->portalService->getKostInfo();

        if (request()->wantsJson()) {
            return response()->json($info);
        }

        return view('tenant.kost-info.index', $info);
    }
}
