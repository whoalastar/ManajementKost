@extends('admin.layouts.app')

@section('title', 'Detail Penghuni')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="flex items-center">
    <a href="{{ route('admin.tenants.index') }}" class="text-gray-500 hover:text-primary-600">Penghuni</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="font-medium text-gray-800">{{ $tenant->name }}</li>
@endsection

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.tenants.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-green-500/30">
                {{ strtoupper(substr($tenant->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $tenant->name }}</h1>
                <p class="mt-1 text-gray-500">{{ $tenant->occupation ?? 'Penghuni' }}</p>
            </div>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <span class="px-3 py-1 text-sm font-medium rounded-full {{ $tenant->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
            {{ $tenant->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
        </span>
        <a href="{{ route('admin.tenants.edit', $tenant) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Info Penghuni -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Informasi Penghuni</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <dt class="text-sm text-gray-500">No. HP</dt>
                        <dd class="mt-1 font-semibold text-gray-900">{{ $tenant->phone }}</dd>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <dt class="text-sm text-gray-500">Email</dt>
                        <dd class="mt-1 font-semibold text-gray-900">{{ $tenant->email ?? '-' }}</dd>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <dt class="text-sm text-gray-500">No. KTP</dt>
                        <dd class="mt-1 font-semibold text-gray-900">{{ $tenant->id_card_number ?? '-' }}</dd>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <dt class="text-sm text-gray-500">Pekerjaan</dt>
                        <dd class="mt-1 font-semibold text-gray-900">{{ $tenant->occupation ?? '-' }}</dd>
                    </div>
                </dl>
                
                @if($tenant->emergency_contact_name || $tenant->emergency_contact_phone)
                <div class="mt-6 p-4 bg-amber-50 border border-amber-100 rounded-xl">
                    <h4 class="font-medium text-amber-900 mb-2">Kontak Darurat</h4>
                    <p class="text-amber-800">{{ $tenant->emergency_contact_name }} - {{ $tenant->emergency_contact_phone }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Invoice Terbaru -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Invoice Terbaru</h3>
                <a href="{{ route('admin.invoices.index') }}?tenant_id={{ $tenant->id }}" class="text-sm text-primary-600 hover:text-primary-700">Lihat Semua</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($tenant->invoices->take(5) as $invoice)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">{{ $invoice->invoice_number }}</p>
                        <p class="text-sm text-gray-500">{{ $invoice->period_month }}/{{ $invoice->period_year }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</p>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($invoice->status === 'paid') bg-green-100 text-green-700
                            @elseif($invoice->status === 'sent') bg-blue-100 text-blue-700
                            @elseif($invoice->status === 'overdue') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">Belum ada invoice</div>
                @endforelse
            </div>
        </div>
        
        <!-- Pembayaran Terbaru -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Pembayaran Terbaru</h3>
                <a href="{{ route('admin.payments.index') }}?tenant_id={{ $tenant->id }}" class="text-sm text-primary-600 hover:text-primary-700">Lihat Semua</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($tenant->payments->take(5) as $payment)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500">{{ $payment->payment_date?->format('d M Y') }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $payment->verified_at ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $payment->verified_at ? 'Verified' : 'Pending' }}
                    </span>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">Belum ada pembayaran</div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="space-y-6">
        <!-- Info Kamar -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Kamar</h3>
            </div>
            <div class="p-6">
                @if($tenant->room)
                <div class="text-center">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center text-white text-xl font-bold mx-auto shadow-lg shadow-blue-500/30">
                        {{ $tenant->room->floor }}
                    </div>
                    <h4 class="mt-3 font-semibold text-gray-900">{{ $tenant->room->name }}</h4>
                    <p class="text-sm text-gray-500">{{ $tenant->room->roomType->name ?? 'Standard' }}</p>
                    <p class="mt-2 text-lg font-bold text-primary-600">Rp {{ number_format($tenant->room->price, 0, ',', '.') }}/bulan</p>
                    
                    <div class="mt-4 pt-4 border-t border-gray-100 text-sm text-left">
                        <div class="flex justify-between py-2">
                            <span class="text-gray-500">Check-in:</span>
                            <span class="font-medium">{{ $tenant->check_in_date?->format('d M Y') ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-500">Durasi:</span>
                            <span class="font-medium">{{ $tenant->check_in_date ? $tenant->check_in_date->diffForHumans(null, true) : '-' }}</span>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-4">
                    <p class="text-gray-500">Belum ada kamar</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Quick Actions -->
        @if($tenant->status === 'active')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Aksi Cepat</h3>
            </div>
            <div class="p-4 space-y-2">
                <a href="{{ route('admin.invoices.create') }}?tenant_id={{ $tenant->id }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="font-medium text-gray-700">Buat Invoice</span>
                </a>
                <a href="{{ route('admin.payments.create') }}?tenant_id={{ $tenant->id }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    <span class="font-medium text-gray-700">Input Pembayaran</span>
                </a>
                <form action="{{ route('admin.tenants.checkout', $tenant) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" onclick="return confirm('Checkout penghuni ini?')" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-red-50 transition-colors text-left">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span class="font-medium text-red-600">Checkout</span>
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
