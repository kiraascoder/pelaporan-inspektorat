<nav class="bg-white shadow-lg border-b border-gray-200" x-data="{ mobileMenuOpen: false, profileDropdown: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo & Brand -->
            <div class="flex items-center">
                <div class="flex-shrink-0 flex items-center">
                    <svg class="h-8 w-8 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 2L3 7v11a1 1 0 001 1h12a1 1 0 001-1V7l-7-5zM10 4.414L5 8.414V17h2v-4a1 1 0 011-1h4a1 1 0 011 1v4h2V8.414L10 4.414z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="ml-2 text-xl font-bold text-gray-900">
                        Inspektorat
                    </span>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:ml-10 md:flex md:space-x-8">
                    <a href="{{ url('/') }}"
                        class="text-gray-500 hover:text-primary-600 px-3 py-2 text-sm font-medium transition-colors duration-200">
                        Beranda
                    </a>
                    <a href="#tentang"
                        class="text-gray-500 hover:text-primary-600 px-3 py-2 text-sm font-medium transition-colors duration-200">
                        Tentang
                    </a>
                    <a href="#layanan"
                        class="text-gray-500 hover:text-primary-600 px-3 py-2 text-sm font-medium transition-colors duration-200">
                        Layanan
                    </a>
                    <a href="#kontak"
                        class="text-gray-500 hover:text-primary-600 px-3 py-2 text-sm font-medium transition-colors duration-200">
                        Kontak
                    </a>
                </div>
            </div>

            <!-- Desktop Auth Buttons -->
            <div class="hidden md:flex md:items-center md:space-x-4">
                @guest
                    <a href=""
                        class="text-gray-500 hover:text-primary-600 px-3 py-2 text-sm font-medium transition-colors duration-200">
                        Masuk
                    </a>
                    <a href="#"
                        class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        Daftar
                    </a>
                @else
                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center text-sm rounded-full text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            <div class="h-8 w-8 rounded-full bg-primary-600 flex items-center justify-center">
                                <span class="text-sm font-medium text-white">
                                    {{ substr(auth()->user()->nama_lengkap, 0, 1) }}
                                </span>
                            </div>
                            <span class="ml-2 font-medium">{{ auth()->user()->nama_lengkap }}</span>
                            <svg class="ml-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                            <div class="py-1">
                                @if (auth()->user()->role === 'Admin')
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                @elseif(auth()->user()->role === 'Ketua_Bidang_Investigasi')
                                    <a href="{{ route('ketua_bidang.dashboard') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                @elseif(auth()->user()->role === 'Pegawai')
                                    <a href="{{ route('pegawai.dashboard') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                @elseif(auth()->user()->role === 'Warga')
                                    <a href="{{ route('warga.dashboard') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                @endif

                                @if (auth()->user()->role === 'Warga')
                                    <a href=""
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                @endif

                                <div class="border-t border-gray-100"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    class="text-gray-500 hover:text-gray-700 focus:outline-none focus:text-gray-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation -->
    <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95" class="md:hidden bg-white border-t border-gray-200">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ url('/') }}"
                class="block px-3 py-2 text-base font-medium text-gray-500 hover:text-primary-600 hover:bg-gray-50 rounded-md">
                Beranda
            </a>
            <a href="#tentang"
                class="block px-3 py-2 text-base font-medium text-gray-500 hover:text-primary-600 hover:bg-gray-50 rounded-md">
                Tentang
            </a>
            <a href="#layanan"
                class="block px-3 py-2 text-base font-medium text-gray-500 hover:text-primary-600 hover:bg-gray-50 rounded-md">
                Layanan
            </a>
            <a href="#kontak"
                class="block px-3 py-2 text-base font-medium text-gray-500 hover:text-primary-600 hover:bg-gray-50 rounded-md">
                Kontak
            </a>
        </div>

        @guest
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="px-2 space-y-1">
                    <a href="{{ route('login') }}"
                        class="block px-3 py-2 text-base font-medium text-gray-500 hover:text-primary-600 hover:bg-gray-50 rounded-md">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                        class="block px-3 py-2 text-base font-medium bg-primary-600 text-white rounded-md">
                        Daftar
                    </a>
                </div>
            </div>
        @else
            <div class="pt-4 pb-3 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div class="h-10 w-10 rounded-full bg-primary-600 flex items-center justify-center">
                        <span class="text-sm font-medium text-white">
                            {{ substr(auth()->user()->nama_lengkap, 0, 1) }}
                        </span>
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800">{{ auth()->user()->nama_lengkap }}</div>
                        <div class="text-sm font-medium text-gray-500">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    @if (auth()->user()->role === 'Admin')
                        <a href="{{ route('admin.dashboard') }}"
                            class="block px-3 py-2 text-base font-medium text-gray-500 hover:text-primary-600 hover:bg-gray-50 rounded-md">Dashboard</a>
                    @elseif(auth()->user()->role === 'Ketua_Bidang_Investigasi')
                        <a href="{{ route('ketua_bidang.dashboard') }}"
                            class="block px-3 py-2 text-base font-medium text-gray-500 hover:text-primary-600 hover:bg-gray-50 rounded-md">Dashboard</a>
                    @elseif(auth()->user()->role === 'Pegawai')
                        <a href="{{ route('pegawai.dashboard') }}"
                            class="block px-3 py-2 text-base font-medium text-gray-500 hover:text-primary-600 hover:bg-gray-50 rounded-md">Dashboard</a>
                    @elseif(auth()->user()->role === 'Warga')
                        <a href=""
                            class="block px-3 py-2 text-base font-medium text-gray-500 hover:text-primary-600 hover:bg-gray-50 rounded-md">Dashboard</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-3 py-2 text-base font-medium text-gray-500 hover:text-primary-600 hover:bg-gray-50 rounded-md">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        @endguest
    </div>
</nav>
