<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\TenantPortalService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct(
        private TenantPortalService $portalService
    ) {}

    /**
     * Display a listing of notifications
     */
    public function index(Request $request): View|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();
        $filters = $request->only(['unread_only', 'per_page']);
        $notifications = $this->portalService->getNotifications($tenant, $filters);

        if ($request->wantsJson()) {
            return response()->json($notifications);
        }

        return view('tenant.notifications.index', compact('notifications', 'filters'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(int $id): RedirectResponse|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();
        $marked = $this->portalService->markNotificationAsRead($tenant, $id);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => $marked,
                'message' => $marked ? 'Notifikasi ditandai sudah dibaca.' : 'Notifikasi tidak ditemukan.'
            ]);
        }

        return back();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(): RedirectResponse|JsonResponse
    {
        $tenant = Auth::guard('tenant')->user();
        $count = $this->portalService->markAllNotificationsAsRead($tenant);

        if (request()->wantsJson()) {
            return response()->json([
                'message' => "{$count} notifikasi ditandai sudah dibaca."
            ]);
        }

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
