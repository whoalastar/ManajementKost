<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\TenantPortalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HistoryController extends Controller
{
    public function __construct(
        private TenantPortalService $portalService
    ) {}

    /**
     * Display tenant history
     */
    public function index(): View|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();
        $history = $this->portalService->getHistory($tenant);

        if (request()->wantsJson()) {
            return response()->json($history);
        }

        return view('tenant.history.index', $history);
    }
}
