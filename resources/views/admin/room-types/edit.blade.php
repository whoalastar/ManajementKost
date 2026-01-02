@extends('admin.layouts.app')

@section('title', 'Edit Tipe Kamar')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
</li>
<li class="flex items-center">
    <a href="{{ route('admin.room-types.index') }}" class="text-gray-500 hover:text-primary-600">Tipe Kamar</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
</li>
<li class="font-medium text-gray-800">Edit</li>
@endsection

@section('header')
<div class="flex items-center gap-4">
    <a href="{{ route('admin.room-types.index') }}" 
       class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Edit Tipe Kamar</h1>
        <p class="mt-1 text-gray-500">Perbarui data tipe kamar <strong>{{ $roomType->name }}</strong></p>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-2xl">
    <form action="{{ route('admin.room-types.update', $roomType) }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        @csrf
        @method('PUT')
        
        <div class="p-6 space-y-6">
            <!-- Nama Tipe -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Tipe <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $roomType->name) }}"
                       required
                       class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all @error('name') border-red-500 @enderror"
                       placeholder="Contoh: Standard, Deluxe, VIP">
                @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Deskripsi -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all resize-none @error('description') border-red-500 @enderror"
                          placeholder="Deskripsi singkat tentang tipe kamar ini...">{{ old('description', $roomType->description) }}</textarea>
                @error('description')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Info -->
            <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-blue-700">
                        <p class="font-medium">Informasi</p>
                        <p class="mt-1">Tipe kamar ini digunakan oleh <strong>{{ $roomType->rooms_count ?? 0 }}</strong> kamar.</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
            <a href="{{ route('admin.room-types.index') }}" 
               class="px-5 py-2.5 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 font-medium rounded-xl transition-colors">
                Batal
            </a>
            <button type="submit" 
                    class="px-5 py-2.5 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-xl transition-all duration-300">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
