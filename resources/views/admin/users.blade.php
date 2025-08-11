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
        <!-- Kelola User (Table + Navigation + Modal Create) -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-800">Kelola User</h3>
                <button id="openCreateUserModal"
                    class="text-sm px-3 py-1.5 rounded-lg bg-green-600 text-white hover:bg-green-700">
                    + Buat User
                </button>
            </div>

            @php
                $tabs = [
                    ['label' => 'Semua', 'val' => null],
                    ['label' => 'Aktif', 'val' => '1'],
                    ['label' => 'Nonaktif', 'val' => '0'],
                ];
                $roles = ['Admin', 'Pegawai', 'Warga', 'Ketua_Bidang_Investigasi'];
                $currentStatus = request('status');
                $currentRole = request('role');
                $q = request('q');
            @endphp
            
            <div class="flex px-4 pt-3 ">
                <div class="flex flex-wrap items-center gap-2">                                    
                    <form method="GET" action="{{ route('admin.users') }}" class="ml-auto flex gap-2 items-center">
                        <input type="hidden" name="status" value="{{ $currentStatus }}">
                        <select name="role" class="border-gray-300 rounded-md shadow-sm text-sm">
                            <option value="">Semua Role</option>
                            @foreach ($roles as $r)
                                <option value="{{ $r }}" {{ $currentRole === $r ? 'selected' : '' }}>
                                    {{ $r }}</option>
                            @endforeach
                        </select>
                        <input name="q" value="{{ $q }}" placeholder="Cari nama/email/NIP…"
                            class="border-gray-300 rounded-md shadow-sm text-sm" />
                        <button class="px-3 py-1.5 rounded-md bg-gray-800 text-white text-sm">Filter</button>
                    </form>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto mt-3">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Email / Username
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Jabatan / NIP</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Dibuat</th>
                            <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $user->nama_lengkap }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->alamat ?: '—' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                    <div class="text-xs text-gray-500">{{ '@' . $user->username }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ str_replace('_', ' ', $user->role) }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div>{{ $user->jabatan ?: '—' }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->nip ?: '—' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div>{{ $user->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <div class="inline-flex items-center gap-2">
                                        <a href=""
                                            class="text-yellow-600 hover:text-yellow-800">Edit</a>

                                        <form method="POST" action=""
                                            onsubmit="return confirm('Hapus user {{ $user->nama_lengkap }}?')"
                                            class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                                        </form>

                                        {{-- Toggle aktif/nonaktif (opsional) --}}
                                        <form method="POST" action=""
                                            class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="text-gray-600 hover:text-gray-900"
                                                title="Ubah status">
                                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">
                                    Belum ada user.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>            
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $users->onEachSide(1)->links() }}
            </div>
        </div>

        {{-- Modal: Buat User --}}
        <div id="createUserModal" class="fixed inset-0 z-50 hidden">
            <div id="createUserBackdrop" class="absolute inset-0 bg-black/50"></div>
            <div class="absolute inset-0 flex items-start md:items-center justify-center p-4 md:p-6">
                <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Buat User</h3>
                        <button id="closeCreateUserModal" class="p-2 rounded-full hover:bg-gray-100 text-gray-500"
                            aria-label="Tutup">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form action="" method="POST" class="px-6 py-5 space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                <input name="nama_lengkap" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                    value="{{ old('nama_lengkap') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Username</label>
                                <input name="username" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                    value="{{ old('username') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" required
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm" value="{{ old('email') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Password</label>
                                <input type="password" name="password" required
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Role</label>
                                <select name="role" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                    <option value="" disabled selected>Pilih Role</option>
                                    @foreach ($roles as $r)
                                        <option value="{{ $r }}" {{ old('role') === $r ? 'selected' : '' }}>
                                            {{ $r }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIP</label>
                                <input name="nip" class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                    value="{{ old('nip') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                                <input name="jabatan" class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                    value="{{ old('jabatan') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                                <input name="no_telepon" class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                    value="{{ old('no_telepon') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIK (opsional)</label>
                                <input name="nik" maxlength="16"
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm" value="{{ old('nik') }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="alamat" rows="2" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">{{ old('alamat') }}</textarea>
                        </div>

                        <div class="flex items-center justify-between border-t pt-4">
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="checkbox" name="is_active" value="1" class="rounded"
                                    {{ old('is_active', 1) ? 'checked' : '' }}>
                                Aktifkan User
                            </label>
                            <div class="space-x-2">
                                <button type="button" id="cancelCreateUser"
                                    class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Batal</button>
                                <button type="submit"
                                    class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
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
    <div id="createUserModal" class="fixed inset-0 z-50 hidden">
        <div id="createUserBackdrop" class="absolute inset-0 bg-black/50"></div>
        <div class="absolute inset-0 flex items-start md:items-center justify-center p-4 md:p-6">
            <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Buat User</h3>
                    <button id="closeCreateUserModal" class="p-2 rounded-full hover:bg-gray-100 text-gray-500"
                        aria-label="Tutup">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form action="" method="POST" class="px-6 py-5 space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input name="nama_lengkap" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('nama_lengkap') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Username</label>
                            <input name="username" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('username') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" required
                                class="mt-1 w-full rounded-md border-gray-300 shadow-sm" value="{{ old('email') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" required
                                class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                <option value="" disabled selected>Pilih Role</option>
                                @foreach ($roles as $r)
                                    <option value="{{ $r }}" {{ old('role') === $r ? 'selected' : '' }}>
                                        {{ $r }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NIP</label>
                            <input name="nip" class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('nip') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                            <input name="jabatan" class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('jabatan') }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                            <input name="no_telepon" class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('no_telepon') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NIK (opsional)</label>
                            <input name="nik" maxlength="16" class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('nik') }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" rows="2" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">{{ old('alamat') }}</textarea>
                    </div>

                    <div class="flex items-center justify-between border-t pt-4">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                            <input type="checkbox" name="is_active" value="1" class="rounded"
                                {{ old('is_active', 1) ? 'checked' : '' }}>
                            Aktifkan User
                        </label>
                        <div class="space-x-2">
                            <button type="button" id="cancelCreateUser"
                                class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Batal</button>
                            <button type="submit"
                                class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('script')
    {{-- JS modal --}}
    <script>
        (function() {
            const modal = document.getElementById('createUserModal');
            const openBtn = document.getElementById('openCreateUserModal');
            const closeBtn = document.getElementById('closeCreateUserModal');
            const cancelBtn = document.getElementById('cancelCreateUser');
            const backdrop = document.getElementById('createUserBackdrop');
            const open = () => {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };
            const close = () => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };
            openBtn?.addEventListener('click', open);
            closeBtn?.addEventListener('click', close);
            cancelBtn?.addEventListener('click', close);
            backdrop?.addEventListener('click', close);
            window.addEventListener('keydown', e => {
                if (e.key === 'Escape') close();
            });
        })();
    </script>
@endpush
