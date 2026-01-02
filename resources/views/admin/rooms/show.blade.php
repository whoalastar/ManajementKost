@extends('admin.layouts.app')

@section('title', 'Detail Kamar')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="flex items-center">
    <a href="{{ route('admin.rooms.index') }}" class="text-gray-500 hover:text-primary-600">Kamar</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="font-medium text-gray-800">{{ $room->name }}</li>
@endsection

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.rooms.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $room->name }}</h1>
            <p class="mt-1 text-gray-500">Kode: {{ $room->code }} • Lantai {{ $room->floor }}</p>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.rooms.edit', $room) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Photo Gallery -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($room->photos->count() > 0)
            <div class="grid grid-cols-3 gap-1">
                @foreach($room->photos->take(6) as $index => $photo)
                <div class="{{ $index === 0 ? 'col-span-2 row-span-2' : '' }} aspect-square bg-gray-100 relative overflow-hidden">
                    <img src="{{ asset($photo->path) }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" alt="Foto kamar">
                    @if($photo->is_primary)
                    <span class="absolute top-2 left-2 px-2 py-1 bg-green-500 text-white text-xs font-medium rounded-lg">Utama</span>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="h-64 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-gray-500">Belum ada foto</p>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Room Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Informasi Kamar</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <dt class="text-sm text-gray-500">Tipe Kamar</dt>
                        <dd class="mt-1 font-semibold text-gray-900">{{ $room->roomType->name ?? 'Tidak ada' }}</dd>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <dt class="text-sm text-gray-500">Lantai</dt>
                        <dd class="mt-1 font-semibold text-gray-900">{{ $room->floor }}</dd>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <dt class="text-sm text-gray-500">Harga Sewa</dt>
                        <dd class="mt-1 font-semibold text-primary-600">Rp {{ number_format($room->price, 0, ',', '.') }}/bulan</dd>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <dt class="text-sm text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 text-sm font-medium rounded-full
                                @if($room->status === 'empty') bg-green-100 text-green-700
                                @elseif($room->status === 'occupied') bg-orange-100 text-orange-700
                                @else bg-red-100 text-red-700 @endif">
                                @if($room->status === 'empty') Kosong
                                @elseif($room->status === 'occupied') Terisi
                                @else Maintenance @endif
                            </span>
                        </dd>
                    </div>
                </dl>
                
                @if($room->description)
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Deskripsi</h4>
                    <p class="text-gray-600">{{ $room->description }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Facilities -->
        @if($room->facilities->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Fasilitas</h3>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap gap-2">
                    @foreach($room->facilities as $facility)
                    <span class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm">
                        @if($facility->icon)
                            <span>{!! $facility->icon !!}</span>
                        @endif
                        {{ $facility->name }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Current Tenant -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Penghuni Saat Ini</h3>
            </div>
            <div class="p-6">
                @if($room->currentTenant)
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center text-white font-semibold text-lg">
                        {{ strtoupper(substr($room->currentTenant->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $room->currentTenant->name }}</p>
                        <p class="text-sm text-gray-500">{{ $room->currentTenant->phone }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="text-sm">
                        <span class="text-gray-500">Check-in:</span>
                        <span class="font-medium text-gray-900">{{ $room->currentTenant->check_in_date?->format('d M Y') ?? '-' }}</span>
                    </div>
                </div>
                <a href="{{ route('admin.tenants.show', $room->currentTenant) }}" class="mt-4 w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary-50 text-primary-600 font-medium rounded-xl hover:bg-primary-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Lihat Detail
                </a>
                @else
                <div class="text-center py-4">
                    <div class="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <p class="text-gray-500 text-sm">Belum ada penghuni</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Quick Status Update -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Update Status</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.rooms.update-status', $room) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500">
                        <option value="empty" {{ $room->status === 'empty' ? 'selected' : '' }}>Kosong</option>
                        <option value="occupied" {{ $room->status === 'occupied' ? 'selected' : '' }}>Terisi</option>
                        <option value="maintenance" {{ $room->status === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors">
                        Update Status
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Recent Maintenance -->
        @if($room->maintenanceReports->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Riwayat Maintenance</h3>
                <a href="{{ route('admin.maintenance.room-history', $room) }}" class="text-sm text-primary-600 hover:text-primary-700">Lihat Semua</a>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($room->maintenanceReports->take(3) as $report)
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <p class="font-medium text-gray-900 line-clamp-1">{{ $report->title }}</p>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($report->status === 'new') bg-red-100 text-red-700
                            @elseif($report->status === 'in_progress') bg-yellow-100 text-yellow-700
                            @else bg-green-100 text-green-700 @endif">
                            {{ $report->status === 'new' ? 'Baru' : ($report->status === 'in_progress' ? 'Proses' : 'Selesai') }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">{{ $report->created_at->format('d M Y') }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
