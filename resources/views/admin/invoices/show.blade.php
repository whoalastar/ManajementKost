@extends('admin.layouts.app')

@section('title', 'Detail Invoice')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="flex items-center">
    <a href="{{ route('admin.invoices.index') }}" class="text-gray-500 hover:text-primary-600">Invoice</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="font-medium text-gray-800">{{ $invoice->invoice_number }}</li>
@endsection

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.invoices.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">{{ $invoice->invoice_number }}</h1>
            <p class="mt-1 text-gray-500">{{ DateTime::createFromFormat('!m', $invoice->period_month)->format('F') }} {{ $invoice->period_year }}</p>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <span class="px-4 py-2 text-sm font-semibold rounded-full
            @if($invoice->status === 'paid') bg-green-100 text-green-700
            @elseif($invoice->status === 'sent') bg-blue-100 text-blue-700
            @elseif($invoice->status === 'overdue') bg-red-100 text-red-700
            @else bg-gray-100 text-gray-700 @endif">
            {{ ucfirst($invoice->status) }}
        </span>
        <a href="{{ route('admin.invoices.download', $invoice) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 font-medium rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Download PDF</span>
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Invoice Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Detail Invoice</h3>
                <a href="{{ route('admin.invoices.edit', $invoice) }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Edit</a>
            </div>
            <div class="p-6">
                <!-- Penghuni Info -->
                <div class="flex items-center gap-4 mb-6 p-4 bg-gray-50 rounded-xl">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center text-white text-xl font-bold">
                        {{ strtoupper(substr($invoice->tenant->name ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">{{ $invoice->tenant->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $invoice->room->name ?? '-' }} • {{ $invoice->tenant->phone }}</p>
                    </div>
                </div>
                
                <!-- Komponen Tagihan -->
                <div class="space-y-3">
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">Sewa Kamar</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($invoice->room_price, 0, ',', '.') }}</span>
                    </div>
                    @if($invoice->electricity_fee > 0)
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">Listrik</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($invoice->electricity_fee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($invoice->water_fee > 0)
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">Air</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($invoice->water_fee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($invoice->internet_fee > 0)
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">Internet</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($invoice->internet_fee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($invoice->penalty_fee > 0)
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">Denda</span>
                        <span class="font-medium text-red-600">Rp {{ number_format($invoice->penalty_fee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($invoice->other_fee > 0)
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">{{ $invoice->other_fee_description ?? 'Biaya Lain' }}</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($invoice->other_fee, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between py-4 bg-primary-50 rounded-xl px-4 mt-4">
                        <span class="font-semibold text-gray-900">Total</span>
                        <span class="text-xl font-bold text-primary-600">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                    </div>
                    @if($invoice->paid_amount > 0)
                    <div class="flex justify-between py-3">
                        <span class="text-gray-600">Dibayar</span>
                        <span class="font-medium text-green-600">Rp {{ number_format($invoice->paid_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between py-3">
                        <span class="font-semibold text-gray-900">Sisa Tagihan</span>
                        <span class="font-bold text-red-600">Rp {{ number_format($invoice->total_amount - $invoice->paid_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
                
                @if($invoice->notes)
                <div class="mt-6 p-4 bg-amber-50 border border-amber-100 rounded-xl">
                    <p class="text-sm text-amber-800"><strong>Catatan:</strong> {{ $invoice->notes }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Payment History -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">Riwayat Pembayaran</h3>
                @if($invoice->status !== 'paid')
                <a href="{{ route('admin.payments.create') }}?invoice_id={{ $invoice->id }}" class="text-sm text-primary-600 hover:text-primary-700 font-medium">Input Pembayaran</a>
                @endif
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($invoice->payments as $payment)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        <p class="text-sm text-gray-500">{{ $payment->payment_date?->format('d M Y') }} • {{ $payment->payment_method }}</p>
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
        
        <!-- Email Log -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Log Pengiriman Email</h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($invoice->emailLogs as $log)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-900">{{ $log->recipient_email }}</p>
                        <p class="text-sm text-gray-500">{{ $log->created_at->format('d M Y H:i') }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $log->status === 'sent' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $log->status === 'sent' ? 'Terkirim' : 'Gagal' }}
                    </span>
                </div>
                @empty
                <div class="px-6 py-8 text-center text-gray-500">Belum ada email dikirim</div>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="space-y-6">
        <!-- Quick Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Informasi</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Jatuh Tempo</p>
                    <p class="font-semibold text-gray-900">{{ $invoice->due_date?->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Dibuat</p>
                    <p class="font-semibold text-gray-900">{{ $invoice->created_at->format('d M Y H:i') }}</p>
                </div>
                @if($invoice->status === 'paid')
                <div>
                    <p class="text-sm text-gray-500">Dibayar</p>
                    <p class="font-semibold text-green-600">{{ $invoice->paid_at?->format('d M Y H:i') }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Actions -->
        @if($invoice->status !== 'paid')
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Aksi</h3>
            </div>
            <div class="p-4 space-y-2">
                <form action="{{ route('admin.invoices.send', $invoice) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-blue-50 transition-colors text-left">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span class="font-medium text-gray-700">Kirim via Email</span>
                    </button>
                </form>
                <form action="{{ route('admin.invoices.mark-paid', $invoice) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-green-50 transition-colors text-left">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="font-medium text-gray-700">Tandai Lunas</span>
                    </button>
                </form>
                <form id="delete-invoice-{{ $invoice->id }}" action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmDelete('delete-invoice-{{ $invoice->id }}')" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-red-50 transition-colors text-left">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        <span class="font-medium text-red-600">Hapus Invoice</span>
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
