@extends('admin.layouts.app')

@section('title', 'Detail Laporan')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="flex items-center">
    <a href="{{ route('admin.maintenance.index') }}" class="text-gray-500 hover:text-primary-600">Maintenance</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="font-medium text-gray-800">Detail</li>
@endsection

@section('header')
<div class="flex items-center gap-4">
    <a href="{{ route('admin.maintenance.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $report->title }}</h1>
        <p class="mt-1 text-gray-500">{{ $report->created_at->format('d M Y H:i') }}</p>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-5xl">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Detail Laporan</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($report->tenant->name ?? 'A', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $report->tenant->name ?? 'Anonim' }}</p>
                        <p class="text-sm text-gray-500">{{ $report->room->name ?? 'Umum' }} • {{ $report->tenant->phone ?? '-' }}</p>
                    </div>
                </div>
                
                <div class="prose prose-sm max-w-none">
                    <p class="text-gray-700">{{ $report->description }}</p>
                </div>
                
                @if($report->photos && count($report->photos) > 0)
                <div class="mt-6">
                    <h4 class="font-medium text-gray-900 mb-3">Foto Kerusakan</h4>
                    <div class="grid grid-cols-3 gap-4">
                        @foreach($report->photos as $photo)
                        <div class="aspect-square rounded-xl overflow-hidden bg-gray-100">
                            <img src="{{ Storage::url($photo) }}" class="w-full h-full object-cover" alt="Foto kerusakan">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Admin Notes -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Catatan Tindak Lanjut</h3>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.maintenance.add-notes', $report) }}" method="POST" class="space-y-4">
                    @csrf
                    <textarea name="admin_notes" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all resize-none" placeholder="Tambahkan catatan tindak lanjut...">{{ $report->admin_notes }}</textarea>
                    <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors">
                        Simpan Catatan
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="space-y-6">
        <!-- Status -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Status</h3>
            </div>
            <div class="p-6">
                <div class="text-center mb-6">
                    <span class="px-4 py-2 text-sm font-semibold rounded-full
                        @if($report->status === 'new') bg-red-100 text-red-700
                        @elseif($report->status === 'in_progress') bg-yellow-100 text-yellow-700
                        @else bg-green-100 text-green-700 @endif">
                        @if($report->status === 'new') Baru
                        @elseif($report->status === 'in_progress') Diproses
                        @else Selesai @endif
                    </span>
                </div>
                
                @if($report->status !== 'completed')
                <form action="{{ route('admin.maintenance.update-status', $report) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500">
                        <option value="new" {{ $report->status === 'new' ? 'selected' : '' }}>Baru</option>
                        <option value="in_progress" {{ $report->status === 'in_progress' ? 'selected' : '' }}>Diproses</option>
                        <option value="completed" {{ $report->status === 'completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    <button type="submit" class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors">
                        Update Status
                    </button>
                </form>
                @endif
            </div>
        </div>
        
        <!-- Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Informasi</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Prioritas</p>
                    <span class="inline-flex mt-1 px-3 py-1 text-sm font-medium rounded-full
                        @if($report->priority === 'high') bg-red-100 text-red-700
                        @elseif($report->priority === 'medium') bg-yellow-100 text-yellow-700
                        @else bg-blue-100 text-blue-700 @endif">
                        @if($report->priority === 'high') Tinggi
                        @elseif($report->priority === 'medium') Sedang
                        @else Rendah @endif
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Dilaporkan</p>
                    <p class="font-medium text-gray-900">{{ $report->created_at->format('d M Y H:i') }}</p>
                </div>
                @if($report->completed_at)
                <div>
                    <p class="text-sm text-gray-500">Diselesaikan</p>
                    <p class="font-medium text-green-600">{{ $report->completed_at->format('d M Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
