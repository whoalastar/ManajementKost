@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('breadcrumb')
<li class="flex items-center">
    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
    </svg>
    <span class="font-medium text-gray-800">Dashboard</span>
</li>
@endsection

@section('header')
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="mt-1 text-gray-500">Selamat datang kembali, {{ Auth::guard('admin')->user()->name ?? 'Admin' }}!</p>
    </div>
    <div class="flex items-center gap-3">
        <span class="px-3 py-1.5 bg-green-100 text-green-700 text-sm font-medium rounded-full">
            <span class="inline-block w-2 h-2 bg-green-500 rounded-full mr-1.5 animate-pulse"></span>
            Online
        </span>
        <span class="text-sm text-gray-500">{{ now()->translatedFormat('l, d F Y') }}</span>
    </div>
</div>
@endsection

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6">
    <!-- Total Kamar -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-primary-100 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Kamar</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['total_rooms'] ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>
        <div class="mt-4 flex items-center gap-4 text-sm">
            <span class="flex items-center text-green-600">
                <span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span>
                {{ $stats['empty_rooms'] ?? 0 }} Kosong
            </span>
            <span class="flex items-center text-orange-600">
                <span class="w-2 h-2 bg-orange-500 rounded-full mr-1.5"></span>
                {{ $stats['occupied_rooms'] ?? 0 }} Terisi
            </span>
        </div>
    </div>
    
    <!-- Penghuni Aktif -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-green-100 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Penghuni Aktif</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['active_tenants'] ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg shadow-green-500/30">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.tenants.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium inline-flex items-center gap-1">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
    
    <!-- Invoice Belum Lunas -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-amber-100 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Invoice Pending</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['unpaid_invoices'] ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/30">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.invoices.index') }}?status=unpaid" class="text-sm text-amber-600 hover:text-amber-700 font-medium inline-flex items-center gap-1">
                Lihat Detail
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
    
    <!-- Pendapatan Bulan Ini -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg hover:border-purple-100 transition-all duration-300">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Pendapatan Bulan Ini</p>
                <p class="text-2xl lg:text-3xl font-bold text-gray-900 mt-1">Rp {{ number_format($stats['total_revenue_this_month'] ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg shadow-purple-500/30">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <div class="mt-4">
            <a href="{{ route('admin.reports.income') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium inline-flex items-center gap-1">
                Laporan Lengkap
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
</div>

<!-- Second Row Stats -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 lg:gap-6 mb-6">
    <!-- Booking Pending -->
    <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl p-6 shadow-lg shadow-cyan-500/30 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-cyan-100 text-sm font-medium">Booking Menunggu</p>
                <p class="text-4xl font-bold mt-1">{{ $stats['pending_bookings'] ?? 0 }}</p>
            </div>
            <svg class="w-12 h-12 text-cyan-200/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-white/90 hover:text-white">
            Kelola Booking
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    
    <!-- Maintenance Pending -->
    <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl p-6 shadow-lg shadow-rose-500/30 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-rose-100 text-sm font-medium">Laporan Maintenance</p>
                <p class="text-4xl font-bold mt-1">{{ $stats['pending_maintenance'] ?? 0 }}</p>
            </div>
            <svg class="w-12 h-12 text-rose-200/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <a href="{{ route('admin.maintenance.index') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-white/90 hover:text-white">
            Kelola Laporan
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
    
    <!-- Kamar Maintenance -->
    <div class="bg-gradient-to-br from-slate-600 to-slate-700 rounded-2xl p-6 shadow-lg shadow-slate-600/30 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-slate-300 text-sm font-medium">Kamar Maintenance</p>
                <p class="text-4xl font-bold mt-1">{{ $stats['maintenance_rooms'] ?? 0 }}</p>
            </div>
            <svg class="w-12 h-12 text-slate-400/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <a href="{{ route('admin.rooms.index') }}?status=maintenance" class="mt-4 inline-flex items-center gap-1 text-sm font-medium text-white/90 hover:text-white">
            Lihat Kamar
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
</div>

<!-- Charts Row -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Monthly Revenue Chart -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Pendapatan Bulanan</h3>
                <p class="text-sm text-gray-500">Tahun {{ date('Y') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="flex items-center gap-1.5 text-sm text-gray-500">
                    <span class="w-3 h-3 bg-primary-500 rounded-full"></span>
                    Pendapatan
                </span>
            </div>
        </div>
        <div class="h-72">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    
    <!-- Occupancy Rate Chart -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Tingkat Hunian</h3>
                <p class="text-sm text-gray-500">6 Bulan Terakhir</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="flex items-center gap-1.5 text-sm text-gray-500">
                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    Okupansi (%)
                </span>
            </div>
        </div>
        <div class="h-72">
            <canvas id="occupancyChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Bookings -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Booking Terbaru</h3>
            <a href="{{ route('admin.bookings.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Lihat Semua</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentActivities['recent_bookings'] ?? [] as $booking)
            <div class="px-6 py-4 hover:bg-gray-50 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">{{ $booking->name }}</p>
                        <p class="text-sm text-gray-500">{{ $booking->room->room_number ?? '-' }}</p>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full
                        @if($booking->status === 'new') bg-blue-100 text-blue-700
                        @elseif($booking->status === 'contacted') bg-yellow-100 text-yellow-700
                        @elseif($booking->status === 'survey') bg-purple-100 text-purple-700
                        @elseif($booking->status === 'deal') bg-green-100 text-green-700
                        @else bg-gray-100 text-gray-700 @endif">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p>Belum ada booking</p>
            </div>
            @endforelse
        </div>
    </div>
    
    <!-- Recent Payments -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Pembayaran Terbaru</h3>
            <a href="{{ route('admin.payments.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Lihat Semua</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentActivities['recent_payments'] ?? [] as $payment)
            <div class="px-6 py-4 hover:bg-gray-50 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">{{ $payment->tenant->name ?? '-' }}</p>
                        <p class="text-sm text-gray-500">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $payment->verified_at ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $payment->verified_at ? 'Terverifikasi' : 'Pending' }}
                    </span>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                <p>Belum ada pembayaran</p>
            </div>
            @endforelse
        </div>
    </div>
    
    <!-- Recent Maintenance -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Laporan Maintenance</h3>
            <a href="{{ route('admin.maintenance.index') }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Lihat Semua</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentActivities['recent_maintenance'] ?? [] as $report)
            <div class="px-6 py-4 hover:bg-gray-50 transition">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900 line-clamp-1">{{ $report->title }}</p>
                        <p class="text-sm text-gray-500">Kamar {{ $report->room->room_number ?? '-' }}</p>
                    </div>
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full
                        @if($report->status === 'new') bg-red-100 text-red-700
                        @elseif($report->status === 'in_progress') bg-yellow-100 text-yellow-700
                        @else bg-green-100 text-green-700 @endif">
                        {{ $report->status === 'new' ? 'Baru' : ($report->status === 'in_progress' ? 'Proses' : 'Selesai') }}
                    </span>
                </div>
            </div>
            @empty
            <div class="px-6 py-8 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <p>Belum ada laporan</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Revenue Data
    const monthlyRevenue = @json($monthlyRevenue ?? []);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Pendapatan',
                data: Object.values(monthlyRevenue),
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 0,
                borderRadius: 8,
                borderSkipped: false,
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
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + (value / 1000000) + 'jt';
                        }
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
    
    // Occupancy Rate Data
    const occupancyRate = @json($occupancyRate ?? []);
    
    // Occupancy Chart
    const occupancyCtx = document.getElementById('occupancyChart').getContext('2d');
    new Chart(occupancyCtx, {
        type: 'line',
        data: {
            labels: Object.keys(occupancyRate),
            datasets: [{
                label: 'Okupansi',
                data: Object.values(occupancyRate),
                borderColor: 'rgba(34, 197, 94, 1)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'white',
                pointBorderColor: 'rgba(34, 197, 94, 1)',
                pointBorderWidth: 3,
                pointRadius: 5,
                pointHoverRadius: 7,
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
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });
});
</script>
@endpush
