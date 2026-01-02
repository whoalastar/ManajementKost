@extends('admin.layouts.app')

@section('title', 'Pengaduan & Maintenance')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="font-medium text-gray-800">Maintenance</li>
@endsection

@section('header')
<div>
    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Pengaduan & Maintenance</h1>
    <p class="mt-1 text-gray-500">Kelola laporan kerusakan dari penghuni</p>
</div>
@endsection

@section('content')
<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-4 border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $reports->where('status', 'new')->count() }}</p>
                <p class="text-sm text-gray-500">Baru</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $reports->where('status', 'in_progress')->count() }}</p>
                <p class="text-sm text-gray-500">Diproses</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $reports->where('status', 'completed')->count() }}</p>
                <p class="text-sm text-gray-500">Selesai</p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-4 border border-gray-100">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $reports->total() }}</p>
                <p class="text-sm text-gray-500">Total</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.maintenance.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari laporan..." 
                   class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        
        <select name="status" class="px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
            <option value="">Semua Status</option>
            <option value="new" {{ ($filters['status'] ?? '') == 'new' ? 'selected' : '' }}>Baru</option>
            <option value="in_progress" {{ ($filters['status'] ?? '') == 'in_progress' ? 'selected' : '' }}>Diproses</option>
            <option value="completed" {{ ($filters['status'] ?? '') == 'completed' ? 'selected' : '' }}>Selesai</option>
        </select>
        
        <select name="priority" class="px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
            <option value="">Semua Prioritas</option>
            <option value="low" {{ ($filters['priority'] ?? '') == 'low' ? 'selected' : '' }}>Rendah</option>
            <option value="medium" {{ ($filters['priority'] ?? '') == 'medium' ? 'selected' : '' }}>Sedang</option>
            <option value="high" {{ ($filters['priority'] ?? '') == 'high' ? 'selected' : '' }}>Tinggi</option>
        </select>
        
        <button type="submit" class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-xl transition-colors">Filter</button>
        
        @if(!empty(array_filter($filters ?? [])))
        <a href="{{ route('admin.maintenance.index') }}" class="px-4 py-2.5 text-gray-600 hover:text-gray-800 text-sm font-medium">Reset</a>
        @endif
    </form>
</div>

<div class="space-y-4">
    @forelse($reports as $report)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
        <div class="p-6">
            <div class="flex items-start justify-between gap-4">
                <div class="flex gap-4">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0
                        @if($report->priority === 'high') bg-red-100 text-red-600
                        @elseif($report->priority === 'medium') bg-yellow-100 text-yellow-600
                        @else bg-blue-100 text-blue-600 @endif">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">{{ $report->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $report->room->name ?? 'Umum' }} • {{ $report->tenant->name ?? 'Anonim' }}</p>
                        <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $report->description }}</p>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-2">
                    <span class="px-3 py-1 text-xs font-medium rounded-full
                        @if($report->status === 'new') bg-red-100 text-red-700
                        @elseif($report->status === 'in_progress') bg-yellow-100 text-yellow-700
                        @else bg-green-100 text-green-700 @endif">
                        @if($report->status === 'new') Baru
                        @elseif($report->status === 'in_progress') Diproses
                        @else Selesai @endif
                    </span>
                    <span class="text-xs text-gray-500">{{ $report->created_at->diffForHumans() }}</span>
                </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <span class="text-xs px-2 py-1 bg-gray-100 rounded-lg text-gray-600">
                        @if($report->priority === 'high') Prioritas Tinggi
                        @elseif($report->priority === 'medium') Prioritas Sedang
                        @else Prioritas Rendah @endif
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.maintenance.show', $report) }}" class="px-3 py-1.5 text-sm text-primary-600 hover:bg-primary-50 rounded-lg font-medium transition-colors">
                        Detail
                    </a>
                    @if($report->status !== 'completed')
                    <form action="{{ route('admin.maintenance.update-status', $report) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $report->status === 'new' ? 'in_progress' : 'completed' }}">
                        <button type="submit" class="px-3 py-1.5 text-sm bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors">
                            {{ $report->status === 'new' ? 'Proses' : 'Selesai' }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada laporan</h3>
        <p class="text-gray-500">Laporan kerusakan dari penghuni akan muncul di sini</p>
    </div>
    @endforelse
</div>

@if($reports->hasPages())
<div class="mt-6">
    {{ $reports->withQueryString()->links('admin.components.pagination') }}
</div>
@endif
@endsection
