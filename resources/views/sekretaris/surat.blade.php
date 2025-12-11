@extends('layouts.dashboard')

@section('title', 'Surat Tugas')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Surat Tugas</h1>
                <p class="text-gray-600">Kelola dan monitoring surat tugas investigasi</p>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Pengajuan Surat Tugas</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor Surat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Laporan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penandatangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dibuat</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($suratList ?? [] as $s)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 font-medium text-gray-900">{{ $s->nomor_surat ?? 'Belum Tersedia' }}
                                </td>
                                <td class="px-6 py-3">
                                    {{ $s->laporan->judul ?? ($s->laporan->permasalahan ?? '') }}
                                </td>
                                <td class="px-6 py-3">{{ $s->penandatangan->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-3">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-medium 
                                    @if ($s->status == 'Pending') bg-yellow-100 text-yellow-800 
                                    @elseif($s->status == 'Dibuat') bg-blue-100 text-blue-800
                                    @elseif($s->status == 'Selesai') bg-green-100 text-green-800 @endif">
                                        {{ $s->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-500">
                                    {{ $s->created_at?->format('d M Y') ?? '-' }}
                                </td>
                                <td class="px-6 py-3 text-right space-x-2">
                                    <a href="{{ route('sekretaris-surat.show', $s->pengajuan_surat_id) }}"
                                        class="text-blue-600 hover:text-blue-800">Lihat</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada pengajuan surat
                                    tugas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ========================= MODAL ========================= --}}
    <div id="createSuratModal" class="fixed inset-0 z-50 hidden">
        <div id="createSuratBackdrop" class="absolute inset-0 bg-black/50"></div>
        <div class="absolute inset-0 flex items-start justify-center p-6">
            <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Buat Pengajuan Surat Tugas</h3>
                        <p class="text-sm text-gray-500">Isi data pengajuan untuk pembuatan surat tugas investigasi</p>
                    </div>
                    <button id="closeCreateSuratModal" class="p-2 rounded-full hover:bg-gray-100 text-gray-500">✕</button>
                </div>

                {{-- SESUAI CONTROLLER: store() hanya butuh laporan_id, penandatangan_id, nama_ditugaskan[], deskripsi_umum --}}
                <form action="{{ route('pengajuan-surat.store') }}" method="POST" class="px-6 py-5 space-y-5">
                    @csrf

                    {{-- Laporan & Penandatangan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Laporan Pengaduan</label>
                            <select name="laporan_id" required class="w-full rounded-md border-gray-300">
                                <option value="">— Pilih Laporan Pengaduan —</option>
                                @foreach ($laporanList ?? [] as $l)
                                    <option value="{{ $l->laporan_id }}" @selected(old('laporan_id') == $l->laporan_id)>
                                        #{{ $l->laporan_id }} — {{ $l->judul ?? $l->permasalahan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('laporan_id')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Penandatangan Surat</label>
                            <select name="penandatangan_id" required class="w-full rounded-md border-gray-300">
                                <option value="">— Pilih Penandatangan —</option>
                                @foreach ($userList ?? [] as $u)
                                    <option value="{{ $u->user_id }}" @selected(old('penandatangan_id') == $u->user_id)>
                                        {{ $u->nama_lengkap }} — {{ $u->jabatan ?? $u->role }}
                                    </option>
                                @endforeach
                            </select>
                            @error('penandatangan_id')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- NAMA-NAMA YANG DITUGASKAN (JSON ARRAY OF OBJECTS) --}}
                    <div>
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-medium text-gray-700">
                                Nama yang Ditugaskan
                                <span class="text-xs text-gray-500">(setiap baris adalah 1 orang; pilih jabatan)</span>
                            </label>
                            <button type="button" id="btnAddNama"
                                class="px-3 py-1.5 rounded border hover:bg-gray-50 text-sm">
                                Tambah Nama
                            </button>
                        </div>

                        <div id="namaWrap" class="mt-2 space-y-2">
                            {{-- Row pertama --}}
                            <div class="grid grid-cols-12 gap-2 nama-row">
                                <div class="col-span-5">
                                    <input type="text" name="nama_ditugaskan[0][nama]"
                                        class="w-full rounded-md border-gray-300"
                                        placeholder="Contoh: Ahmad Zainuddin, S.STP."
                                        value="{{ old('nama_ditugaskan.0.nama') }}">
                                </div>

                                <div class="col-span-5">
                                    <select name="nama_ditugaskan[0][jabatan]" class="w-full rounded-md border-gray-300">
                                        <option value="">— Pilih Jabatan —</option>
                                        @foreach ($jabatanList as $jab)
                                            <option value="{{ $jab }}" @selected(old('nama_ditugaskan.0.jabatan') == $jab)>
                                                {{ $jab }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-span-2 flex items-center">
                                    <button type="button"
                                        class="btn-remove-nama w-full md:w-auto px-3 py-2 rounded border text-red-600 hover:bg-red-50">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 mt-1">
                            Nama & jabatan akan disimpan sebagai JSON array of objects dan dapat ditampilkan urut di surat
                            tugas.
                        </p>
                        @error('nama_ditugaskan')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        @error('nama_ditugaskan.*.nama')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        @error('nama_ditugaskan.*.jabatan')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi Umum --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Deskripsi Umum / “Untuk” (satu baris = satu poin)
                        </label>
                        <textarea name="deskripsi_umum" rows="3" class="w-full rounded-md border-gray-300"
                            placeholder="Contoh:
Melakukan audit investigasi dugaan pungutan liar ...
Pelaksanaan tugas selama 15 (lima belas) hari kerja mulai tanggal ...
Hasil pelaksanaan tugas dilaporkan kepada Bupati melalui Inspektur ...">{{ old('deskripsi_umum') }}</textarea>
                        @error('deskripsi_umum')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" id="cancelCreateSurat"
                            class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 rounded-lg bg-primary-600 text-white hover:bg-primary-700">
                            Simpan Pengajuan
                        </button>
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

            // Repeater Nama Ditugaskan (dengan jabatan)
            const wrap = document.getElementById('namaWrap');
            const btnAdd = document.getElementById('btnAddNama');

            // get jabatanList dari server (rendered via blade)
            const JB_OPTIONS = (() => {
                try {
                    return @json($jabatanList);
                } catch (err) {
                    return ['Inspektur', 'Auditor', 'Pengawas', 'Staf'];
                }
            })();

            function optionsHtml() {
                return JB_OPTIONS.map(o => `<option value="${o}">${o}</option>`).join('');
            }

            function nextIndex() {
                return wrap.querySelectorAll('.nama-row').length;
            }

            btnAdd?.addEventListener('click', () => {
                const idx = nextIndex();
                const row = document.createElement('div');
                row.className = 'grid grid-cols-12 gap-2 nama-row';
                row.innerHTML = `
                    <div class="col-span-5">
                        <input type="text" name="nama_ditugaskan[${idx}][nama]" class="w-full rounded-md border-gray-300"
                            placeholder="Nama lengkap pegawai yang ditugaskan">
                    </div>
                    <div class="col-span-5">
                        <select name="nama_ditugaskan[${idx}][jabatan]" class="w-full rounded-md border-gray-300">
                            <option value="">— Pilih Jabatan —</option>
                            ${optionsHtml()}
                        </select>
                    </div>
                    <div class="col-span-2 flex items-center">
                        <button type="button"
                            class="btn-remove-nama w-full md:w-auto px-3 py-2 rounded border text-red-600 hover:bg-red-50">
                            Hapus
                        </button>
                    </div>
                `;
                wrap.appendChild(row);
            });

            // Delegasi: hapus baris nama + reindex
            wrap?.addEventListener('click', (e) => {
                if (e.target.classList.contains('btn-remove-nama')) {
                    e.preventDefault();
                    const row = e.target.closest('.nama-row');
                    if (row && wrap.children.length > 1) row.remove();

                    // Re-index names after remove so keys 0..n-1 berurutan
                    const rows = wrap.querySelectorAll('.nama-row');
                    rows.forEach((r, i) => {
                        const inputNama = r.querySelector('input[type="text"]');
                        const selectJab = r.querySelector('select');
                        if (inputNama) inputNama.setAttribute('name', `nama_ditugaskan[${i}][nama]`);
                        if (selectJab) selectJab.setAttribute('name', `nama_ditugaskan[${i}][jabatan]`);
                    });
                }
            });
        })();
    </script>
@endpush
