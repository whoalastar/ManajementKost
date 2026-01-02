@extends('admin.layouts.app')

@section('title', 'Detail Booking')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="flex items-center">
    <a href="{{ route('admin.bookings.index') }}" class="text-gray-500 hover:text-primary-600">Booking</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="font-medium text-gray-800">Detail</li>
@endsection

@section('header')
<div class="flex items-center gap-4">
    <a href="{{ route('admin.bookings.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Detail Booking</h1>
        <p class="mt-1 text-gray-500">{{ $booking->name }}</p>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Info Calon Penghuni -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Informasi Calon Penghuni</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <dt class="text-sm text-gray-500">Nama Lengkap</dt>
                        <dd class="mt-1 font-semibold text-gray-900">{{ $booking->name }}</dd>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <dt class="text-sm text-gray-500">No. HP</dt>
                        <dd class="mt-1 font-semibold text-gray-900">{{ $booking->phone }}</dd>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <dt class="text-sm text-gray-500">Email</dt>
                        <dd class="mt-1 font-semibold text-gray-900">{{ $booking->email ?? '-' }}</dd>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <dt class="text-sm text-gray-500">Tanggal Booking</dt>
                        <dd class="mt-1 font-semibold text-gray-900">{{ $booking->created_at->format('d M Y H:i') }}</dd>
                    </div>
                </dl>
                
                @if($booking->message)
                <div class="mt-6 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                    <h4 class="font-medium text-blue-900 mb-2">Pesan dari Calon Penghuni</h4>
                    <p class="text-blue-800">{{ $booking->message }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Kamar yang Diminati -->
        @if($booking->room)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Kamar yang Diminati</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl overflow-hidden">
                        @if($booking->room->primaryPhoto)
                        <img src="{{ Storage::url($booking->room->primaryPhoto->path) }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $booking->room->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $booking->room->roomType->name ?? 'Standard' }} • Lantai {{ $booking->room->floor }}</p>
                        <p class="mt-1 text-lg font-bold text-primary-600">Rp {{ number_format($booking->room->price, 0, ',', '.') }}/bulan</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Admin Notes -->
        @if($booking->admin_notes)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Catatan Admin</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-700">{{ $booking->admin_notes }}</p>
            </div>
        </div>
        @endif
    </div>
    
    <div class="space-y-6">
        <!-- Status -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Status Booking</h3>
            </div>
            <div class="p-6">
                <div class="text-center mb-6">
                    <span class="px-4 py-2 text-sm font-semibold rounded-full
                        @if($booking->status === 'new') bg-blue-100 text-blue-700
                        @elseif($booking->status === 'contacted') bg-yellow-100 text-yellow-700
                        @elseif($booking->status === 'survey') bg-purple-100 text-purple-700
                        @elseif($booking->status === 'deal') bg-green-100 text-green-700
                        @else bg-red-100 text-red-700 @endif">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
                
                @if($booking->status !== 'cancelled' && $booking->status !== 'deal')
                <form action="{{ route('admin.bookings.update-status', $booking) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Update Status</label>
                        <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500">
                            <option value="new" {{ $booking->status === 'new' ? 'selected' : '' }}>Baru</option>
                            <option value="contacted" {{ $booking->status === 'contacted' ? 'selected' : '' }}>Dihubungi</option>
                            <option value="survey" {{ $booking->status === 'survey' ? 'selected' : '' }}>Survey</option>
                            <option value="deal" {{ $booking->status === 'deal' ? 'selected' : '' }}>Deal</option>
                            <option value="cancelled" {{ $booking->status === 'cancelled' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea name="admin_notes" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 resize-none">{{ $booking->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors">
                        Update Status
                    </button>
                </form>
                @endif
            </div>
        </div>
        
        <!-- Survey Date -->
        @if($booking->status === 'survey' || $booking->status === 'contacted')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Jadwal Survey</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.bookings.survey-date', $booking) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Survey</label>
                        <input type="datetime-local" name="survey_date" value="{{ $booking->survey_date?->format('Y-m-d\TH:i') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500">
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-xl transition-colors">
                        Set Jadwal
                    </button>
                </form>
            </div>
        </div>
        @endif
        
        <!-- Convert to Tenant -->
        @if($booking->status === 'deal')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Konversi ke Penghuni</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.bookings.convert', $booking) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kamar <span class="text-red-500">*</span></label>
                        <select name="room_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500">
                            <option value="{{ $booking->room_id }}">{{ $booking->room->name ?? 'Pilih Kamar' }}</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Check-in</label>
                        <input type="date" name="check_in_date" value="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. KTP</label>
                        <input type="text" name="id_card_number"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500" placeholder="16 digit">
                    </div>
                    <button type="submit" class="w-full py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition-colors">
                        Konversi ke Penghuni
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
