<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TenantController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes khusus untuk admin panel.
| Semua routes di sini sudah memiliki prefix 'admin' dan name 'admin.'
|
*/

// Guest routes (belum login)
Route::middleware('admin.guest')->group(function () {
    // Login
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Forgot Password
    Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])
        ->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    // Reset Password
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

// Authenticated routes (sudah login)
Route::middleware('admin.auth')->group(function () {
    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('dashboard/chart-data', [DashboardController::class, 'chartData'])->name('dashboard.chart-data');

    // Room Types
    Route::resource('room-types', RoomTypeController::class);

    // Rooms
    Route::patch('rooms/{room}/status', [RoomController::class, 'updateStatus'])->name('rooms.update-status');
    Route::delete('rooms/photos/{photo}', [RoomController::class, 'deletePhoto'])->name('rooms.delete-photo');
    Route::post('rooms/{room}/photos/{photo}/primary', [RoomController::class, 'setPrimaryPhoto'])->name('rooms.set-primary-photo');
    Route::resource('rooms', RoomController::class);

    // Facilities
    Route::resource('facilities', FacilityController::class);

    // Tenants
    Route::get('tenants/archived', [TenantController::class, 'archived'])->name('tenants.archived');
    Route::post('tenants/{tenant}/assign-room', [TenantController::class, 'assignRoom'])->name('tenants.assign-room');
    Route::post('tenants/{tenant}/move-room', [TenantController::class, 'moveRoom'])->name('tenants.move-room');
    Route::post('tenants/{tenant}/checkout', [TenantController::class, 'checkout'])->name('tenants.checkout');
    Route::resource('tenants', TenantController::class);

    // Bookings
    Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::post('bookings/{booking}/survey-date', [BookingController::class, 'setSurveyDate'])->name('bookings.survey-date');
    Route::post('bookings/{booking}/convert', [BookingController::class, 'convertToTenant'])->name('bookings.convert');
    Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::resource('bookings', BookingController::class)->only(['index', 'show', 'destroy']);

    // Invoices
    Route::post('invoices/generate-monthly', [InvoiceController::class, 'generateMonthly'])->name('invoices.generate-monthly');
    Route::post('invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
    Route::post('invoices/{invoice}/resend', [InvoiceController::class, 'resend'])->name('invoices.resend');
    Route::get('invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markAsPaid'])->name('invoices.mark-paid');
    Route::resource('invoices', InvoiceController::class);

    // Payments
    Route::resource('payment-methods', PaymentMethodController::class);
    Route::post('payments/{payment}/verify', [PaymentController::class, 'verify'])->name('payments.verify');
    Route::post('payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');
    Route::post('payments/{payment}/upload-proof', [PaymentController::class, 'uploadProof'])->name('payments.upload-proof');
    Route::get('payments/summary', [PaymentController::class, 'summary'])->name('payments.summary');
    Route::resource('payments', PaymentController::class)->except(['edit', 'update']);

    // Maintenance
    Route::patch('maintenance/{maintenance}/status', [MaintenanceController::class, 'updateStatus'])->name('maintenance.update-status');
    Route::post('maintenance/{maintenance}/notes', [MaintenanceController::class, 'addNotes'])->name('maintenance.add-notes');
    Route::get('maintenance/room/{room}/history', [MaintenanceController::class, 'roomHistory'])->name('maintenance.room-history');
    Route::resource('maintenance', MaintenanceController::class);

    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
        Route::get('income', [ReportController::class, 'income'])->name('income');
        Route::get('income/export-pdf', [ReportController::class, 'incomeExportPdf'])->name('income.export-pdf');
        Route::get('arrears', [ReportController::class, 'arrears'])->name('arrears');
        Route::get('arrears/export-pdf', [ReportController::class, 'arrearsExportPdf'])->name('arrears.export-pdf');
        Route::get('occupancy', [ReportController::class, 'occupancy'])->name('occupancy');
        Route::get('occupancy/export-pdf', [ReportController::class, 'occupancyExportPdf'])->name('occupancy.export-pdf');
        Route::get('maintenance', [ReportController::class, 'maintenance'])->name('maintenance');
        Route::get('maintenance/export-pdf', [ReportController::class, 'maintenanceExportPdf'])->name('maintenance.export-pdf');
    });

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::put('/', [SettingController::class, 'update'])->name('update');
        Route::post('profile', [SettingController::class, 'updateProfile'])->name('profile');
        Route::post('logo', [SettingController::class, 'uploadLogo'])->name('logo');
        Route::post('payment', [SettingController::class, 'updatePayment'])->name('payment');
        Route::post('invoice', [SettingController::class, 'updateInvoice'])->name('invoice');
        Route::post('email', [SettingController::class, 'updateEmail'])->name('email');
        Route::post('rules', [SettingController::class, 'updateRules'])->name('rules');
        Route::post('backup', [SettingController::class, 'backup'])->name('backup');
    });

    // Activity Logs
    Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'show']);
});
