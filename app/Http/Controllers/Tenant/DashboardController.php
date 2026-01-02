<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\TenantPortalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private TenantPortalService $portalService
    ) {}

    /**
     * Tampilkan dashboard tenant
     */
    public function index(): View|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();
        $data = $this->portalService->getDashboardData($tenant);

        if (request()->wantsJson()) {
            return response()->json($data);
        }

        return view('tenant.dashboard', $data);
    }
}
