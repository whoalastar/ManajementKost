@extends('admin.layouts.app')

@section('title', 'Buat Invoice')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="flex items-center">
    <a href="{{ route('admin.invoices.index') }}" class="text-gray-500 hover:text-primary-600">Invoice</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="font-medium text-gray-800">Buat</li>
@endsection

@section('header')
<div class="flex items-center gap-4">
    <a href="{{ route('admin.invoices.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Buat Invoice</h1>
        <p class="mt-1 text-gray-500">Buat tagihan baru untuk penghuni</p>
    </div>
</div>
@endsection

@section('content')
<form action="{{ route('admin.invoices.store') }}" method="POST" class="max-w-4xl" x-data="invoiceForm()">
    @csrf
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Penghuni & Kamar -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Penghuni & Kamar</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <label for="tenant_id" class="block text-sm font-medium text-gray-700 mb-2">Penghuni <span class="text-red-500">*</span></label>
                        <select id="tenant_id" name="tenant_id" required x-model="tenantId" @change="updateRoom()"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all">
                            <option value="">Pilih Penghuni</option>
                            @foreach($tenants as $tenant)
                            <option value="{{ $tenant->id }}" data-room-id="{{ $tenant->room_id }}" data-room-price="{{ $tenant->room->price ?? 0 }}">
                                {{ $tenant->name }} - {{ $tenant->room->name ?? 'Tanpa Kamar' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="room_id" class="block text-sm font-medium text-gray-700 mb-2">Kamar <span class="text-red-500">*</span></label>
                        <select id="room_id" name="room_id" required x-model="roomId"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all">
                            <option value="">Pilih Kamar</option>
                            @foreach($rooms as $room)
                            <option value="{{ $room->id }}" data-price="{{ $room->price }}">{{ $room->name }} - Rp {{ number_format($room->price, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Periode -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Periode Tagihan</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                        <div>
                            <label for="period_month" class="block text-sm font-medium text-gray-700 mb-2">Bulan <span class="text-red-500">*</span></label>
                            <select id="period_month" name="period_month" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                                @for($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ date('n') == $i ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label for="period_year" class="block text-sm font-medium text-gray-700 mb-2">Tahun <span class="text-red-500">*</span></label>
                            <select id="period_year" name="period_year" required
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                                @for($y = date('Y') - 1; $y <= date('Y') + 1; $y++)
                                <option value="{{ $y }}" {{ date('Y') == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">Jatuh Tempo <span class="text-red-500">*</span></label>
                            <input type="date" id="due_date" name="due_date" value="{{ date('Y-m-10') }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Komponen Tagihan -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Komponen Tagihan</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="room_price" class="block text-sm font-medium text-gray-700 mb-2">Sewa Kamar</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" id="room_price" name="room_price" x-model="roomPrice" min="0"
                                       class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                            </div>
                        </div>
                        <div>
                            <label for="electricity_fee" class="block text-sm font-medium text-gray-700 mb-2">Listrik</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" id="electricity_fee" name="electricity_fee" min="0" x-model="electricityFee"
                                       class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                            </div>
                        </div>
                        <div>
                            <label for="water_fee" class="block text-sm font-medium text-gray-700 mb-2">Air</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" id="water_fee" name="water_fee" min="0" x-model="waterFee"
                                       class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                            </div>
                        </div>
                        <div>
                            <label for="internet_fee" class="block text-sm font-medium text-gray-700 mb-2">Internet</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" id="internet_fee" name="internet_fee" min="0" x-model="internetFee"
                                       class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                            </div>
                        </div>
                        <div>
                            <label for="penalty_fee" class="block text-sm font-medium text-gray-700 mb-2">Denda</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" id="penalty_fee" name="penalty_fee" min="0" x-model="penaltyFee"
                                       class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                            </div>
                        </div>
                        <div>
                            <label for="other_fee" class="block text-sm font-medium text-gray-700 mb-2">Biaya Lain</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" id="other_fee" name="other_fee" min="0" x-model="otherFee"
                                       class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="other_fee_description" class="block text-sm font-medium text-gray-700 mb-2">Keterangan Biaya Lain</label>
                        <input type="text" id="other_fee_description" name="other_fee_description"
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all" placeholder="Contoh: Service AC">
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea id="notes" name="notes" rows="2"
                                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all resize-none"></textarea>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="space-y-6">
            <!-- Summary -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden sticky top-24">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Ringkasan</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Sewa Kamar</span>
                            <span class="font-medium" x-text="formatRupiah(roomPrice || 0)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Listrik</span>
                            <span class="font-medium" x-text="formatRupiah(electricityFee || 0)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Air</span>
                            <span class="font-medium" x-text="formatRupiah(waterFee || 0)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Internet</span>
                            <span class="font-medium" x-text="formatRupiah(internetFee || 0)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Denda</span>
                            <span class="font-medium" x-text="formatRupiah(penaltyFee || 0)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Biaya Lain</span>
                            <span class="font-medium" x-text="formatRupiah(otherFee || 0)"></span>
                        </div>
                        <div class="pt-3 mt-3 border-t border-gray-200">
                            <div class="flex justify-between text-lg">
                                <span class="font-semibold text-gray-900">Total</span>
                                <span class="font-bold text-primary-600" x-text="formatRupiah(total)"></span>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full mt-6 py-3 px-4 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-xl transition-all duration-300">
                        Simpan Invoice
                    </button>
                    <a href="{{ route('admin.invoices.index') }}" class="mt-3 w-full py-3 px-4 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 font-medium rounded-xl transition-colors block text-center">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function invoiceForm() {
    return {
        tenantId: '',
        roomId: '',
        roomPrice: 0,
        electricityFee: 0,
        waterFee: 0,
        internetFee: 0,
        penaltyFee: 0,
        otherFee: 0,
        
        get total() {
            return (parseInt(this.roomPrice) || 0) + 
                   (parseInt(this.electricityFee) || 0) + 
                   (parseInt(this.waterFee) || 0) + 
                   (parseInt(this.internetFee) || 0) + 
                   (parseInt(this.penaltyFee) || 0) + 
                   (parseInt(this.otherFee) || 0);
        },
        
        updateRoom() {
            const select = document.getElementById('tenant_id');
            const option = select.options[select.selectedIndex];
            if (option) {
                this.roomId = option.dataset.roomId || '';
                this.roomPrice = parseInt(option.dataset.roomPrice) || 0;
            }
        },
        
        formatRupiah(num) {
            return 'Rp ' + new Intl.NumberFormat('id-ID').format(num);
        }
    }
}
</script>
@endpush
