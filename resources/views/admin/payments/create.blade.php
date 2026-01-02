@extends('admin.layouts.app')

@section('title', 'Input Pembayaran')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="flex items-center">
    <a href="{{ route('admin.payments.index') }}" class="text-gray-500 hover:text-primary-600">Pembayaran</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="font-medium text-gray-800">Input</li>
@endsection

@section('header')
<div class="flex items-center gap-4">
    <a href="{{ route('admin.payments.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Input Pembayaran</h1>
        <p class="mt-1 text-gray-500">Catat pembayaran dari penghuni</p>
    </div>
</div>
@endsection

@section('content')
<form action="{{ route('admin.payments.store') }}" method="POST" enctype="multipart/form-data" class="max-w-2xl">
    @csrf
    
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900">Detail Pembayaran</h3>
        </div>
        <div class="p-6 space-y-5">
            <div>
                <label for="invoice_id" class="block text-sm font-medium text-gray-700 mb-2">Invoice <span class="text-red-500">*</span></label>
                <select id="invoice_id" name="invoice_id" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                    <option value="">Pilih Invoice</option>
                    @foreach($invoices as $invoice)
                    <option value="{{ $invoice->id }}" {{ request('invoice_id') == $invoice->id ? 'selected' : '' }}>
                        {{ $invoice->invoice_number }} - {{ $invoice->tenant->name ?? '-' }} (Rp {{ number_format($invoice->total_amount - $invoice->paid_amount, 0, ',', '.') }})
                    </option>
                    @endforeach
                </select>
                @error('invoice_id')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Jumlah <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                        <input type="number" id="amount" name="amount" value="{{ old('amount') }}" required min="1"
                               class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                    </div>
                    @error('amount')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="payment_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pembayaran <span class="text-red-500">*</span></label>
                    <input type="date" id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Metode Pembayaran <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
                        <input type="radio" name="payment_method" value="transfer" {{ old('payment_method', 'transfer') == 'transfer' ? 'checked' : '' }} class="w-4 h-4 text-primary-600">
                        <div>
                            <span class="font-medium text-gray-900">Transfer Bank</span>
                            <p class="text-sm text-gray-500">Via rekening bank</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
                        <input type="radio" name="payment_method" value="cash" {{ old('payment_method') == 'cash' ? 'checked' : '' }} class="w-4 h-4 text-primary-600">
                        <div>
                            <span class="font-medium text-gray-900">Cash</span>
                            <p class="text-sm text-gray-500">Pembayaran tunai</p>
                        </div>
                    </label>
                </div>
            </div>
            
            <div>
                <label for="proof_photo" class="block text-sm font-medium text-gray-700 mb-2">Bukti Pembayaran</label>
                <input type="file" id="proof_photo" name="proof_photo" accept="image/*"
                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
            </div>
            
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea id="notes" name="notes" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all resize-none">{{ old('notes') }}</textarea>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.payments.index') }}" class="px-5 py-2.5 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 font-medium rounded-xl transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-xl transition-all duration-300">Simpan</button>
        </div>
    </div>
</form>
@endsection
