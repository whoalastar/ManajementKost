@extends('admin.layouts.app')

@section('title', 'Tambah Fasilitas')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="flex items-center">
    <a href="{{ route('admin.facilities.index') }}" class="text-gray-500 hover:text-primary-600">Fasilitas</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="font-medium text-gray-800">Tambah</li>
@endsection

@section('header')
<div class="flex items-center gap-4">
    <a href="{{ route('admin.facilities.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Tambah Fasilitas</h1>
        <p class="mt-1 text-gray-500">Buat fasilitas baru</p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.facilities.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf
        
        <div class="p-6 space-y-6">
            <!-- Nama -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Fasilitas <span class="text-red-500">*</span></label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all @error('name') border-red-500 @enderror"
                       placeholder="Contoh: AC, WiFi, Parkir Motor">
                @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            
            <!-- Tipe -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Fasilitas <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 gap-4">
                    @foreach($types as $value => $label)
                    <label class="flex items-center gap-3 p-4 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
                        <input type="radio" name="type" value="{{ $value }}" {{ old('type', 'room') == $value ? 'checked' : '' }} class="w-4 h-4 text-primary-600">
                        <div>
                            <span class="font-medium text-gray-900">{{ $label }}</span>
                            <p class="text-sm text-gray-500">{{ $value === 'room' ? 'Fasilitas di dalam kamar' : 'Fasilitas bersama' }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
            
            <!-- Icon -->
            <div>
                <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                <div class="flex flex-wrap gap-2 mb-3">
                     <button type="button" onclick="document.getElementById('icon').value = '★'" class="w-10 h-10 flex items-center justify-center bg-gray-50 border border-gray-200 hover:bg-primary-50 hover:border-primary-200 hover:text-primary-600 rounded-lg text-xl transition-all" title="Bintang">★</button>
                     <button type="button" onclick="document.getElementById('icon').value = '☆'" class="w-10 h-10 flex items-center justify-center bg-gray-50 border border-gray-200 hover:bg-primary-50 hover:border-primary-200 hover:text-primary-600 rounded-lg text-xl transition-all" title="Bintang Outline">☆</button>
                     <button type="button" onclick="document.getElementById('icon').value = '✔'" class="w-10 h-10 flex items-center justify-center bg-gray-50 border border-gray-200 hover:bg-primary-50 hover:border-primary-200 hover:text-primary-600 rounded-lg text-xl transition-all" title="Ceklis">✔</button>
                     <button type="button" onclick="document.getElementById('icon').value = '✓'" class="w-10 h-10 flex items-center justify-center bg-gray-50 border border-gray-200 hover:bg-primary-50 hover:border-primary-200 hover:text-primary-600 rounded-lg text-xl transition-all" title="Ceklis Tipis">✓</button>
                     <button type="button" onclick="document.getElementById('icon').value = '■'" class="w-10 h-10 flex items-center justify-center bg-gray-50 border border-gray-200 hover:bg-primary-50 hover:border-primary-200 hover:text-primary-600 rounded-lg text-xl transition-all" title="Kotak">■</button>
                     <button type="button" onclick="document.getElementById('icon').value = '□'" class="w-10 h-10 flex items-center justify-center bg-gray-50 border border-gray-200 hover:bg-primary-50 hover:border-primary-200 hover:text-primary-600 rounded-lg text-xl transition-all" title="Kotak Outline">□</button>
                     <button type="button" onclick="document.getElementById('icon').value = '●'" class="w-10 h-10 flex items-center justify-center bg-gray-50 border border-gray-200 hover:bg-primary-50 hover:border-primary-200 hover:text-primary-600 rounded-lg text-xl transition-all" title="Lingkaran">●</button>
                     <button type="button" onclick="document.getElementById('icon').value = '○'" class="w-10 h-10 flex items-center justify-center bg-gray-50 border border-gray-200 hover:bg-primary-50 hover:border-primary-200 hover:text-primary-600 rounded-lg text-xl transition-all" title="Lingkaran Outline">○</button>
                     <button type="button" onclick="document.getElementById('icon').value = '◆'" class="w-10 h-10 flex items-center justify-center bg-gray-50 border border-gray-200 hover:bg-primary-50 hover:border-primary-200 hover:text-primary-600 rounded-lg text-xl transition-all" title="Diamond">◆</button>
                </div>
                <input type="text" id="icon" name="icon" value="{{ old('icon') }}"
                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all"
                       placeholder="Pilih simbol di atas">
                <p class="mt-2 text-sm text-gray-500">Pilih simbol untuk representasi fasilitas</p>
            </div>
            
            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all resize-none"
                          placeholder="Deskripsi singkat tentang fasilitas ini...">{{ old('description') }}</textarea>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.facilities.index') }}" class="px-5 py-2.5 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 font-medium rounded-xl transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-xl transition-all duration-300">Simpan</button>
        </div>
    </form>
</div>
@endsection
