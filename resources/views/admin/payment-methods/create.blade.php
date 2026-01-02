@extends('admin.layouts.app')

@section('title', 'Tambah Metode Pembayaran')

@section('header')
<div class="flex items-center gap-4">
    <a href="{{ route('admin.payment-methods.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Metode Pembayaran</h1>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.payment-methods.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="p-6 space-y-6">
                <!-- Nama Bank -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Bank / E-Wallet</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                           class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-primary-500 focus:border-primary-500"
                           placeholder="Contoh: Bank BCA, GoPay">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- No Rekening -->
                    <div>
                        <label for="account_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor Rekening</label>
                        <input type="text" name="account_number" id="account_number" value="{{ old('account_number') }}" required 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-primary-500 focus:border-primary-500"
                               placeholder="1234567890">
                        @error('account_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <!-- Atas Nama -->
                    <div>
                        <label for="account_holder" class="block text-sm font-medium text-gray-700 mb-2">Atas Nama</label>
                        <input type="text" name="account_holder" id="account_holder" value="{{ old('account_holder') }}" required 
                               class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-primary-500 focus:border-primary-500"
                               placeholder="Nama Pemilik Rekening">
                        @error('account_holder')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <!-- Deskripsi/Instruksi -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Instruksi Transfer (Opsional)</label>
                    <textarea name="description" id="description" rows="3" 
                              class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:ring-primary-500 focus:border-primary-500"
                              placeholder="Masukkan instruksi jika ada">{{ old('description') }}</textarea>
                    @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Logo Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo Bank / E-Wallet</label>
                    <div class="flex items-center gap-4">
                        <div class="flex-shrink-0 w-20 h-20 bg-gray-100 rounded-xl flex items-center justify-center border border-gray-200 overflow-hidden" id="logo-preview-container">
                             <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                              </svg>
                        </div>
                        <div>
                            <input type="file" name="logo" id="logo" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100" onchange="previewLogo(this)">
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            @error('logo')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                <!-- Status Checkbox -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                        Aktifkan metode pembayaran ini
                    </label>
                </div>

            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.payment-methods.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewLogo(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logo-preview-container').innerHTML = '<img src="'+e.target.result+'" class="w-full h-full object-contain">';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
