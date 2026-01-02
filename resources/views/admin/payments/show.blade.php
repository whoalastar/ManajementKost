@extends('admin.layouts.app')

@section('title', 'Detail Pembayaran')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="flex items-center">
    <a href="{{ route('admin.payments.index') }}" class="text-gray-500 hover:text-primary-600">Pembayaran</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="font-medium text-gray-800">Detail</li>
@endsection

@section('header')
<div class="flex items-center gap-4">
    <a href="{{ route('admin.payments.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Detail Pembayaran</h1>
        <p class="mt-1 text-gray-500">{{ $payment->payment_date?->format('d M Y') }}</p>
    </div>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 max-w-5xl">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Informasi Pembayaran</h3>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <dt class="text-sm text-gray-500">Jumlah</dt>
                        <dd class="mt-1 text-2xl font-bold text-primary-600">Rp {{ number_format($payment->amount, 0, ',', '.') }}</dd>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <dt class="text-sm text-gray-500">Metode</dt>
                        <dd class="mt-1 font-semibold text-gray-900">{{ ucfirst($payment->payment_method) }}</dd>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <dt class="text-sm text-gray-500">Tanggal</dt>
                        <dd class="mt-1 font-semibold text-gray-900">{{ $payment->payment_date?->format('d M Y') }}</dd>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <dt class="text-sm text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="px-3 py-1 text-sm font-medium rounded-full
                                @if($payment->verified_at) bg-green-100 text-green-700
                                @elseif($payment->rejected_at) bg-red-100 text-red-700
                                @else bg-yellow-100 text-yellow-700 @endif">
                                @if($payment->verified_at) Verified
                                @elseif($payment->rejected_at) Rejected
                                @else Pending @endif
                            </span>
                        </dd>
                    </div>
                </dl>
                
                @if($payment->notes)
                <div class="mt-6 p-4 bg-amber-50 border border-amber-100 rounded-xl">
                    <p class="text-sm text-amber-800"><strong>Catatan:</strong> {{ $payment->notes }}</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Invoice Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Invoice Terkait</h3>
            </div>
            <div class="p-6">
                <a href="{{ route('admin.invoices.show', $payment->invoice) }}" class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                    <div class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $payment->invoice->invoice_number }}</p>
                        <p class="text-sm text-gray-500">{{ $payment->tenant->name ?? '-' }} • Rp {{ number_format($payment->invoice->total_amount, 0, ',', '.') }}</p>
                    </div>
                </a>
            </div>
        </div>
        
        <!-- Bukti Pembayaran -->
        @if($payment->proof_image)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Bukti Pembayaran</h3>
            </div>
            <div class="p-6">
                <img src="{{ asset($payment->proof_image) }}" alt="Bukti Pembayaran" class="rounded-xl max-h-96 object-contain mx-auto">
            </div>
        </div>
        @endif
    </div>
    
    <div class="space-y-6">
        <!-- Actions -->
        @if(!$payment->verified_at && !$payment->rejected_at)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Aksi</h3>
            </div>
            <div class="p-4 space-y-2">
                <form action="{{ route('admin.payments.verify', $payment) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-green-50 transition-colors text-left">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="font-medium text-gray-700">Verifikasi Pembayaran</span>
                    </button>
                </form>
                <form action="{{ route('admin.payments.reject', $payment) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-red-50 transition-colors text-left">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        <span class="font-medium text-red-600">Tolak Pembayaran</span>
                    </button>
                </form>
            </div>
        </div>
        @endif
        
        <!-- Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Info</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-sm text-gray-500">Dibuat</p>
                    <p class="font-medium text-gray-900">{{ $payment->created_at->format('d M Y H:i') }}</p>
                </div>
                @if($payment->verified_at)
                <div>
                    <p class="text-sm text-gray-500">Diverifikasi</p>
                    <p class="font-medium text-green-600">{{ $payment->verified_at->format('d M Y H:i') }}</p>
                </div>
                @endif
                @if($payment->verified_by)
                <div>
                    <p class="text-sm text-gray-500">Oleh</p>
                    <p class="font-medium text-gray-900">{{ $payment->verifiedBy->name ?? '-' }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

