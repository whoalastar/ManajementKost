@extends('admin.layouts.app')

@section('title', 'Laporan Hunian')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="flex items-center">
    <a href="{{ route('admin.reports.index') }}" class="text-gray-500 hover:text-primary-600">Laporan</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="font-medium text-gray-800">Hunian</li>
@endsection

@section('header')
<div class="flex items-center gap-4">
    <a href="{{ route('admin.reports.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Laporan Hunian</h1>
        <p class="mt-1 text-gray-500 hidden sm:block">Tingkat hunian kamar</p>
    </div>
</div>
@endsection

@section('content')
<!-- Current Status -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Kamar</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_rooms'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Terisi</p>
                <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['occupied_rooms'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Kosong</p>
                <p class="text-3xl font-bold text-gray-600 mt-1">{{ $stats['empty_rooms'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Tingkat Hunian</p>
                <p class="text-3xl font-bold text-primary-600 mt-1">{{ $stats['occupancy_rate'] ?? 0 }}%</p>
            </div>
            <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
        </div>
    </div>
</div>

<!-- Occupancy Chart -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <h3 class="font-semibold text-gray-900 mb-6">Tingkat Hunian per Bulan</h3>
    <div class="h-64 lg:h-80">
        <canvas id="occupancyChart"></canvas>
    </div>
</div>

<!-- Room Status -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-900">Status Kamar</h3>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 p-6">
        @foreach($rooms ?? [] as $room)
        <div class="p-4 rounded-xl border-2 transition-all hover:shadow-md
            @if($room->status === 'occupied') border-green-200 bg-green-50/50
            @elseif($room->status === 'maintenance') border-yellow-200 bg-yellow-50/50
            @else border-gray-200 bg-white @endif">
            <div class="text-center">
                <p class="font-bold text-gray-900 text-lg">{{ $room->name }}</p>
                <p class="text-xs text-gray-500 mt-1">Lantai {{ $room->floor }}</p>
                <span class="inline-flex items-center gap-1.5 mt-3 px-2.5 py-1 text-xs font-medium rounded-full
                    @if($room->status === 'occupied') bg-green-100 text-green-700
                    @elseif($room->status === 'maintenance') bg-yellow-100 text-yellow-700
                    @else bg-gray-100 text-gray-700 @endif">
                    <span class="w-1.5 h-1.5 rounded-full 
                        @if($room->status === 'occupied') bg-green-500
                        @elseif($room->status === 'maintenance') bg-yellow-500
                        @else bg-gray-500 @endif"></span>
                    @if($room->status === 'occupied') Terisi
                    @elseif($room->status === 'maintenance') Perbaikan
                    @else Kosong @endif
                </span>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('occupancyChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode(collect($monthlyOccupancy ?? [])->pluck('period')) !!},
            datasets: [{
                label: 'Tingkat Hunian (%)',
                data: {!! json_encode(collect($monthlyOccupancy ?? [])->pluck('rate')) !!},
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'white',
                pointBorderColor: 'rgb(59, 130, 246)',
                pointBorderWidth: 3,
                pointRadius: 6,
                pointHoverRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw + '%';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        callback: function(value) { return value + '%'; },
                        font: { size: window.innerWidth < 640 ? 10 : 12 }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { size: window.innerWidth < 640 ? 10 : 12 }
                    }
                }
            }
        }
    });
});
</script>
@endpush
