@extends('layouts.dashboard')

@section('title', 'Detail User')

@section('content')
    <div class="space-y-6">
        <!-- Breadcrumb + Back -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Detail User</h1>
                <p class="text-sm text-gray-500">
                    Informasi lengkap untuk user <span class="font-medium">{{ $user->nama_lengkap }}</span>.
                </p>
            </div>
            <a href="{{ route('admin.users') }}"
                class="inline-flex items-center px-3 py-1.5 rounded-lg border border-gray-300 text-sm text-gray-700 hover:bg-gray-50">
                ← Kembali ke daftar
            </a>
        </div>

        <!-- Card Utama -->
        <div class="bg-white rounded-xl shadow border border-gray-200 p-6 space-y-6">
            <!-- Header User -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-xl font-semibold text-blue-700">
                            {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-gray-900">
                            {{ $user->nama_lengkap }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ '@' . $user->username }}
                        </div>
                        <div class="mt-1 flex flex-wrap items-center gap-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ str_replace('_', ' ', $user->role) }}
                            </span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                </div>                
            </div>

            <!-- Grid Detail -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Identitas Akun -->
                <div class="space-y-3">
                    <h2 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Identitas Akun</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Email</span>
                            <span class="font-medium text-gray-900">{{ $user->email }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Username</span>
                            <span class="font-medium text-gray-900">{{ '@' . $user->username }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Role</span>
                            <span class="font-medium text-gray-900">
                                {{ str_replace('_', ' ', $user->role) }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Status</span>
                            <span class="font-medium {{ $user->is_active ? 'text-green-700' : 'text-red-700' }}">
                                {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Data Pegawai / Warga -->
                <div class="space-y-3">
                    <h2 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Data Pegawai / Warga</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">NIK</span>
                            <span class="font-medium text-gray-900">{{ $user->nik ?: '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">NIP</span>
                            <span class="font-medium text-gray-900">{{ $user->nip ?: '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Jabatan</span>
                            <span class="font-medium text-gray-900">{{ $user->jabatan ?: '—' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Kontak -->
                <div class="space-y-3">
                    <h2 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Kontak</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">No. Telepon</span>
                            <span class="font-medium text-gray-900">{{ $user->no_telepon ?: '—' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500 block mb-1">Alamat</span>
                            <p class="text-sm font-medium text-gray-900">
                                {{ $user->alamat ?: '—' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Waktu -->
                <div class="space-y-3">
                    <h2 class="text-sm font-semibold text-gray-800 uppercase tracking-wide">Waktu</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Dibuat</span>
                            <span class="font-medium text-gray-900">
                                {{ $user->created_at ? $user->created_at->format('d M Y H:i') : '—' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Terakhir Diupdate</span>
                            <span class="font-medium text-gray-900">
                                {{ $user->updated_at ? $user->updated_at->format('d M Y H:i') : '—' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aksi berbahaya -->
            <div class="pt-4 border-t border-gray-200">
                <h2 class="text-sm font-semibold text-red-700 mb-2">Aksi</h2>
                <div class="flex flex-wrap gap-3">
                    <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit"
                            class="px-3 py-1.5 rounded-lg border text-sm
                                   {{ $user->is_active
                                       ? 'border-yellow-500 text-yellow-700 hover:bg-yellow-50'
                                       : 'border-green-600 text-green-700 hover:bg-green-50' }}">
                            {{ $user->is_active ? 'Nonaktifkan User' : 'Aktifkan User' }}
                        </button>
                    </form>

                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-3 py-1.5 rounded-lg border border-red-500 text-sm text-red-700 hover:bg-red-50">
                            Hapus User
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
