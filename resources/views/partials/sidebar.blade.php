<div class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
        <div class="flex items-center">
            <svg class="h-8 w-8 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                    d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM10 4.414L5 8.414V17h2v-4a1 1 0 011-1h4a1 1 0 011 1v4h2V8.414L10 4.414z"
                    clip-rule="evenodd" />
            </svg>
            <span class="ml-2 text-lg font-bold text-gray-900">Inspektorat</span>
        </div>
        <button @click="sidebarOpen = false" class="lg:hidden text-gray-500 hover:text-gray-700">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-6">
        @if (auth()->user()->role === 'Admin')
            @include('partials.navigation.admin')
        @elseif(auth()->user()->role === 'Ketua_Bidang_Investigasi')
            @include('partials.navigation.ketua-bidang')
        @elseif(auth()->user()->role === 'Pegawai')
            @include('partials.navigation.pegawai')
        @elseif(auth()->user()->role === 'Warga')
            @include('partials.navigation.warga')
        @elseif(auth()->user()->role === 'Sekretaris')
            @include('partials.navigation.sekretaris')
        @elseif(auth()->user()->role === 'Kepala_Inspektorat')
            @include('partials.navigation.kepala-inspektorat')
        @endif
    </nav>

    <!-- User Info -->
    <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-gray-200">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center">
                <span class="text-sm font-medium text-white">
                    {{ substr(auth()->user()->nama_lengkap, 0, 1) }}
                </span>
            </div>
            <div class="ml-3 flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">
                    {{ auth()->user()->nama_lengkap }}
                </p>
                <p class="text-xs text-gray-500 truncate">
                    {{ str_replace('_', ' ', auth()->user()->role) }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Sidebar overlay for mobile -->
<div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden"
    x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
</div>
