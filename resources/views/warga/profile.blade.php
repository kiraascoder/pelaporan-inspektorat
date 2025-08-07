@extends('layouts.dashboard')

@section('title', 'Profile Saya')

@section('content')
    <div class="space-y-6">
        <!-- Profile Header Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-800">Informasi Profile</h3>
                <button onclick="toggleEditMode()" id="editBtn"
                    class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                    Edit Profile
                </button>
            </div>

            <div class="p-6">
                <!-- Profile Form -->
                <form action="{{ route('warga.profile.update') }}" method="POST" id="profileForm">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div class="space-y-4">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" value="{{ auth()->user()->nama_lengkap }}"
                                    class="profile-input w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                                    readonly>
                            </div>

                            <!-- Username -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                                <input type="text" value="{{ str_replace('_', ' ', auth()->user()->nik) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" value="{{ auth()->user()->email }}"
                                    class="profile-input w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                                    readonly>
                            </div>

                            <!-- No Telepon -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                                <input type="text" name="no_telepon" value="{{ auth()->user()->no_telepon }}"
                                    class="profile-input w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                                    readonly>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-4">
                            <!-- Role -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                <input type="text" value="{{ str_replace('_', ' ', auth()->user()->role) }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                            </div>

                            @if (!auth()->user()->isWarga())
                                <!-- NIP (untuk pegawai) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">NIP</label>
                                    <input type="text" name="nip" value="{{ auth()->user()->nip }}"
                                        class="profile-input w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                                        readonly>
                                </div>

                                <!-- Jabatan (untuk pegawai) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                                    <input type="text" name="jabatan" value="{{ auth()->user()->jabatan }}"
                                        class="profile-input w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50"
                                        readonly>
                                </div>
                            @endif

                            <!-- Status -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                    {{ auth()->user()->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ auth()->user()->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>

                            <!-- Member Since -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bergabung Sejak</label>
                                <input type="text" value="{{ auth()->user()->created_at->format('d M Y') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Alamat (Full Width) -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="alamat" rows="3"
                            class="profile-input w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50" readonly>{{ auth()->user()->alamat }}</textarea>
                    </div>

                    <!-- Action Buttons (Hidden by default) -->
                    <div id="actionButtons" class="mt-6 flex space-x-3 hidden">
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700 transition-colors">
                            Simpan Perubahan
                        </button>
                        <button type="button" onclick="cancelEdit()"
                            class="px-4 py-2 bg-gray-600 text-white text-sm rounded hover:bg-gray-700 transition-colors">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- <!-- Change Password Card -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="text-base font-semibold text-gray-800">Ubah Password</h3>
            </div>
            <div class="p-6">
                <form action="" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
                            <input type="password" name="current_password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <input type="password" name="password"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                required>
                        </div>
                    </div>

                    <div class="flex justify-start">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition-colors">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div> --}}
    </div>

    <script>
        function toggleEditMode() {
            const inputs = document.querySelectorAll('.profile-input');
            const editBtn = document.getElementById('editBtn');
            const actionButtons = document.getElementById('actionButtons');

            inputs.forEach(input => {
                if (input.readOnly) {
                    input.readOnly = false;
                    input.classList.remove('bg-gray-50');
                    input.classList.add('bg-white', 'focus:ring-2', 'focus:ring-blue-500', 'focus:border-blue-500');
                }
            });

            editBtn.style.display = 'none';
            actionButtons.classList.remove('hidden');
        }

        function cancelEdit() {
            const inputs = document.querySelectorAll('.profile-input');
            const editBtn = document.getElementById('editBtn');
            const actionButtons = document.getElementById('actionButtons');

            inputs.forEach(input => {
                input.readOnly = true;
                input.classList.add('bg-gray-50');
                input.classList.remove('bg-white', 'focus:ring-2', 'focus:ring-blue-500', 'focus:border-blue-500');
            });

            editBtn.style.display = 'inline-block';
            actionButtons.classList.add('hidden');

            // Reset form to original values
            document.getElementById('profileForm').reset();
        }
    </script>
@endsection
