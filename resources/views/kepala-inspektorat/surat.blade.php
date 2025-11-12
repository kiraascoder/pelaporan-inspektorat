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
            <button id="openCreateSuratModal"
                class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                + Buat Surat Tugas
            </button>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Daftar Surat Tugas</h3>
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
                                <td class="px-6 py-3 font-medium text-gray-900">{{ $s->nomor_surat ?? '-' }}</td>
                                <td class="px-6 py-3">
                                    {{ $s->laporanPengaduan->judul ?? ($s->laporanPengaduan->permasalahan ?? '-') }}</td>
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
                                    <a href="{{ route('ketua_bidang.surat.show', $s->pengajuan_surat_id) }}"
                                        class="text-blue-600 hover:text-blue-800">Lihat</a>
                                    <a href="{{ route('ketua_bidang.surat.edit', $s->pengajuan_surat_id) }}"
                                        class="text-gray-600 hover:text-gray-900">Edit</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada surat tugas.</td>
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
                        <h3 class="text-lg font-semibold text-gray-900">Buat Surat Tugas</h3>
                        <p class="text-sm text-gray-500">Isi data sesuai format surat dinas</p>
                    </div>
                    <button id="closeCreateSuratModal" class="p-2 rounded-full hover:bg-gray-100 text-gray-500">✕</button>
                </div>

                <form action="{{ route('ketua_bidang.surat.store') }}" method="POST" class="px-6 py-5 space-y-5">
                    @csrf

                    {{-- Nomor & Laporan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nomor Surat</label>
                            <input type="text" name="nomor_surat" required class="w-full rounded-md border-gray-300"
                                placeholder="700.1/62/Inspektorat" value="{{ old('nomor_surat') }}">
                            @error('nomor_surat')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
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
                    </div>

                    {{-- Penandatangan --}}
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

                    {{-- ==== ANGGOTA TIM (MULTI SELECT + ROLE) ==== --}}
                    <div>
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-medium text-gray-700">Anggota Tim yang Ditugaskan</label>
                            <button type="button" id="btnAddAnggota"
                                class="px-3 py-1.5 rounded border hover:bg-gray-50">Tambah Anggota</button>
                        </div>

                        <div id="anggotaWrap" class="mt-2 space-y-2">
                            {{-- Row Template (pertama) --}}
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 anggota-row">
                                <div class="md:col-span-6">
                                    <select name="anggota[pegawai_id][]" class="w-full rounded-md border-gray-300" required>
                                        <option value="">— Pilih Pegawai —</option>
                                        @foreach ($pegawaiList ?? [] as $p)
                                            <option value="{{ $p->user_id }}">{{ $p->nama_lengkap }} —
                                                {{ $p->jabatan ?? $p->role }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-4">
                                    <select name="anggota[role][]" class="w-full rounded-md border-gray-300" required>
                                        @php
                                            $roles = [
                                                'Penanggung Jawab',
                                                'Wakil Penanggung Jawab',
                                                'Pengendali Teknis',
                                                'Ketua Tim',
                                                'Anggota',
                                            ];
                                        @endphp
                                        <option value="">— Pilih Role —</option>
                                        @foreach ($roles as $r)
                                            <option value="{{ $r }}">{{ $r }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-2 flex items-center">
                                    <button type="button"
                                        class="btn-remove-anggota w-full md:w-auto px-3 py-2 rounded border text-red-600 hover:bg-red-50">
                                        Hapus
                                    </button>
                                </div>
                                <div class="md:col-span-12">
                                    <input type="text" name="anggota[deskripsi][]"
                                        class="w-full rounded-md border-gray-300" placeholder="Deskripsi tugas (opsional)">
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500 mt-1">Tips: hindari duplikasi pegawai pada tim yang sama.</p>
                        @error('anggota.pegawai_id.*')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                        @error('anggota.role.*')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Deskripsi Umum (jadi poin “Untuk” di PDF; 1 baris = 1 poin) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi Umum / “Untuk” (satu baris = satu
                            poin)</label>
                        <textarea name="deskripsi_umum" rows="3" class="w-full rounded-md border-gray-300"
                            placeholder="Contoh:
Melakukan audit investigasi dugaan pungutan liar ...
Pelaksanaan tugas selama 15 (lima belas) hari kerja mulai tanggal ...
Hasil pelaksanaan tugas dilaporkan kepada Bupati melalui Inspektur ...">{{ old('deskripsi_umum') }}</textarea>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="w-full rounded-md border-gray-300">
                            @foreach (['Pending', 'Dibuat', 'Selesai'] as $st)
                                <option value="{{ $st }}" @selected(old('status') == $st)>{{ $st }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol --}}
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" id="cancelCreateSurat"
                            class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-lg bg-primary-600 text-white hover:bg-primary-700">
                            Simpan Surat
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

            // Repeater Anggota
            const wrap = document.getElementById('anggotaWrap');
            const btnAdd = document.getElementById('btnAddAnggota');

            btnAdd?.addEventListener('click', () => {
                const row = document.createElement('div');
                row.className = 'grid grid-cols-1 md:grid-cols-12 gap-2 anggota-row';
                row.innerHTML = `
            <div class="md:col-span-6">
                <select name="anggota[pegawai_id][]" class="w-full rounded-md border-gray-300" required>
                    <option value="">— Pilih Pegawai —</option>
                    @foreach ($pegawaiList ?? [] as $p)
                        <option value="{{ $p->user_id }}">{{ $p->nama_lengkap }} — {{ $p->jabatan ?? $p->role }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-4">
                <select name="anggota[role][]" class="w-full rounded-md border-gray-300" required>
                    <option value="">— Pilih Role —</option>
                    @foreach ($roles as $r)
                        <option value="{{ $r }}">{{ $r }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 flex items-center">
                <button type="button" class="btn-remove-anggota w-full md:w-auto px-3 py-2 rounded border text-red-600 hover:bg-red-50">Hapus</button>
            </div>
            <div class="md:col-span-12">
                <input type="text" name="anggota[deskripsi][]" class="w-full rounded-md border-gray-300" placeholder="Deskripsi tugas (opsional)">
            </div>
        `;
                wrap.appendChild(row);
            });

            // Delegasi: hapus baris anggota
            wrap?.addEventListener('click', (e) => {
                if (e.target.classList.contains('btn-remove-anggota')) {
                    e.preventDefault();
                    const row = e.target.closest('.anggota-row');
                    if (row && wrap.children.length > 1) row.remove();
                }
            });
        })();
    </script>
@endpush
