@extends('admin.layouts.app')

@section('title', 'Pengaturan')

@section('breadcrumb')
<li class="flex items-center">
    <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-primary-600">Dashboard</a>
    <svg class="w-4 h-4 mx-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
</li>
<li class="font-medium text-gray-800">Pengaturan</li>
@endsection

@section('header')
<div>
    <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Pengaturan</h1>
    <p class="mt-1 text-gray-500">Konfigurasi sistem dan profil kost</p>
</div>
@endsection

@section('content')
<div class="max-w-4xl" x-data="{ activeTab: 'profile' }">
    <!-- Tabs -->
    <div class="flex border-b border-gray-200 mb-6 overflow-x-auto">
        <button @click="activeTab = 'profile'" :class="activeTab === 'profile' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap">Profil Kost</button>
        <button @click="activeTab = 'payment'" :class="activeTab === 'payment' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap">Rekening Pembayaran</button>
        <button @click="activeTab = 'invoice'" :class="activeTab === 'invoice' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap">Branding Invoice</button>
        <button @click="activeTab = 'email'" :class="activeTab === 'email' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap">Konfigurasi Email</button>
        <button @click="activeTab = 'rules'" :class="activeTab === 'rules' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700'" class="px-4 py-3 text-sm font-medium border-b-2 whitespace-nowrap">Aturan Kost</button>
    </div>
    
    <!-- Profil Kost -->
    <div x-show="activeTab === 'profile'" x-cloak>
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @csrf
            @method('PUT')
            <input type="hidden" name="group" value="profile">
            
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Profil Kost</h3>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kost</label>
                    <input type="text" name="settings[kost_name]" value="{{ $settings['kost_name'] ?? '' }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                    <textarea name="settings[kost_address]" rows="2" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all resize-none">{{ $settings['kost_address'] ?? '' }}</textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                        <input type="text" name="settings[kost_phone]" value="{{ $settings['kost_phone'] ?? '' }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="settings[kost_email]" value="{{ $settings['kost_email'] ?? '' }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo</label>
                    <input type="file" name="logo" accept="image/*" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors">Simpan</button>
            </div>
        </form>
    </div>
    
    <!-- Rekening Pembayaran -->
    <div x-show="activeTab === 'payment'" x-cloak>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Rekening Pembayaran</h3>
            </div>
            <div class="p-8 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-50 mb-4">
                    <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <h4 class="text-xl font-bold text-gray-900 mb-2">Kelola Metode Pembayaran</h4>
                <p class="text-gray-500 mb-6 max-w-md mx-auto">Pengaturan rekening pembayaran telah dipindahkan ke menu Metode Pembayaran. Anda dapat mengelola banyak rekening bank dan e-wallet di sana.</p>
                <a href="{{ route('admin.payment-methods.index') }}" class="inline-flex items-center px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors">
                    Kelola Metode Pembayaran
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Branding Invoice -->
    <div x-show="activeTab === 'invoice'" x-cloak>
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @csrf
            @method('PUT')
            <input type="hidden" name="group" value="invoice">
            
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Branding Invoice</h3>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prefix Nomor Invoice</label>
                    <input type="text" name="settings[invoice_prefix]" value="{{ $settings['invoice_prefix'] ?? 'INV' }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan di Invoice</label>
                    <textarea name="settings[invoice_notes]" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all resize-none" placeholder="Catatan yang muncul di invoice...">{{ $settings['invoice_notes'] ?? '' }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Footer Invoice</label>
                    <input type="text" name="settings[invoice_footer]" value="{{ $settings['invoice_footer'] ?? '' }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all" placeholder="Terima kasih atas pembayaran Anda">
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors">Simpan</button>
            </div>
        </form>
    </div>
    
    <!-- Konfigurasi Email -->
    <div x-show="activeTab === 'email'" x-cloak>
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @csrf
            @method('PUT')
            <input type="hidden" name="group" value="email">
            
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Konfigurasi SMTP</h3>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Host</label>
                        <input type="text" name="settings[smtp_host]" value="{{ $settings['smtp_host'] ?? '' }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all" placeholder="smtp.gmail.com">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">SMTP Port</label>
                        <input type="text" name="settings[smtp_port]" value="{{ $settings['smtp_port'] ?? '587' }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Username</label>
                    <input type="email" name="settings[smtp_username]" value="{{ $settings['smtp_username'] ?? '' }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Password</label>
                    <input type="password" name="settings[smtp_password]" value="{{ $settings['smtp_password'] ?? '' }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">From Name</label>
                    <input type="text" name="settings[mail_from_name]" value="{{ $settings['mail_from_name'] ?? '' }}" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all">
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors">Simpan</button>
            </div>
        </form>
    </div>
    
    <!-- Aturan Kost -->
    <div x-show="activeTab === 'rules'" x-cloak>
        <form action="{{ route('admin.settings.update') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @csrf
            @method('PUT')
            <input type="hidden" name="group" value="rules">
            
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-900">Aturan Kost</h3>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Aturan Kost</label>
                    <textarea name="settings[kost_rules]" rows="10" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 transition-all resize-none" placeholder="1. Dilarang merokok di dalam kamar&#10;2. Jam malam pukul 22:00&#10;3. ...">{{ is_array($settings['kost_rules'] ?? '') ? implode("\n", $settings['kost_rules']) : ($settings['kost_rules'] ?? '') }}</textarea>
                </div>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="submit" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl transition-colors">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
