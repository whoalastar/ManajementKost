@extends('admin.layouts.app')

@section('title', 'Kamar')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
</li>
<li class="font-medium text-gray-800">Kamar</li>
@endsection

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Kamar</h1>
        <p class="mt-1 text-gray-500">Kelola semua kamar kost</p>
    </div>
    <a href="{{ route('admin.rooms.create') }}" 
       class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-xl transition-all duration-300">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        <span>Tambah Kamar</span>
    </a>
</div>
@endsection

@section('content')
<!-- Stats Summary -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $rooms->total() }}</p>
                <p class="text-sm text-gray-500">Total</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $rooms->where('status', 'empty')->count() }}</p>
                <p class="text-sm text-gray-500">Kosong</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $rooms->where('status', 'occupied')->count() }}</p>
                <p class="text-sm text-gray-500">Terisi</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $rooms->where('status', 'maintenance')->count() }}</p>
                <p class="text-sm text-gray-500">Maintenance</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.rooms.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
        <!-- Search -->
        <div class="relative flex-1 min-w-[200px]">
            <input type="text" 
                   name="search"
                   value="{{ $filters['search'] ?? '' }}"
                   placeholder="Cari kamar..." 
                   class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        
        <!-- Status Filter -->
        <select name="status" class="px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
            <option value="">Semua Status</option>
            <option value="empty" {{ ($filters['status'] ?? '') == 'empty' ? 'selected' : '' }}>Kosong</option>
            <option value="occupied" {{ ($filters['status'] ?? '') == 'occupied' ? 'selected' : '' }}>Terisi</option>
            <option value="maintenance" {{ ($filters['status'] ?? '') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
        </select>
        
        <!-- Room Type Filter -->
        <select name="room_type_id" class="px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
            <option value="">Semua Tipe</option>
            @foreach($roomTypes as $type)
            <option value="{{ $type->id }}" {{ ($filters['room_type_id'] ?? '') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
            @endforeach
        </select>
        
        <!-- Floor Filter -->
        <select name="floor" class="px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
            <option value="">Semua Lantai</option>
            @foreach($floors as $floor)
            <option value="{{ $floor }}" {{ ($filters['floor'] ?? '') == $floor ? 'selected' : '' }}>Lantai {{ $floor }}</option>
            @endforeach
        </select>
        
        <button type="submit" class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-xl transition-colors">
            Filter
        </button>
        
        @if(!empty(array_filter($filters ?? [])))
        <a href="{{ route('admin.rooms.index') }}" class="px-4 py-2.5 text-gray-600 hover:text-gray-800 text-sm font-medium">
            Reset
        </a>
        @endif
    </form>
</div>

<!-- Room Cards Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($rooms as $room)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg hover:border-primary-100 transition-all duration-300 group">
        <!-- Room Image -->
        <div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
            @if($room->primaryPhoto)
                <img src="{{ asset($room->primaryPhoto->path) }}" 
                     alt="{{ $room->name }}" 
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
            @endif
            
            <!-- Status Badge -->
            <div class="absolute top-3 left-3">
                <span class="px-3 py-1 text-xs font-semibold rounded-full shadow-sm
                    @if($room->status === 'empty') bg-green-500 text-white
                    @elseif($room->status === 'occupied') bg-orange-500 text-white
                    @else bg-red-500 text-white @endif">
                    @if($room->status === 'empty') Kosong
                    @elseif($room->status === 'occupied') Terisi
                    @else Maintenance @endif
                </span>
            </div>
            
            <!-- Quick Actions -->
            <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                <div class="flex gap-1">
                    <a href="{{ route('admin.rooms.show', $room) }}" class="p-2 bg-white/90 hover:bg-white rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </a>
                    <a href="{{ route('admin.rooms.edit', $room) }}" class="p-2 bg-white/90 hover:bg-white rounded-lg shadow-sm transition">
                        <svg class="w-4 h-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Room Info -->
        <div class="p-4">
            <div class="flex items-start justify-between mb-2">
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $room->name }}</h3>
                    <p class="text-sm text-gray-500">Kode: {{ $room->code }}</p>
                </div>
                <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">Lt. {{ $room->floor }}</span>
            </div>
            
            <p class="text-lg font-bold text-primary-600 mb-3">Rp {{ number_format($room->price, 0, ',', '.') }}<span class="text-sm font-normal text-gray-500">/bulan</span></p>
            
            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                <span class="text-xs text-gray-500">{{ $room->roomType->name ?? 'Tanpa Tipe' }}</span>
                <div class="flex gap-1">
                    <form id="delete-room-{{ $room->id }}" action="{{ route('admin.rooms.destroy', $room) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" 
                                onclick="confirmDelete('delete-room-{{ $room->id }}')"
                                class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum ada kamar</h3>
            <p class="text-gray-500 mb-6">Mulai dengan menambahkan kamar pertama</p>
            <a href="{{ route('admin.rooms.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kamar
            </a>
        </div>
    </div>
    @endforelse
</div>

<!-- Pagination -->
@if($rooms->hasPages())
<div class="mt-6">
    {{ $rooms->withQueryString()->links('admin.components.pagination') }}
</div>
@endif
@endsection
