@extends('layouts.dashboard')

@section('title', 'Kelola User')

@section('content')
    <div class="space-y-6">
        {{-- Success/Error Messages --}}
        @if (session('success'))
            <div class="rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @php
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

        <!-- Kelola User (Table + Modal Create) -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <!-- Header -->
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-800">Kelola User</h3>

                {{-- Tombol Buat User --}}
                <button id="openCreateUserModal" type="button"
                    class="inline-flex items-center gap-1 text-sm px-3 py-1.5 rounded-lg bg-green-600 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-green-500">
                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                    </svg>
                    <span>Tambah User</span>
                </button>
            </div>

            @php
                $roles = ['Admin', 'Pegawai', 'Warga', 'Ketua_Bidang_Investigasi'];
                $currentStatus = request('status');
                $currentRole = request('role');
                $q = request('q');
            @endphp

            <!-- Filter -->
            <div class="flex px-4 pt-3">
                <div class="flex flex-wrap items-center gap-2 w-full justify-end">
                    <form method="GET" action="{{ route('admin.users') }}" class="flex gap-2 items-center">
                        <input type="hidden" name="status" value="{{ $currentStatus }}">
                        <select name="role" class="border-gray-300 rounded-md shadow-sm text-sm">
                            <option value="">Semua Role</option>
                            @foreach ($roles as $r)
                                <option value="{{ $r }}" {{ $currentRole === $r ? 'selected' : '' }}>
                                    {{ str_replace('_', ' ', $r) }}
                                </option>
                            @endforeach
                        </select>
                        <input name="q" value="{{ $q }}" placeholder="Cari nama / email / NIP"
                            class="border-gray-300 rounded-md shadow-sm text-sm" />
                        <button class="px-3 py-1.5 rounded-md bg-gray-800 text-white text-sm hover:bg-gray-900">
                            Filter
                        </button>
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
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div>{{ $user->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $user->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-6 py-4 text-right text-sm">
                                    <div class="inline-flex items-center gap-2">
                                        <button type="button" onclick="" class="text-gray-600 hover:text-gray-800">
                                            Detail
                                        </button>
                                        <button type="button"
                                            onclick="openEditModal({{ $user->user_id }}, '{{ addslashes($user->nik) }}', '{{ addslashes($user->nama_lengkap) }}', '{{ addslashes($user->username) }}', '{{ addslashes($user->email) }}', '{{ $user->role }}', '{{ addslashes($user->nip ?? '') }}', '{{ addslashes($user->jabatan ?? '') }}', '{{ addslashes($user->no_telepon ?? '') }}', '{{ addslashes($user->alamat ?? '') }}', {{ $user->is_active ? 'true' : 'false' }})"
                                            class="text-yellow-600 hover:text-yellow-800">
                                            Edit
                                        </button>

                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                onclick="return confirm('Yakin ingin menghapus user ini?')"
                                                class="text-red-600 hover:text-red-800">
                                                Hapus
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.users.toggle', $user) }}"
                                            class="inline">
                                            @csrf
                                            @method('PATCH')
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

        <!-- Modal Buat User -->
        <div id="createUserModal" class="fixed inset-0 z-50 hidden bg-black/50" style="display: none;">
            <div class="flex items-start md:items-center justify-center min-h-screen px-4 pt-10 pb-20">
                <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Buat User Baru</h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Lengkapi data user sesuai dengan informasi pegawai / warga.
                            </p>
                        </div>
                        <button id="closeCreateUserModal" type="button"
                            class="p-2 rounded-full hover:bg-gray-100 text-gray-500" aria-label="Tutup">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('admin.users.store') }}" method="POST" class="px-6 py-5 space-y-5">
                        @csrf

                        {{-- Error validation --}}
                        @if ($errors->any())
                            <div class="rounded-md bg-red-50 border border-red-200 px-3 py-2 text-xs text-red-700">
                                <div class="font-semibold mb-1">Terjadi kesalahan:</div>
                                <ul class="list-disc pl-4 space-y-0.5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIK <span
                                        class="text-red-500">*</span></label>
                                <input name="nik" required maxlength="16"
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="16 digit NIK" value="{{ old('nik') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Lengkap <span
                                        class="text-red-500">*</span></label>
                                <input name="nama_lengkap" required
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="Nama sesuai identitas" value="{{ old('nama_lengkap') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Username <span
                                        class="text-red-500">*</span></label>
                                <input name="username" required
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="username login" value="{{ old('username') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email <span
                                        class="text-red-500">*</span></label>
                                <input type="email" name="email" required
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="contoh: user@domain.com" value="{{ old('email') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Password <span
                                        class="text-red-500">*</span></label>
                                <input type="password" name="password" required minlength="8"
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="Minimal 8 karakter">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Konfirmasi Password <span
                                        class="text-red-500">*</span></label>
                                <input type="password" name="password_confirmation" required minlength="8"
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="Ulangi password">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Role <span
                                        class="text-red-500">*</span></label>
                                <select name="role" required
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm">
                                    <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Role
                                    </option>
                                    @foreach ($roles as $r)
                                        <option value="{{ $r }}" {{ old('role') === $r ? 'selected' : '' }}>
                                            {{ str_replace('_', ' ', $r) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIP</label>
                                <input name="nip" class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="Opsional" value="{{ old('nip') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                                <input name="jabatan" class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="Opsional" value="{{ old('jabatan') }}">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                                <input name="no_telepon" class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="contoh: 08xxxx" value="{{ old('no_telepon') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                <input name="alamat" class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="Alamat lengkap" value="{{ old('alamat') }}">
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-t pt-4 mt-2">
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300"
                                    {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                Aktifkan User
                            </label>
                            <div class="space-x-2">
                                <button type="button" id="cancelCreateUser"
                                    class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700 text-sm">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit User -->
        <div id="editUserModal" class="fixed inset-0 z-50 hidden bg-black/50" style="display: none;">
            <div class="flex items-start md:items-center justify-center min-h-screen px-4 pt-10 pb-20">
                <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Edit User</h3>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Perbarui data user sesuai kebutuhan.
                            </p>
                        </div>
                        <button id="closeEditUserModal" type="button"
                            class="p-2 rounded-full hover:bg-gray-100 text-gray-500" aria-label="Tutup">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <form id="editUserForm" method="POST" class="px-6 py-5 space-y-5">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIK</label>
                                <input id="edit_nik" name="nik" maxlength="16"
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="16 digit NIK">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Lengkap <span
                                        class="text-red-500">*</span></label>
                                <input id="edit_nama_lengkap" name="nama_lengkap" required
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="Nama sesuai identitas">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Username <span
                                        class="text-red-500">*</span></label>
                                <input id="edit_username" name="username" required
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="username login">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email <span
                                        class="text-red-500">*</span></label>
                                <input id="edit_email" type="email" name="email" required
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="contoh: user@domain.com">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Password Baru</label>
                                <input id="edit_password" type="password" name="password" minlength="8"
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="Kosongkan jika tidak diubah">
                                <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter, kosongkan jika tidak ingin
                                    mengubah password</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                <input id="edit_password_confirmation" type="password" name="password_confirmation"
                                    minlength="8" class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="Ulangi password baru">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Role <span
                                        class="text-red-500">*</span></label>
                                <select id="edit_role" name="role" required
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm">
                                    <option value="" disabled>Pilih Role</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Pegawai">Pegawai</option>
                                    <option value="Warga">Warga</option>
                                    <option value="Ketua_Bidang_Investigasi">Ketua Bidang Investigasi</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">NIP</label>
                                <input id="edit_nip" name="nip"
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="Opsional">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                                <input id="edit_jabatan" name="jabatan"
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="Opsional">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                                <input id="edit_no_telepon" name="no_telepon"
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="contoh: 08xxxx">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                                <input id="edit_alamat" name="alamat"
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm text-sm"
                                    placeholder="Alamat lengkap">
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-t pt-4 mt-2">
                            <label class="inline-flex items-center gap-2 text-sm text-gray-700">
                                <input type="hidden" name="is_active" value="0">
                                <input id="edit_is_active" type="checkbox" name="is_active" value="1"
                                    class="rounded border-gray-300">
                                Aktifkan User
                            </label>
                            <div class="space-x-2">
                                <button type="button" id="cancelEditUser"
                                    class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50 text-sm">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 text-sm">
                                    Update
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tips Section -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mt-2">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-green-600 mt-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-green-800">Tips Pengelolaan User</h4>
                    <ul class="list-disc pl-4 text-sm text-green-700 mt-1 space-y-1">
                        <li>Pastikan role sesuai dengan tugas dan wewenang.</li>
                        <li>Gunakan email yang aktif untuk keperluan notifikasi.</li>
                        <li>Nonaktifkan user yang sudah tidak bertugas.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Create Modal Elements
            const createModal = document.getElementById('createUserModal');
            const openCreateBtn = document.getElementById('openCreateUserModal');
            const closeCreateBtn = document.getElementById('closeCreateUserModal');
            const cancelCreateBtn = document.getElementById('cancelCreateUser');

            // Edit Modal Elements
            const editModal = document.getElementById('editUserModal');
            const closeEditBtn = document.getElementById('closeEditUserModal');
            const cancelEditBtn = document.getElementById('cancelEditUser');
            const editForm = document.getElementById('editUserForm');

            // Create Modal Functions
            function openCreateModal() {
                if (!createModal) return;
                createModal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            }

            function closeCreateModal() {
                if (!createModal) return;
                createModal.style.display = 'none';
                document.body.style.overflow = '';
            }

            if (openCreateBtn) {
                openCreateBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    openCreateModal();
                });
            }

            if (closeCreateBtn) {
                closeCreateBtn.addEventListener('click', closeCreateModal);
            }

            if (cancelCreateBtn) {
                cancelCreateBtn.addEventListener('click', closeCreateModal);
            }

            if (createModal) {
                createModal.addEventListener('click', function(e) {
                    if (e.target === createModal) {
                        closeCreateModal();
                    }
                });
            }

            // Edit Modal Functions
            function closeEditModal() {
                if (!editModal) return;
                editModal.style.display = 'none';
                document.body.style.overflow = '';
            }

            if (closeEditBtn) {
                closeEditBtn.addEventListener('click', closeEditModal);
            }

            if (cancelEditBtn) {
                cancelEditBtn.addEventListener('click', closeEditModal);
            }

            if (editModal) {
                editModal.addEventListener('click', function(e) {
                    if (e.target === editModal) {
                        closeEditModal();
                    }
                });
            }

            // Close on Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (createModal && createModal.style.display === 'block') {
                        closeCreateModal();
                    }
                    if (editModal && editModal.style.display === 'block') {
                        closeEditModal();
                    }
                }
            });

            // Auto-open create modal if there are validation errors
            @if ($errors->any())
                openCreateModal();
            @endif

            // Fungsi global untuk membuka modal edit dan isi data
            window.openEditModal = function(
                id,
                nik,
                nama_lengkap,
                username,
                email,
                role,
                nip,
                jabatan,
                no_telepon,
                alamat,
                is_active
            ) {
                if (!editModal || !editForm) return;

                // Set action form update (ganti :id dengan id sebenarnya)
                let actionUrl = "{{ route('admin.users.update', ['user' => ':id']) }}";
                actionUrl = actionUrl.replace(':id', id);
                editForm.action = actionUrl;

                // Isi field
                document.getElementById('edit_nik').value = nik || '';
                document.getElementById('edit_nama_lengkap').value = nama_lengkap || '';
                document.getElementById('edit_username').value = username || '';
                document.getElementById('edit_email').value = email || '';
                document.getElementById('edit_role').value = role || '';
                document.getElementById('edit_nip').value = nip || '';
                document.getElementById('edit_jabatan').value = jabatan || '';
                document.getElementById('edit_no_telepon').value = no_telepon || '';
                document.getElementById('edit_alamat').value = alamat || '';

                const isActiveCheckbox = document.getElementById('edit_is_active');
                if (isActiveCheckbox) {
                    isActiveCheckbox.checked = !!is_active;
                }

                // Kosongkan password
                const editPassword = document.getElementById('edit_password');
                const editPasswordConf = document.getElementById('edit_password_confirmation');
                if (editPassword) editPassword.value = '';
                if (editPasswordConf) editPasswordConf.value = '';

                // Tampilkan modal
                editModal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            };
        });
    </script>
@endsection
