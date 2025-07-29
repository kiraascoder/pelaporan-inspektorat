<ul class="space-y-2">
    <li>
        <a href=""
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('warga.dashboard') ? 'bg-primary-100 text-primary-700' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0a2 2 0 002-2h6l2 2h6a2 2 0 012 2v2" />
            </svg>
            Dashboard
        </a>
    </li>
    <li>
        <a href="{{ route('warga.laporan') }}"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('warga.laporan.*') ? 'bg-primary-100 text-primary-700' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Laporan Saya
        </a>
    </li>
    <li>
        <a href="{{ route('warga.profile') }}"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs(' ') ? 'bg-primary-100 text-primary-700' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            Profile
        </a>
    </li>
</ul>
