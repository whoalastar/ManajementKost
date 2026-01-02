@php
    $isMobile = $isMobile ?? false;
    $showLabel = $isMobile ? true : 'sidebarOpen';
@endphp

<!-- Dashboard -->
<a href="{{ route('admin.dashboard') }}" 
   class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
          {{ request()->routeIs('admin.dashboard*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/30' : 'text-secondary-300 hover:bg-secondary-700/50 hover:text-white' }}">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
    </svg>
    @if($isMobile)
        <span class="ml-3 font-medium">Dashboard</span>
    @else
        <span x-show="sidebarOpen" x-transition class="ml-3 font-medium">Dashboard</span>
    @endif
</a>

<!-- Section: Properti -->
@if($isMobile)
    <div class="pt-4 pb-2"><p class="px-3 text-xs font-semibold text-secondary-500 uppercase tracking-wider">Properti</p></div>
@else
    <div x-show="sidebarOpen" class="pt-4 pb-2"><p class="px-3 text-xs font-semibold text-secondary-500 uppercase tracking-wider">Properti</p></div>
@endif

<!-- Tipe Kamar -->
<a href="{{ route('admin.room-types.index') }}" 
   class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
          {{ request()->routeIs('admin.room-types*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/30' : 'text-secondary-300 hover:bg-secondary-700/50 hover:text-white' }}">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
    </svg>
    @if($isMobile)<span class="ml-3 font-medium">Tipe Kamar</span>@else<span x-show="sidebarOpen" x-transition class="ml-3 font-medium">Tipe Kamar</span>@endif
</a>

<!-- Kamar -->
<a href="{{ route('admin.rooms.index') }}" 
   class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
          {{ request()->routeIs('admin.rooms*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/30' : 'text-secondary-300 hover:bg-secondary-700/50 hover:text-white' }}">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
    </svg>
    @if($isMobile)<span class="ml-3 font-medium">Kamar</span>@else<span x-show="sidebarOpen" x-transition class="ml-3 font-medium">Kamar</span>@endif
</a>

<!-- Fasilitas -->
<a href="{{ route('admin.facilities.index') }}" 
   class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
          {{ request()->routeIs('admin.facilities*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/30' : 'text-secondary-300 hover:bg-secondary-700/50 hover:text-white' }}">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
    </svg>
    @if($isMobile)<span class="ml-3 font-medium">Fasilitas</span>@else<span x-show="sidebarOpen" x-transition class="ml-3 font-medium">Fasilitas</span>@endif
</a>

<!-- Section: Penghuni -->
@if($isMobile)
    <div class="pt-4 pb-2"><p class="px-3 text-xs font-semibold text-secondary-500 uppercase tracking-wider">Penghuni</p></div>
@else
    <div x-show="sidebarOpen" class="pt-4 pb-2"><p class="px-3 text-xs font-semibold text-secondary-500 uppercase tracking-wider">Penghuni</p></div>
@endif

<!-- Penghuni -->
<a href="{{ route('admin.tenants.index') }}" 
   class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
          {{ request()->routeIs('admin.tenants*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/30' : 'text-secondary-300 hover:bg-secondary-700/50 hover:text-white' }}">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
    </svg>
    @if($isMobile)<span class="ml-3 font-medium">Penghuni</span>@else<span x-show="sidebarOpen" x-transition class="ml-3 font-medium">Penghuni</span>@endif
</a>

<!-- Booking -->
<a href="{{ route('admin.bookings.index') }}" 
   class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
          {{ request()->routeIs('admin.bookings*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/30' : 'text-secondary-300 hover:bg-secondary-700/50 hover:text-white' }}">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
    </svg>
    @if($isMobile)<span class="ml-3 font-medium">Booking</span>@else<span x-show="sidebarOpen" x-transition class="ml-3 font-medium">Booking</span>@endif
</a>

<!-- Section: Keuangan -->
@if($isMobile)
    <div class="pt-4 pb-2"><p class="px-3 text-xs font-semibold text-secondary-500 uppercase tracking-wider">Keuangan</p></div>
@else
    <div x-show="sidebarOpen" class="pt-4 pb-2"><p class="px-3 text-xs font-semibold text-secondary-500 uppercase tracking-wider">Keuangan</p></div>
@endif

<!-- Invoice -->
<a href="{{ route('admin.invoices.index') }}" 
   class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
          {{ request()->routeIs('admin.invoices*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/30' : 'text-secondary-300 hover:bg-secondary-700/50 hover:text-white' }}">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
    </svg>
    @if($isMobile)<span class="ml-3 font-medium">Invoice</span>@else<span x-show="sidebarOpen" x-transition class="ml-3 font-medium">Invoice</span>@endif
</a>

<!-- Pembayaran -->
<a href="{{ route('admin.payments.index') }}" 
   class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
          {{ request()->routeIs('admin.payments*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/30' : 'text-secondary-300 hover:bg-secondary-700/50 hover:text-white' }}">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
    </svg>
    @if($isMobile)<span class="ml-3 font-medium">Pembayaran</span>@else<span x-show="sidebarOpen" x-transition class="ml-3 font-medium">Pembayaran</span>@endif
</a>

<!-- Metode Pembayaran -->
<a href="{{ route('admin.payment-methods.index') }}" 
   class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
          {{ request()->routeIs('admin.payment-methods*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/30' : 'text-secondary-300 hover:bg-secondary-700/50 hover:text-white' }}">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a1 1 0 11-2 0 1 1 0 012 0z"/>
    </svg>
    @if($isMobile)<span class="ml-3 font-medium">Metode Pembayaran</span>@else<span x-show="sidebarOpen" x-transition class="ml-3 font-medium">Metode Pembayaran</span>@endif
</a>

<!-- Section: Layanan -->
@if($isMobile)
    <div class="pt-4 pb-2"><p class="px-3 text-xs font-semibold text-secondary-500 uppercase tracking-wider">Layanan</p></div>
@else
    <div x-show="sidebarOpen" class="pt-4 pb-2"><p class="px-3 text-xs font-semibold text-secondary-500 uppercase tracking-wider">Layanan</p></div>
@endif

<!-- Maintenance -->
<a href="{{ route('admin.maintenance.index') }}" 
   class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
          {{ request()->routeIs('admin.maintenance*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/30' : 'text-secondary-300 hover:bg-secondary-700/50 hover:text-white' }}">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>
    @if($isMobile)<span class="ml-3 font-medium">Maintenance</span>@else<span x-show="sidebarOpen" x-transition class="ml-3 font-medium">Maintenance</span>@endif
</a>

<!-- Section: Laporan -->
@if($isMobile)
    <div class="pt-4 pb-2"><p class="px-3 text-xs font-semibold text-secondary-500 uppercase tracking-wider">Laporan</p></div>
@else
    <div x-show="sidebarOpen" class="pt-4 pb-2"><p class="px-3 text-xs font-semibold text-secondary-500 uppercase tracking-wider">Laporan</p></div>
@endif

<!-- Laporan -->
<a href="{{ route('admin.reports.index') }}" 
   class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
          {{ request()->routeIs('admin.reports*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/30' : 'text-secondary-300 hover:bg-secondary-700/50 hover:text-white' }}">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
    </svg>
    @if($isMobile)<span class="ml-3 font-medium">Laporan</span>@else<span x-show="sidebarOpen" x-transition class="ml-3 font-medium">Laporan</span>@endif
</a>

<!-- Activity Log -->
<a href="{{ route('admin.activity-logs.index') }}" 
   class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
          {{ request()->routeIs('admin.activity-logs*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/30' : 'text-secondary-300 hover:bg-secondary-700/50 hover:text-white' }}">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    @if($isMobile)<span class="ml-3 font-medium">Log Aktivitas</span>@else<span x-show="sidebarOpen" x-transition class="ml-3 font-medium">Log Aktivitas</span>@endif
</a>

<!-- Section: Sistem -->
@if($isMobile)
    <div class="pt-4 pb-2"><p class="px-3 text-xs font-semibold text-secondary-500 uppercase tracking-wider">Sistem</p></div>
@else
    <div x-show="sidebarOpen" class="pt-4 pb-2"><p class="px-3 text-xs font-semibold text-secondary-500 uppercase tracking-wider">Sistem</p></div>
@endif

<!-- Settings -->
<a href="{{ route('admin.settings.index') }}" 
   class="flex items-center px-3 py-2.5 rounded-xl transition-all duration-200 group
          {{ request()->routeIs('admin.settings*') ? 'bg-primary-600 text-white shadow-lg shadow-primary-600/30' : 'text-secondary-300 hover:bg-secondary-700/50 hover:text-white' }}">
    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
    </svg>
    @if($isMobile)<span class="ml-3 font-medium">Pengaturan</span>@else<span x-show="sidebarOpen" x-transition class="ml-3 font-medium">Pengaturan</span>@endif
</a>
