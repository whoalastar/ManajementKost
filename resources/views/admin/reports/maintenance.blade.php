@extends('admin.layouts.app')

@section('title', 'Laporan Pengaduan')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="flex items-center">
    <a href="{{ route('admin.reports.index') }}" class="text-gray-500 hover:text-primary-600">Laporan</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="font-medium text-gray-800">Pengaduan</li>
@endsection

@section('header')
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.reports.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Laporan Pengaduan</h1>
            <p class="mt-1 text-gray-500 hidden sm:block">Rekap pengaduan dan maintenance</p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.reports.maintenance', ['export' => 'excel']) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span class="hidden sm:inline">Export</span> Excel
        </a>
    </div>
</div>
@endsection

@section('content')
<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Pengaduan</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Baru</p>
                <p class="text-3xl font-bold text-red-600 mt-1">{{ $stats['new'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Diproses</p>
                <p class="text-3xl font-bold text-yellow-600 mt-1">{{ $stats['in_progress'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Selesai</p>
                <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['completed'] ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
        </div>
    </div>
</div>

<!-- Chart -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <h3 class="font-semibold text-gray-900 mb-6">Pengaduan per Bulan</h3>
    <div class="h-64 lg:h-80">
        <canvas id="maintenanceChart"></canvas>
    </div>
</div>

<!-- Per Room -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-900">Pengaduan per Kamar</h3>
    </div>
    
    <!-- Mobile View -->
    <div class="block lg:hidden divide-y divide-gray-100">
        @forelse($perRoom ?? [] as $room)
        <div class="p-4 hover:bg-gray-50 transition">
            <div class="flex items-center justify-between mb-2">
                <span class="font-semibold text-gray-900">{{ $room['name'] }}</span>
                <span class="px-2.5 py-1 text-xs font-medium bg-gray-100 text-gray-600 rounded-full">{{ $room['total'] }} Laporan</span>
            </div>
            <div class="flex gap-2 mt-2">
                <div class="flex-1 text-center bg-red-50 py-1 rounded">
                    <span class="block text-xs text-red-600 font-medium">Baru</span>
                    <span class="block text-sm font-bold text-red-700">{{ $room['new'] }}</span>
                </div>
                <div class="flex-1 text-center bg-yellow-50 py-1 rounded">
                    <span class="block text-xs text-yellow-600 font-medium">Proses</span>
                    <span class="block text-sm font-bold text-yellow-700">{{ $room['in_progress'] }}</span>
                </div>
                <div class="flex-1 text-center bg-green-50 py-1 rounded">
                    <span class="block text-xs text-green-600 font-medium">Selesai</span>
                    <span class="block text-sm font-bold text-green-700">{{ $room['completed'] }}</span>
                </div>
            </div>
        </div>
        @empty
        <div class="p-8 text-center text-gray-500">
            <p>Tidak ada data</p>
        </div>
        @endforelse
    </div>

    <!-- Desktop View -->
    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Kamar</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Baru</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Diproses</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase">Selesai</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($perRoom ?? [] as $room)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $room['name'] }}</td>
                    <td class="px-6 py-4 text-center font-medium">{{ $room['total'] }}</td>
                    <td class="px-6 py-4 text-center text-red-600 font-medium">{{ $room['new'] }}</td>
                    <td class="px-6 py-4 text-center text-yellow-600 font-medium">{{ $room['in_progress'] }}</td>
                    <td class="px-6 py-4 text-center text-green-600 font-medium">{{ $room['completed'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('maintenanceChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(collect($monthlyData ?? [])->pluck('period')) !!},
            datasets: [
                {
                    label: 'Baru',
                    data: {!! json_encode(collect($monthlyData ?? [])->pluck('new')) !!},
                    backgroundColor: 'rgba(239, 68, 68, 0.8)',
                    borderRadius: 4,
                },
                {
                    label: 'Diproses',
                    data: {!! json_encode(collect($monthlyData ?? [])->pluck('in_progress')) !!},
                    backgroundColor: 'rgba(245, 158, 11, 0.8)',
                    borderRadius: 4,
                },
                {
                    label: 'Selesai',
                    data: {!! json_encode(collect($monthlyData ?? [])->pluck('completed')) !!},
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderRadius: 4,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: { 
                    stacked: true,
                    grid: { display: false },
                    ticks: {
                        font: { size: window.innerWidth < 640 ? 10 : 12 }
                    }
                },
                y: { 
                    stacked: true, 
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        font: { size: window.innerWidth < 640 ? 10 : 12 },
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>
@endpush
