@extends('layouts.dashboard')

@section('title', 'Pengajuan Surat Tugas')

@section('content')
    <div class="space-y-6">

        {{-- Header + Tombol Kembali --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pengajuan Surat Tugas</h1>
                <p class="text-gray-600">Kelola dan monitoring Pengajuan Surat Tgas investigasi</p>
            </div>

        </div>

        {{-- Filter & Search --}}
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

        {{-- Table Pengajuan Surat --}}
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
                                <td class="px-6 py-4 font-medium text-gray-900">{{ $s->nomor_surat ?? 'Belum Ditentukan' }}
                                </td>
                                <td class="px-6 py-4">{{ $s->laporan?->judul ?? ($s->laporan?->permasalahan ?? '-') }}</td>
                                <td class="px-6 py-4">{{ $s->penandatangan?->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span
                                        class="px-2.5 py-1 rounded-full text-xs font-medium
                                    @if ($s->status === 'Pending') bg-yellow-100 text-yellow-800
                                    @elseif($s->status === 'Dibuat') bg-blue-100 text-blue-800
                                    @elseif($s->status === 'Selesai') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-700 @endif">
                                        {{ $s->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $s->created_at?->format('d M Y H:i') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right space-x-3 text-sm">
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

    {{-- === MODAL CREATE SURAT TUGAS === --}}
    <div id="createSuratModal" class="fixed inset-0 z-50 hidden">
        <div id="createSuratBackdrop" class="absolute inset-0 bg-black/50"></div>
        <div class="absolute inset-0 flex items-start md:items-center justify-center p-6">
            <div class="w-full max-w-4xl bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">

                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Buat Pengajuan Surat Tugas</h3>
                        <p class="text-sm text-gray-500">Lengkapi sesuai format surat dinas</p>
                    </div>
                    <button id="closeCreateSuratModal" class="p-2 rounded-full hover:bg-gray-100 text-gray-500">✕</button>
                </div>

                {{-- Body/Form --}}
                <form action="{{ route('pengajuan-surat.store') }}" method="POST" class="px-6 py-5 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        {{-- Laporan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Laporan Pengaduan</label>
                            <select name="laporan_id" required class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                <option value="" selected>— Pilih Laporan —</option>
                                @foreach ($laporanList ?? [] as $l)
                                    <option value="{{ $l->laporan_id }}" @selected(old('laporan_id') == $l->laporan_id)>
                                        #{{ $l->laporan_id }} — {{ $l->judul ?? $l->permasalahan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Penandatangan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Penandatangan Surat</label>
                            <select name="penandatangan_id" required
                                class="mt-1 w-full rounded-md border-gray-300 shadow-sm">
                                <option value="" disabled selected>— Pilih Penandatangan —</option>
                                @foreach ($userList ?? [] as $u)
                                    <option value="{{ $u->user_id }}" @selected(old('penandatangan_id') == $u->user_id)>
                                        {{ $u->nama_lengkap }} — {{ $u->jabatan ?? $u->role }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Repeater nama ditugaskan --}}
                    <div>
                        <div class="flex justify-between items-center">
                            <label class="block text-sm font-medium text-gray-700">
                                Nama yang Ditugaskan <span class="text-xs text-gray-400">(array JSON)</span>
                            </label>
                            <button type="button" id="btnAddNama"
                                class="px-3 py-1.5 border rounded-md text-sm hover:bg-gray-50">Tambah Nama</button>
                        </div>

                        <div id="namaWrap" class="mt-2 space-y-2">
                            <div class="grid grid-cols-12 gap-2 nama-row">
                                <div class="col-span-10">
                                    <input type="text" name="nama_ditugaskan[]"
                                        class="w-full rounded-md border-gray-300 shadow-sm"
                                        placeholder="Nama lengkap pegawai" value="{{ old('nama_ditugaskan.0') }}">
                                </div>
                                <div class="col-span-2 flex items-center">
                                    <button type="button"
                                        class="btn-remove-nama w-full px-3 py-2 rounded border text-red-600 hover:bg-red-50">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Deskripsi umum --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deskripsi Umum</label>
                        <textarea name="deskripsi_umum" rows="3" class="mt-1 w-full rounded-md border-gray-300 shadow-sm"
                            placeholder="Isi deskripsi pengajuan...">{{ old('deskripsi_umum') }}</textarea>
                    </div>

                    {{-- Footer --}}
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button type="button" id="cancelCreateSurat"
                            class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 rounded-lg bg-primary-600 text-white hover:bg-primary-700">Simpan
                            Pengajuan</button>
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

            // Tambah input nama
            const wrap = document.getElementById('namaWrap');
            const btnAdd = document.getElementById('btnAddNama');
            btnAdd?.addEventListener('click', () => {
                const row = document.createElement('div');
                row.className = 'grid grid-cols-12 gap-2 nama-row';
                row.innerHTML = `
                <div class="col-span-10">
                    <input type="text" name="nama_ditugaskan[]" class="w-full rounded-md border-gray-300 shadow-sm" placeholder="Nama lengkap pegawai">
                </div>
                <div class="col-span-2 flex items-center">
                    <button type="button" class="btn-remove-nama w-full px-3 py-2 rounded border text-red-600 hover:bg-red-50">Hapus</button>
                </div>
            `;
                wrap.appendChild(row);
            });

            // Delegasi hapus input nama
            wrap?.addEventListener('click', (e) => {
                if (e.target.classList.contains('btn-remove-nama')) {
                    e.preventDefault();
                    const row = e.target.closest('.nama-row');
                    if (row && wrap.children.length > 1) row.remove();
                }
            });
        })();
    </script>
@endpush
