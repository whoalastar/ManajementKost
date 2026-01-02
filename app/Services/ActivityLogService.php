<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Log aktivitas ke database
     */
    public static function log(
        string $action,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $description = null
    ): ActivityLog {
        $admin = Auth::guard('admin')->user();

        return ActivityLog::create([
            'admin_id' => $admin?->id,
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'description' => $description,
        ]);
    }

    /**
     * Log aktivitas login
     */
    public static function logLogin(Model $admin): ActivityLog
    {
        return self::log(
            action: 'login',
            model: $admin,
            description: "Admin {$admin->name} berhasil login"
        );
    }

    /**
     * Log aktivitas logout
     */
    public static function logLogout(Model $admin): ActivityLog
    {
        return self::log(
            action: 'logout',
            model: $admin,
            description: "Admin {$admin->name} logout dari sistem"
        );
    }

    /**
     * Log aktivitas create
     */
    public static function logCreate(Model $model, ?string $description = null): ActivityLog
    {
        $modelName = class_basename($model);
        
        return self::log(
            action: 'create',
            model: $model,
            newValues: $model->toArray(),
            description: $description ?? "Membuat {$modelName} baru"
        );
    }

    /**
     * Log aktivitas update
     */
    public static function logUpdate(Model $model, array $oldValues, ?string $description = null): ActivityLog
    {
        $modelName = class_basename($model);
        
        return self::log(
            action: 'update',
            model: $model,
            oldValues: $oldValues,
            newValues: $model->toArray(),
            description: $description ?? "Mengubah data {$modelName}"
        );
    }

    /**
     * Log aktivitas delete
     */
    public static function logDelete(Model $model, ?string $description = null): ActivityLog
    {
        $modelName = class_basename($model);
        
        return self::log(
            action: 'delete',
            model: $model,
            oldValues: $model->toArray(),
            description: $description ?? "Menghapus {$modelName}"
        );
    }

    /**
     * Log aktivitas restore (soft delete)
     */
    public static function logRestore(Model $model, ?string $description = null): ActivityLog
    {
        $modelName = class_basename($model);
        
        return self::log(
            action: 'restore',
            model: $model,
            description: $description ?? "Memulihkan {$modelName}"
        );
    }
}
