<!-- Desktop Sidebar -->
<aside class="fixed inset-y-0 left-0 z-40 bg-gradient-to-b from-secondary-900 via-secondary-800 to-secondary-900 shadow-xl sidebar-transition hidden lg:block"
       :class="sidebarOpen ? 'w-64' : 'w-20'">
    
    <!-- Logo Section -->
    <div class="flex items-center h-16 px-4 border-b border-secondary-700/50">
        <div class="flex items-center space-x-3 overflow-hidden">
            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <div x-show="sidebarOpen" x-transition class="whitespace-nowrap">
                <h1 class="text-white font-bold text-lg leading-tight">Kostra</h1>
                <p class="text-secondary-400 text-xs">Admin Panel</p>
            </div>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 scrollbar-hide" style="max-height: calc(100vh - 120px);">
        @include('admin.layouts.partials.nav-items')
    </nav>
    
    <!-- Collapse Button -->
    <div class="absolute bottom-4 left-0 right-0 px-3 hidden lg:block">
        <button @click="sidebarOpen = !sidebarOpen" 
                class="w-full flex items-center justify-center px-3 py-2 rounded-xl text-secondary-400 hover:bg-secondary-700/50 hover:text-white transition-all duration-200">
            <svg x-show="sidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
            <svg x-show="!sidebarOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
            </svg>
        </button>
    </div>
</aside>

<!-- Mobile Sidebar -->
<aside class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-secondary-900 via-secondary-800 to-secondary-900 shadow-xl transform transition-transform duration-300 lg:hidden"
       :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'">
    
    <div class="flex items-center justify-between h-16 px-4 border-b border-secondary-700/50">
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <div>
                <h1 class="text-white font-bold text-lg leading-tight">KostApp</h1>
                <p class="text-secondary-400 text-xs">Admin Panel</p>
            </div>
        </div>
        <button @click="mobileMenuOpen = false" class="p-2 text-secondary-400 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 scrollbar-hide" style="max-height: calc(100vh - 64px);">
        @include('admin.layouts.partials.nav-items', ['isMobile' => true])
    </nav>
</aside>
