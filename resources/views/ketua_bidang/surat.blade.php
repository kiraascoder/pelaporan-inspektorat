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
                                    <a href="{{ route('ketua-bidang.surat.show', $s->pengajuan_surat_id) }}"
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
