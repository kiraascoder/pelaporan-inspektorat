@extends('layouts.dashboard')

@section('title', 'Kelola User')

@section('content')
    <div class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @php
                $colors = [
                    'bg-blue-100 text-blue-800',
                    'bg-yellow-100 text-yellow-800',
                    'bg-purple-100 text-purple-800',
                    'bg-green-100 text-green-800',
                ];
                $titles = ['Total Users', 'User Aktif', 'User Non Aktif'];
                $values = [$stats['total_user'], $stats['user_aktif'], $stats['user_nonaktif']];
            @endphp

            @foreach ($titles as $index => $title)
                <div class="p-4 rounded-lg shadow bg-white border border-gray-200 h-full flex flex-col justify-between">
                    <div class="text-sm font-medium text-gray-700">{{ $title }}</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ $values[$index] }}</div>
                </div>
            @endforeach
        </div>

        <!-- Recent Reports -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-800">Kelola User</h3>
                <a href="#" class="text-sm text-green-600 hover:underline">Buat User</a>
            </div>

            <div class="p-4">
                @if ($users->count() > 0)
                    <div class="space-y-4">
                        @foreach ($users as $user)
                            <div
                                class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm flex justify-between items-start hover:shadow-md transition">
                                <div class="flex-1">
                                    <h4 class="text-base font-semibold text-gray-800">{{ $user->nama_lengkap }}</h4>
                                    <p class="text-sm text-gray-500">{{ $user->nip ?? '-' }}</p>

                                    <div class="mt-1 space-y-1 text-xs text-gray-600">
                                        <p><span class="font-medium">Jabatan:</span> {{ $user->jabatan ?? '-' }}</p>
                                        <p><span class="font-medium">Role:</span> {{ ucfirst($user->role) }}</p>
                                        <p><span class="font-medium">Dibuat:</span>
                                            {{ $user->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>

                                <div class="ml-4 text-right space-y-2">
                                    <span
                                        class="inline-block text-xs px-3 py-1 rounded-full 
                                {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>

                                    <div class="mt-2 flex space-x-2 justify-end">
                                        <a href="#" class="text-sm text-yellow-600 hover:underline">Edit</a>
                                        <button class="text-sm text-red-600 hover:underline">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500 text-sm">
                        Belum ada user
                    </div>
                @endif
            </div>
        </div>


        <!-- Tips Section -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-green-600 mt-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-green-800">Tips Laporan Efektif</h4>
                    <ul class="list-disc pl-4 text-sm text-green-700 mt-1 space-y-1">
                        <li>Detail kejadian secara lengkap</li>
                        <li>Tambahkan bukti jika tersedia</li>
                        <li>Tentukan waktu & lokasi</li>
                        <li>Gunakan bahasa sopan dan jelas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
