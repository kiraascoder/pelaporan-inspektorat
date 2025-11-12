<ul class="space-y-2">
    <li>
        <a href="{{ route('sekretaris.dashboard') }}"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('sekretaris.dashboard') ? 'bg-primary-100 text-primary-700' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2v0a2 2 0 002-2h6l2 2h6a2 2 0 012 2v2" />
            </svg>
            Dashboard
        </a>
    </li>
    <li>
        <a href="{{ route('sekretaris.laporan') }}"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('sekretaris.laporan') ? 'bg-primary-100 text-primary-700' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            Laporan Masuk
        </a>
    </li>
    <li>
        <a href="{{ route('sekretaris.tim') }}"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('sekretaris.tim') ? 'bg-primary-100 text-primary-700' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Tim Investigasi
        </a>
    </li>
    <li>
        <a href="{{ route('sekretaris.laporan_tugas') }}"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('sekretaris.laporan_tugas') ? 'bg-primary-100 text-primary-700' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Laporan Tugas sekretaris
        </a>
    </li>
    <li>
        <a href="{{ route('sekretaris.surat_tugas') }}"
            class="flex items-center px-4 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('sekretaris.surat_tugas') ? 'bg-primary-100 text-primary-700' : 'text-gray-600 hover:bg-gray-100' }}">
            <svg class="mr-3 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Surat Tugas
        </a>
    </li>
</ul>
