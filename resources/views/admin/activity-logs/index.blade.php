@extends('admin.layouts.app')

@section('title', 'Log Aktivitas')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="font-medium text-gray-800">Log Aktivitas</li>
@endsection

@section('header')
<div>
    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Log Aktivitas</h1>
    <p class="mt-1 text-gray-500">Riwayat aktivitas dan perubahan data</p>
</div>
@endsection

@section('content')
<!-- Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <form action="{{ route('admin.activity-logs.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[200px]">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari aktivitas..." 
                   class="w-full pl-10 pr-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all">
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        
        <select name="action" class="px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
            <option value="">Semua Aksi</option>
            <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Create</option>
            <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Update</option>
            <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Delete</option>
            <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Login</option>
        </select>
        
        <input type="date" name="date" value="{{ request('date') }}" class="px-4 py-2.5 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500">
        
        <button type="submit" class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-xl transition-colors">Filter</button>
        
        @if(request()->hasAny(['search', 'action', 'date']))
        <a href="{{ route('admin.activity-logs.index') }}" class="px-4 py-2.5 text-gray-600 hover:text-gray-800 text-sm font-medium">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="divide-y divide-gray-100">
        @forelse($logs as $log)
        <div class="p-4 hover:bg-gray-50/50 transition-colors">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                    @if($log->action === 'create') bg-green-100 text-green-600
                    @elseif($log->action === 'update') bg-blue-100 text-blue-600
                    @elseif($log->action === 'delete') bg-red-100 text-red-600
                    @else bg-gray-100 text-gray-600 @endif">
                    @if($log->action === 'create')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    @elseif($log->action === 'update')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    @elseif($log->action === 'delete')
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-4">
                        <p class="font-medium text-gray-900">{{ $log->description }}</p>
                        <span class="text-sm text-gray-500 flex-shrink-0">{{ $log->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="mt-1 flex items-center gap-4 text-sm text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            {{ $log->admin->name ?? 'System' }}
                        </span>
                        <span>{{ $log->model_type ? class_basename($log->model_type) : '-' }}</span>
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full
                            @if($log->action === 'create') bg-green-100 text-green-700
                            @elseif($log->action === 'update') bg-blue-100 text-blue-700
                            @elseif($log->action === 'delete') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($log->action) }}
                        </span>
                    </div>
                    @if($log->old_values || $log->new_values)
                    <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="mt-2 text-sm text-primary-600 hover:text-primary-700 font-medium">
                        Lihat Detail
                    </button>
                    <div class="hidden mt-2 p-3 bg-gray-50 rounded-lg text-sm">
                        @if($log->old_values)
                        <p class="text-gray-500 mb-1"><strong>Sebelum:</strong></p>
                        <pre class="text-xs text-gray-600 overflow-x-auto">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                        @endif
                        @if($log->new_values)
                        <p class="text-gray-500 mt-2 mb-1"><strong>Sesudah:</strong></p>
                        <pre class="text-xs text-gray-600 overflow-x-auto">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="p-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada log</h3>
            <p class="text-gray-500">Aktivitas akan tercatat di sini</p>
        </div>
        @endforelse
    </div>
    
    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $logs->withQueryString()->links('admin.components.pagination') }}
    </div>
    @endif
</div>
@endsection
