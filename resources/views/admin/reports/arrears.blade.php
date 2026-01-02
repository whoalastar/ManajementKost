@extends('admin.layouts.app')

@section('title', 'Laporan Tunggakan')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="flex items-center">
    <a href="{{ route('admin.reports.index') }}" class="text-gray-500 hover:text-primary-600">Laporan</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="font-medium text-gray-800">Tunggakan</li>
@endsection

@section('header')
<div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.reports.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Laporan Tunggakan</h1>
            <p class="mt-1 text-gray-500 hidden sm:block">Daftar invoice yang belum dibayar</p>
        </div>
    </div>
    <div class="flex items-center gap-2">
        <a href="{{ route('admin.reports.arrears', ['export' => 'excel']) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span class="hidden sm:inline">Export</span> Excel
        </a>
        <a href="{{ route('admin.reports.arrears', ['export' => 'pdf']) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            <span class="hidden sm:inline">Export</span> PDF
        </a>
    </div>
</div>
@endsection

@section('content')
<!-- Summary Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-6">
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-lg shadow-red-500/30">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-red-100 text-sm font-medium">Total Tunggakan</p>
                <p class="text-2xl lg:text-3xl font-bold mt-1">Rp {{ number_format($summary['total_arrears'] ?? 0, 0, ',', '.') }}</p>
            </div>
            <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg shadow-orange-500/30">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium">Invoice Jatuh Tempo</p>
                <p class="text-2xl lg:text-3xl font-bold mt-1">{{ $summary['overdue_count'] ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>
    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl p-6 text-white shadow-lg shadow-yellow-500/30 sm:col-span-2 lg:col-span-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-yellow-100 text-sm font-medium">Invoice Belum Bayar</p>
                <p class="text-2xl lg:text-3xl font-bold mt-1">{{ $summary['unpaid_count'] ?? 0 }}</p>
            </div>
            <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <h3 class="font-semibold text-gray-900">Daftar Tunggakan</h3>
    </div>
    
    <!-- Mobile View -->
    <div class="block lg:hidden divide-y divide-gray-100">
        @forelse($invoices ?? [] as $invoice)
        <div class="p-4 hover:bg-gray-50 transition">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center text-white text-sm font-semibold shadow-sm">
                        {{ strtoupper(substr($invoice->tenant->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $invoice->tenant->name ?? '-' }}</p>
                        <p class="text-sm text-gray-500">{{ $invoice->room->name ?? '-' }}</p>
                    </div>
                </div>
                <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $invoice->status === 'overdue' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ $invoice->status === 'overdue' ? 'Overdue' : 'Unpaid' }}
                </span>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-3 text-sm">
                <div>
                    <p class="text-gray-500 text-xs mb-1">Invoice</p>
                    <a href="{{ route('admin.invoices.show', $invoice) }}" class="text-primary-600 font-medium hover:underline">
                        #{{ $invoice->invoice_number }}
                    </a>
                </div>
                <div>
                    <p class="text-gray-500 text-xs mb-1">Periode</p>
                    <p class="font-medium text-gray-900">
                        {{ DateTime::createFromFormat('!m', $invoice->period_month)->format('M') }} {{ $invoice->period_year }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs mb-1">Jatuh Tempo</p>
                    <p class="font-medium {{ $invoice->due_date < now() ? 'text-red-600' : 'text-gray-900' }}">
                        {{ $invoice->due_date?->format('d M Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs mb-1">Total Tunggakan</p>
                    <p class="font-bold text-red-600">
                        Rp {{ number_format($invoice->total_amount - $invoice->paid_amount, 0, ',', '.') }}
                    </p>
                </div>
            </div>
            
            <a href="{{ route('admin.invoices.show', $invoice) }}" class="block w-full text-center px-4 py-2 bg-gray-50 text-gray-600 font-medium rounded-lg hover:bg-gray-100 transition-colors text-sm">
                Lihat Detail Invoice
            </a>
        </div>
        @empty
        <div class="p-8 text-center text-gray-500">
            <svg class="w-12 h-12 mx-auto text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p>Tidak ada tagihan yang menunggak</p>
        </div>
        @endforelse
    </div>
    
    <!-- Desktop View -->
    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50/50">
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Penghuni</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Invoice</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Periode</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Tunggakan</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($invoices ?? [] as $invoice)
                <tr class="hover:bg-gray-50/50 transition">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center text-white text-sm font-semibold shadow-sm">
                                {{ strtoupper(substr($invoice->tenant->name ?? 'U', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $invoice->tenant->name ?? '-' }}</p>
                                <p class="text-sm text-gray-500">{{ $invoice->room->name ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.invoices.show', $invoice) }}" class="text-primary-600 hover:text-primary-700 font-medium hover:underline">
                            {{ $invoice->invoice_number }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-gray-600">
                        {{ DateTime::createFromFormat('!m', $invoice->period_month)->format('F') }} {{ $invoice->period_year }}
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-900">{{ $invoice->due_date?->format('d M Y') }}</p>
                        @if($invoice->due_date < now())
                        <p class="text-xs text-red-600 font-medium mt-0.5">{{ $invoice->due_date->diffForHumans() }}</p>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="font-bold text-red-600">Rp {{ number_format($invoice->total_amount - $invoice->paid_amount, 0, ',', '.') }}</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $invoice->status === 'overdue' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $invoice->status === 'overdue' ? 'Jatuh Tempo' : 'Belum Bayar' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">Tidak ada tunggakan</h3>
                        <p class="text-gray-500">Semua invoice sudah dibayar</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
