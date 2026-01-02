<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = ActivityLog::with('admin');

        if ($request->has('action')) {
            $query->byAction($request->action);
        }

        if ($request->has('model_type')) {
            $query->byModelType($request->model_type);
        }

        if ($request->has('admin_id')) {
            $query->where('admin_id', $request->admin_id);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->orderByDesc('created_at')
            ->paginate($request->get('per_page', 20));

        // Get available filters
        $actions = ActivityLog::distinct('action')->pluck('action');
        $modelTypes = ActivityLog::distinct('model_type')
            ->whereNotNull('model_type')
            ->pluck('model_type')
            ->map(fn($type) => class_basename($type));

        if ($request->wantsJson()) {
            return response()->json($logs);
        }

        return view('admin.activity-logs.index', compact('logs', 'actions', 'modelTypes'));
    }

    /**
     * Display the specified log detail
     */
    public function show(ActivityLog $activityLog): View|JsonResponse
    {
        $activityLog->load('admin');

        if (request()->wantsJson()) {
            return response()->json($activityLog);
        }

        return view('admin.activity-logs.show', compact('activityLog'));
    }
}
