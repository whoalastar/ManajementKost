<?php

use App\Http\Controllers\PublicArea\ContactController;
use App\Http\Controllers\PublicArea\HomeController;
use App\Http\Controllers\PublicArea\InfoController;
use App\Http\Controllers\PublicArea\RoomController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
|
| Routes untuk halaman publik (landing page, daftar kamar).
| Semua routes di sini memiliki name prefix 'public.'
| 
| Note: Booking memerlukan login, redirect ke tenant portal.
|
*/

Route::name('public.')->group(function () {
    // Home / Landing Page
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/about', [HomeController::class, 'about'])->name('about');

    // Rooms
    Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
    Route::get('/rooms/{id}', [RoomController::class, 'show'])
        ->where('id', '[0-9]+')
        ->name('rooms.show');
    Route::get('/room/{code}', [RoomController::class, 'showByCode'])->name('rooms.show-by-code');

    // Booking redirect - requires login
    Route::get('/booking', function () {
        // Jika sudah login, langsung ke form booking
        if (Auth::guard('tenant')->check()) {
            return redirect()->route('tenant.bookings.create');
        }

        // Jika belum login, set intended URL ke form booking
        session()->put('url.intended', route('tenant.bookings.create'));

        // Redirect ke halaman login
        return redirect()->route('tenant.login')
            ->with('info', 'Silakan login atau daftar terlebih dahulu untuk melakukan booking.');
    })->name('booking');

    // Contact
    Route::get('/contact', [ContactController::class, 'index'])->name('contact');
    Route::get('/whatsapp', [ContactController::class, 'whatsapp'])->name('whatsapp');

    // Info
    Route::get('/rules', [InfoController::class, 'rules'])->name('rules');
    Route::get('/facilities', [InfoController::class, 'facilities'])->name('facilities');
});
