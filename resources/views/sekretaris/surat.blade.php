@extends('layouts.dashboard')

@section('title', 'Surat Tugas')

@section('content')
    <div class="space-y-6">
        <!-- Header Actions -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Surat Tugas</h1>
                <p class="text-gray-600">Kelola dan monitoring surat tugas investigasi</p>
            </div>
            <button id="openCreateSuratModal"
                class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                + Buat Surat Tugas
            </button>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- (4 Kartu Statistik Seperti Sebelumnya) -->
            <!-- ... kode yang sudah kamu tulis di atas ... -->
        </div>

        <!-- Filter & Search -->
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" placeholder="Cari nomor surat atau tim..."
                        class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <select class="border-gray-300 rounded-md shadow-sm">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="pending">Pending Approval</option>
                    <option value="approved">Disetujui</option>
                    <option value="active">Aktif</option>
                    <option value="completed">Selesai</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
                <select class="border-gray-300 rounded-md shadow-sm">
                    <option value="">Semua Prioritas</option>
                    <option value="urgent">Urgent</option>
                    <option value="high">Tinggi</option>
                    <option value="medium">Sedang</option>
                    <option value="low">Rendah</option>
                </select>
                <input type="date" class="border-gray-300 rounded-md shadow-sm">
            </div>
        </div>

        <!-- Surat Tugas Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Surat Tugas</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <!-- Header Table -->
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No.
                                Surat & Tim</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul
                                Kasus</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Prioritas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- ... baris 1 - 4 ... -->

                        <!-- Baris 5 (Lengkap) -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div>
                                    <div class="text-sm font-medium text-gray-900">ST/2024/005</div>
                                    <div class="text-sm text-gray-500">Tim Beta</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">Review Sistem Informasi Pelayanan</div>
                                <div class="text-sm text-gray-500">Diskominfo</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Sedang
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">05 Feb 2024</div>
                                <div class="text-sm text-gray-500">Deadline: 05 Mar 2024</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Aktif
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: 60%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600">60%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button class="text-blue-600 hover:text-blue-900">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button class="text-gray-600 hover:text-gray-900">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button class="text-green-600 hover:text-green-900">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="createSuratModal" class="fixed inset-0 z-50 hidden">
        <div id="createSuratBackdrop" class="absolute inset-0 bg-black/50"></div>
        <div class="absolute inset-0 flex items-start md:items-center justify-center p-4 md:p-6">
            <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Buat Surat Tugas</h3>
                        <p class="text-sm text-gray-500">Lengkapi sesuai format surat dinas</p>
                    </div>
                    <button id="closeCreateSuratModal" class="p-2 rounded-full hover:bg-gray-100 text-gray-500"
                        aria-label="Tutup">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body / Form --}}
                <form action="{{ route('ketua_bidang.surat.store') }}" method="POST" class="px-6 py-5 space-y-6">
                    @csrf

                    {{-- Baris 1: Nomor, Perihal --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Surat</label>
                            <input type="text" name="nomor_surat" required
                                class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="700.1/62/Inspektorat" value="{{ old('nomor_surat') }}">
                            @error('nomor_surat')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Perihal</label>
                            <input type="text" name="perihal" required
                                class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="Audit Investigasi Dana Kelurahan" value="{{ old('perihal') }}">
                        </div>
                    </div>

                    {{-- Baris 2: Tim, Laporan, Pembuat --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tim Investigasi</label>
                            <select name="tim_id" class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                <option value="" selected>— Pilih Tim —</option>
                                @foreach ($timList ?? [] as $t)
                                    <option value="{{ $t->tim_id }}">Tim #{{ $t->tim_id }} — Ketua:
                                        {{ optional($t->ketua)->nama_lengkap ?? '-' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Laporan</label>
                            <select name="laporan_id" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                <option value="" disabled selected>— Pilih Laporan —</option>
                                @foreach ($laporanList ?? [] as $l)
                                    <option value="{{ $l->laporan_id }}">#{{ $l->laporan_id }} —
                                        {{ \Illuminate\Support\Str::limit($l->judul_laporan, 40) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dibuat Oleh</label>
                            <select name="dibuat_oleh" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                <option value="" disabled selected>— Pilih Pejabat —</option>
                                @foreach ($userList ?? [] as $u)
                                    <option value="{{ $u->user_id }}">{{ $u->nama_lengkap }}
                                        ({{ $u->jabatan ?? $u->role }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Dasar (repeater) --}}
                    <div>
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-medium text-gray-700">Dasar</label>
                            <button type="button" onclick="addDasar()"
                                class="px-3 py-1.5 rounded border hover:bg-gray-50">Tambah</button>
                        </div>
                        <div id="dasarWrap" class="mt-2 space-y-2">
                            <input name="dasar[]" class="w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="Peraturan Pemerintah No. 12 Tahun 2017 ...">
                        </div>
                    </div>

                    {{-- Menugaskan (anggota & jabatan) --}}
                    <div>
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-medium text-gray-700">Menugaskan</label>
                            <button type="button" onclick="addAnggota()"
                                class="px-3 py-1.5 rounded border hover:bg-gray-50">Tambah Anggota</button>
                        </div>
                        <div id="anggotaWrap" class="mt-2 space-y-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 anggota-row">
                                <input name="anggota[nama][]" class="rounded-md border-gray-300 shadow-sm"
                                    placeholder="Nama & Gelar (mis. Drs. MUSTARI KADIR, M.Si.)">
                                <div class="flex gap-2">
                                    <input name="anggota[jabatan][]" class="flex-1 rounded-md border-gray-300 shadow-sm"
                                        placeholder="Penanggung jawab / Ketua Tim / Anggota">
                                    <button type="button"
                                        class="px-2.5 py-1.5 rounded border text-red-600 hover:bg-red-50"
                                        onclick="this.closest('.anggota-row').remove()">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Untuk (poin tugas) --}}
                    <div>
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-medium text-gray-700">Untuk</label>
                            <button type="button" onclick="addUntuk()"
                                class="px-3 py-1.5 rounded border hover:bg-gray-50">Tambah Poin</button>
                        </div>
                        <div id="untukWrap" class="mt-2 space-y-2">
                            <input name="untuk[]" class="w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="Melakukan Audit Investigasi di ...">
                        </div>
                    </div>

                    {{-- Tanggal pelaksanaan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
                            <input type="datetime-local" name="tanggal_mulai"
                                class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('tanggal_mulai') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                            <input type="datetime-local" name="tanggal_selesai"
                                class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('tanggal_selesai') }}">
                        </div>
                    </div>

                    {{-- Metadata surat & pejabat TTD --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tempat Dikeluarkan</label>
                            <input type="text" name="kota_terbit"
                                class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="Pangkajene Sidenreng" value="{{ old('kota_terbit') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal Surat</label>
                            <input type="date" name="tanggal_surat" required
                                class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('tanggal_surat') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Lokasi/Kecamatan (opsional)</label>
                            <input type="text" name="lokasi" class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="Watang Sidenreng" value="{{ old('lokasi') }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jabatan Penandatangan</label>
                            <input type="text" name="jabatan_ttd"
                                class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="INSPEKTUR DAERAH KAB. SIDENRENG RAPPANG" value="{{ old('jabatan_ttd') }}">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Penandatangan</label>
                                <input type="text" name="nama_ttd"
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                    placeholder="Drs. MUSTARI KADIR, M.Si." value="{{ old('nama_ttd') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pangkat</label>
                                <input type="text" name="pangkat_ttd"
                                    class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                    placeholder="Pembina Utama Muda" value="{{ old('pangkat_ttd') }}">
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">NIP</label>
                            <input type="text" name="nip_ttd" class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="19680119 199112 1 002" value="{{ old('nip_ttd') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status Surat</label>
                            <select name="status_surat" class="mt-1 w-full rounded-md border-gray-300 shadow-sm" required>
                                @foreach (['Draft', 'Diterbitkan', 'Dalam_Pelaksanaan', 'Selesai'] as $st)
                                    <option value="{{ $st }}"
                                        {{ old('status_surat') === $st ? 'selected' : '' }}>
                                        {{ $st }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Tembusan (repeater) --}}
                    <div>
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-medium text-gray-700">Tembusan</label>
                            <button type="button" onclick="addTembusan()"
                                class="px-3 py-1.5 rounded border hover:bg-gray-50">Tambah</button>
                        </div>
                        <div id="tembusanWrap" class="mt-2 space-y-2">
                            <input name="tembusan[]" class="w-full rounded-md border-gray-300 shadow-sm"
                                placeholder="Bupati Sidenreng Rappang">
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-200">
                        <button type="button" id="cancelCreateSurat"
                            class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-lg bg-primary-600 text-white hover:bg-primary-700">Simpan
                            Surat</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        (function() {
            const modal = document.getElementById('createSuratModal');
            const openBtn = document.getElementById('openCreateSuratModal');
            const closeBtn = document.getElementById('closeCreateSuratModal');
            const cancelBtn = document.getElementById('cancelCreateSurat');
            const backdrop = document.getElementById('createSuratBackdrop');
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

        function addInput(wrapId, name, placeholder) {
            const wrap = document.getElementById(wrapId);
            const div = document.createElement('div');
            div.className = 'flex gap-2';
            div.innerHTML = `
        <input name="${name}" class="flex-1 rounded-md border-gray-300 shadow-sm" placeholder="${placeholder}">
        <button type="button" class="px-2.5 py-1.5 rounded border text-red-600 hover:bg-red-50" onclick="this.parentElement.remove()">Hapus</button>
    `;
            wrap.appendChild(div);
        }

        function addDasar() {
            addInput('dasarWrap', 'dasar[]', 'Tambahkan dasar lain...');
        }

        function addUntuk() {
            addInput('untukWrap', 'untuk[]', 'Poin tugas...');
        }

        function addTembusan() {
            addInput('tembusanWrap', 'tembusan[]', 'Pihak tembusan...');
        }

        function addAnggota() {
            const wrap = document.getElementById('anggotaWrap');
            const row = document.createElement('div');
            row.className = 'grid grid-cols-1 md:grid-cols-2 gap-2 anggota-row';
            row.innerHTML = `
        <input name="anggota[nama][]" class="rounded-md border-gray-300 shadow-sm" placeholder="Nama & Gelar">
        <div class="flex gap-2">
            <input name="anggota[jabatan][]" class="flex-1 rounded-md border-gray-300 shadow-sm" placeholder="Jabatan/Peran">
            <button type="button" class="px-2.5 py-1.5 rounded border text-red-600 hover:bg-red-50" onclick="this.closest('.anggota-row').remove()">Hapus</button>
        </div>
    `;
            wrap.appendChild(row);
        }
    </script>
@endpush
