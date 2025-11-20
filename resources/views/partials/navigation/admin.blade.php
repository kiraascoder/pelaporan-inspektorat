<ul class="space-y-2">
    <li>
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary-100 text-primary-700' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0a2 2 0 002-2h6l2 2h6a2 2 0 012 2v2" />
            </svg>
            Dashboard
        </a>
    </li>
    <li>
        <a href="{{ route('admin.users') }}"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.users') ? 'bg-primary-100 text-primary-700' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
            Kelola User
        </a>
    </li>
    <li>
        <a href="{{ route('admin.laporan') }}"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.laporan') ? 'bg-primary-100 text-primary-700' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Semua Laporan
        </a>
    </li>
    <li>
        <a href="{{ route('admin.tim') }}"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.tim') ? 'bg-primary-100 text-primary-700' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Tim Investigasi
        </a>
    </li>
    <li>
    <a href="{{ route('admin.reports') }}"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.reports') ? 'bg-primary-100 text-primary-700' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Laporan Pegawai
        </a>
    </li>
</ul>
