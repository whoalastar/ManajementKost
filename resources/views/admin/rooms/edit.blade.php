@extends('admin.layouts.app')

@section('title', 'Edit Kamar')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="flex items-center">
    <a href="{{ route('admin.rooms.index') }}" class="text-gray-500 hover:text-primary-600">Kamar</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="font-medium text-gray-800">Edit {{ $room->name }}</li>
@endsection

@section('header')
<div class="flex items-center gap-4">
    <a href="{{ route('admin.rooms.index') }}" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
    </a>
    <div>
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Edit Kamar</h1>
        <p class="mt-1 text-gray-500">{{ $room->code }} - {{ $room->name }}</p>
    </div>
</div>
@endsection

@section('content')
@if($errors->any())
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm leading-5 font-medium text-red-800">
                    Terdapat batasan kesalahan pada keserupaan input
                </h3>
                <div class="mt-2 text-sm leading-5 text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif

<form action="{{ route('admin.rooms.update', $room) }}" method="POST" enctype="multipart/form-data" class="max-w-4xl">
    @csrf
    @method('PUT')
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Informasi Dasar</h3>
                </div>
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Kode Kamar -->
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">Kode Kamar <span class="text-red-500">*</span></label>
                            <input type="text" id="code" name="code" value="{{ old('code', $room->code) }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all @error('code') border-red-500 @enderror"
                                   placeholder="A-101">
                            @error('code')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        
                        <!-- Nama Kamar -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Kamar <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $room->name) }}" required
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all @error('name') border-red-500 @enderror"
                                   placeholder="Kamar 101">
                            @error('name')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- Tipe Kamar -->
                        <div>
                            <label for="room_type_id" class="block text-sm font-medium text-gray-700 mb-2">Tipe Kamar</label>
                            <select id="room_type_id" name="room_type_id"
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all">
                                <option value="">Pilih Tipe</option>
                                @foreach($roomTypes as $type)
                                <option value="{{ $type->id }}" {{ old('room_type_id', $room->room_type_id) == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Lantai -->
                        <div>
                            <label for="floor" class="block text-sm font-medium text-gray-700 mb-2">Lantai <span class="text-red-500">*</span></label>
                            <input type="number" id="floor" name="floor" value="{{ old('floor', $room->floor) }}" required min="1"
                                   class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all @error('floor') border-red-500 @enderror">
                            @error('floor')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    
                    <!-- Harga -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga Sewa/Bulan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                            <input type="number" id="price" name="price" value="{{ old('price', $room->price) }}" required min="0"
                                   class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all @error('price') border-red-500 @enderror"
                                   placeholder="500000">
                        </div>
                        @error('price')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    
                    <!-- Deskripsi -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="description" name="description" rows="3"
                                  class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 focus:bg-white transition-all resize-none"
                                  placeholder="Deskripsi kamar...">{{ old('description', $room->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Facilities -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Fasilitas Kamar</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($facilities as $facility)
                        <label class="flex items-center gap-3 p-3 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-primary-500 has-[:checked]:bg-primary-50">
                            <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" {{ in_array($facility->id, old('facilities', $room->facilities->pluck('id')->toArray())) ? 'checked' : '' }} class="w-4 h-4 text-primary-600 rounded border-gray-300 focus:ring-primary-500">
                            <span class="text-gray-700 whitespace-nowrap overflow-hidden text-ellipsis">{{ $facility->icon }} {{ $facility->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @if($facilities->isEmpty())
                    <p class="text-gray-500 text-sm italic">Belum ada fasilitas. <a href="{{ route('admin.facilities.index') }}" class="text-primary-600 hover:underline">Tambah fasilitas</a></p>
                    @endif
                </div>
            </div>

            <!-- Existing Photos -->
            @if($room->photos->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Foto Saat Ini</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-4 gap-4">
                        @foreach($room->photos as $photo)
                        <div class="relative aspect-square rounded-xl overflow-hidden bg-gray-100 group">
                            <img src="{{ asset($photo->path) }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                @if(!$photo->is_primary)
                                <form action="{{ route('admin.rooms.set-primary-photo', [$room, $photo]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 bg-white rounded-lg text-green-600 hover:bg-green-50" title="Jadikan foto utama">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.rooms.delete-photo', $photo) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 bg-white rounded-lg text-red-600 hover:bg-red-50" title="Hapus foto">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                            @if($photo->is_primary)
                            <span class="absolute top-2 left-2 px-2 py-1 bg-green-500 text-white text-xs font-medium rounded-lg">Utama</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            
            <!-- New Photos -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Tambah Foto Baru</h3>
                </div>
                <div class="p-6">
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-primary-400 transition-colors">
                        <input type="file" id="photos" name="photos[]" multiple accept="image/*" class="hidden" onchange="previewImages(this)">
                        <label for="photos" class="cursor-pointer">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-gray-600 font-medium">Klik atau drag foto ke sini</p>
                            <p class="text-sm text-gray-500 mt-1">PNG, JPG maksimal 2MB</p>
                        </label>
                    </div>
                    @error('photos')
                        <p class="mt-2 text-sm text-red-600 bg-red-50 p-2 rounded-lg text-center">{{ $message }}</p>
                    @enderror
                    @error('photos.*')
                        <p class="mt-2 text-sm text-red-600 bg-red-50 p-2 rounded-lg text-center">{{ $message }}</p>
                    @enderror
                    <div id="image-preview" class="grid grid-cols-4 gap-4 mt-4"></div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-900">Status</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                            <input type="radio" name="status" value="empty" {{ old('status', $room->status) == 'empty' ? 'checked' : '' }} class="w-4 h-4 text-green-600">
                            <div><span class="font-medium text-gray-900">Kosong</span><p class="text-sm text-gray-500">Siap disewakan</p></div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-orange-500 has-[:checked]:bg-orange-50">
                            <input type="radio" name="status" value="occupied" {{ old('status', $room->status) == 'occupied' ? 'checked' : '' }} class="w-4 h-4 text-orange-600">
                            <div><span class="font-medium text-gray-900">Terisi</span><p class="text-sm text-gray-500">Sudah ada penghuni</p></div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                            <input type="radio" name="status" value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'checked' : '' }} class="w-4 h-4 text-red-600">
                            <div><span class="font-medium text-gray-900">Maintenance</span><p class="text-sm text-gray-500">Sedang diperbaiki</p></div>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-6">
                <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-primary-600 to-primary-700 hover:from-primary-700 hover:to-primary-800 text-white font-semibold rounded-xl shadow-lg shadow-primary-500/30 hover:shadow-xl transition-all duration-300">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.rooms.index') }}" class="mt-3 w-full py-3 px-4 text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 font-medium rounded-xl transition-colors block text-center">
                    Batal
                </a>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function previewImages(input) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    if (input.files) {
        Array.from(input.files).forEach((file) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative aspect-square rounded-xl overflow-hidden bg-gray-100';
                div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                preview.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
    }
}
</script>
@endpush
