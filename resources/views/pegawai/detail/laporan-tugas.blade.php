@extends('layouts.dashboard')

@section('title', 'Detail Laporan')

@section('content')
    <div class="space-y-6">

        {{-- ================= HEADER ================= --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">Detail Laporan Pengaduan</h2>
                    <p class="text-sm text-gray-500 mt-1">Informasi laporan dan laporan tugas pegawai</p>
                </div>
                <a href="{{ route('pegawai.laporan') }}"
                    class="px-4 py-2 border rounded-lg text-sm text-gray-700 hover:bg-gray-50">
                    ← Kembali
                </a>
            </div>
        </div>

        {{-- ================= STATUS ================= --}}
        @php
            $authUserId = auth()->id();

            $isKetua = false;

            if ($tim) {
                $anggota = $tim->anggotaAktif->firstWhere('user_id', $authUserId);

                $isKetua = $anggota && $anggota->pivot->role_dalam_tim === 'Ketua';
            }
            $statusColors = [
                'Pending' => 'bg-yellow-100 text-yellow-800',
                'Dalam_Investigasi' => 'bg-blue-100 text-blue-800',
                'Selesai' => 'bg-green-100 text-green-800',
                'Ditolak' => 'bg-red-100 text-red-800',
            ];
            $statusColor = $statusColors[$laporan->status] ?? 'bg-gray-100 text-gray-800';
        @endphp

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium {{ $statusColor }}">
                <span class="w-2 h-2 rounded-full bg-current mr-2"></span>
                {{ str_replace('_', ' ', $laporan->status) }}
            </span>
            <span class="ml-3 text-xs text-gray-500">
                Dibuat {{ $laporan->created_at?->format('d M Y') }}
            </span>
        </div>

        {{-- ================= INFO ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl border p-5">
                <p class="text-xs text-gray-500">No Pengaduan</p>
                <p class="text-sm font-semibold text-gray-900">{{ $laporan->no_pengaduan ?? '-' }}</p>
            </div>
            <div class="bg-white rounded-xl border p-5">
                <p class="text-xs text-gray-500">Tanggal Pengaduan</p>
                <p class="text-sm font-semibold text-gray-900">
                    {{ optional($laporan->tanggal_pengaduan)->format('d M Y') ?? '-' }}
                </p>
            </div>
            <div class="bg-white rounded-xl border p-5">
                <p class="text-xs text-gray-500">Pelapor</p>
                <p class="text-sm font-semibold text-gray-900">{{ $laporan->pelapor_nama ?? '-' }}</p>
            </div>
        </div>

        {{-- ================= SUBSTANSI ================= --}}
        <div class="bg-white rounded-xl border p-6 space-y-5">
            <div>
                <p class="text-xs text-gray-500 mb-1">Permasalahan</p>
                <p class="text-sm text-gray-900 whitespace-pre-line">{{ $laporan->permasalahan }}</p>
            </div>

            @if ($laporan->harapan)
                <div class="pt-4 border-t">
                    <p class="text-xs text-gray-500 mb-1">Harapan</p>
                    <p class="text-sm text-gray-900 whitespace-pre-line">{{ $laporan->harapan }}</p>
                </div>
            @endif

            {{-- ================= LAMPIRAN ================= --}}
            @php
                $lampiran = $laporan->bukti_pendukung;
                if (is_string($lampiran)) {
                    $lampiran = json_decode($lampiran, true) ?: [];
                }
            @endphp

            @if (count($lampiran))
                <div class="pt-4 border-t">
                    <p class="text-xs text-gray-500 mb-2">Bukti Pendukung</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($lampiran as $i => $file)
                            <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                class="inline-flex items-center px-3 py-1 rounded-full
                                  bg-blue-50 text-blue-700 text-xs hover:bg-blue-100">
                                📎 Lampiran {{ $i + 1 }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($laporan->keterangan_admin)
                <div class="pt-4 border-t">
                    <p class="text-xs text-gray-500 mb-1">Keterangan Admin</p>
                    <p class="text-sm text-gray-900 whitespace-pre-line">
                        {{ $laporan->keterangan_admin }}
                    </p>
                </div>
            @endif
        </div>

        {{-- ================= LAPORAN TUGAS ================= --}}
        <div class="bg-white rounded-xl border p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Laporan Tugas Pegawai</h3>
                <span class="text-sm text-gray-500">Total: {{ $laporanTugas->count() }}</span>
            </div>

            @if ($laporanTugas->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full border text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 border">No</th>
                                <th class="px-4 py-3 border text-left">Pegawai</th>
                                <th class="px-4 py-3 border text-left">Judul</th>
                                <th class="px-4 py-3 border">Status</th>
                                <th class="px-4 py-3 border">Tanggal</th>
                                <th class="px-4 py-3 border text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($laporanTugas as $i => $lt)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border text-center">{{ $i + 1 }}</td>
                                    <td class="px-4 py-2 border">{{ $lt->pegawai->nama_lengkap ?? '-' }}</td>
                                    <td class="px-4 py-2 border">{{ $lt->judul_laporan }}</td>

                                    {{-- STATUS SELECT --}}
                                    <td class="px-4 py-2 border text-center">
                                        <p
                                            class="inline-block px-3 py-1 rounded-full text-xs font-medium
        {{ $lt->status_laporan === 'Submitted' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $lt->status_laporan }}
                                        </p>
                                    </td>

                                    <td class="px-4 py-2 border text-center">
                                        {{ $lt->created_at?->format('d M Y') }}
                                    </td>

                                    <td class="px-4 py-2 border text-center">
                                        <div class="flex items-center justify-center gap-3">

                                            {{-- ================= DOWNLOAD (SEMUA BOLEH) ================= --}}
                                            <a href=""
                                                class="text-indigo-600 hover:text-indigo-800 text-sm">
                                                Download
                                            </a>

                                            {{-- ================= JIKA KETUA TIM ================= --}}
                                            @if ($isKetua)
                                                {{-- Approve / Review --}}
                                                <form
                                                    action=""
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('PUT')

                                                    <select name="status_laporan" onchange="this.form.submit()"
                                                        class="text-xs border-gray-300 rounded-md px-2 py-1 bg-emerald-50 text-emerald-800">
                                                        <option value="">Approve</option>
                                                        <option value="Reviewed">Reviewed</option>
                                                        <option value="Approved">Approved</option>
                                                        <option value="Rejected">Rejected</option>
                                                    </select>
                                                </form>

                                                {{-- ================= JIKA ANGGOTA ================= --}}
                                            @else
                                                {{-- Edit --}}
                                                <button onclick='openEditLaporanModal(@json($lt))'
                                                    class="text-yellow-600 hover:text-yellow-800 text-sm">
                                                    Edit
                                                </button>

                                                {{-- Hapus --}}
                                                <form
                                                    action=""
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus laporan tugas ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-10 text-center text-gray-500 text-sm">
                    Belum ada laporan tugas yang dibuat.
                </div>
            @endif
        </div>

    </div>

    {{-- ================= MODAL EDIT LAPORAN TUGAS ================= --}}
    <div id="editLaporanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50"
        onclick="closeEditLaporanModal(event)">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                onclick="event.stopPropagation()">
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Edit Laporan Tugas</h3>
                    <button onclick="closeEditLaporanModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <form id="editLaporanForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="px-6 py-4 space-y-6">
                        {{-- Info Pengaduan --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-gray-700">
                                <span class="font-medium">No Pengaduan:</span>
                                <span id="edit_no_pengaduan" class="ml-2">{{ $laporan->no_pengaduan ?? '-' }}</span>
                            </p>
                        </div>

                        {{-- Judul Laporan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Laporan <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="judul_laporan" id="edit_judul_laporan" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Isi Laporan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Isi Laporan <span
                                    class="text-red-500">*</span></label>
                            <textarea name="isi_laporan" id="edit_isi_laporan" required rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        {{-- Temuan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Temuan</label>
                            <textarea name="temuan" id="edit_temuan" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        {{-- Rekomendasi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rekomendasi</label>
                            <textarea name="rekomendasi" id="edit_rekomendasi" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        {{-- Temuan Pemeriksaan (ceklist) --}}
                        @php
                            $opsi = [
                                'Terlapor Tidak ada di Rumah',
                                'Alamat Tidak Ditemukan',
                                'Dokumen Tidak Lengkap',
                                'Tidak Kooperatif saat dimintai keterangan',
                                'Kooperatif dan Bersedia Memberikan Keterangan',
                                'Butuh Pemeriksaan Lanjutan',
                                'Selesai Diperiksa Di Tempat',
                            ];
                        @endphp
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Temuan Pemeriksaan</label>
                            <div class="grid sm:grid-cols-2 gap-2" id="edit_temuan_pemeriksaan_container">
                                @foreach ($opsi as $label)
                                    <label
                                        class="flex items-start space-x-2 p-2 rounded border border-gray-200 hover:bg-gray-50">
                                        <input type="checkbox" name="temuan_pemeriksaan[]" value="{{ $label }}"
                                            class="mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <span class="text-sm text-gray-700">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Lampiran --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Pendukung Baru</label>
                            <input type="file" name="bukti_pendukung[]" multiple
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, JPG, PNG. Max 5MB/file</p>
                        </div>

                        {{-- Status Laporan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Laporan</label>
                            <select name="status_laporan" id="edit_status_laporan"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="Draft">Draft</option>
                                <option value="Submitted">Submitted</option>
                            </select>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button" onclick="closeEditLaporanModal()"
                            class="px-4 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Update
                            Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function openEditLaporanModal(laporan) {
            console.log('Opening modal with data:', laporan);

            // Set action form dengan route update
            const form = document.getElementById('editLaporanForm');
            form.action = `/pegawai/laporan-tugas/${laporan.laporan_tugas_id}`;

            // Isi field
            document.getElementById('edit_judul_laporan').value = laporan.judul_laporan || '';
            document.getElementById('edit_isi_laporan').value = laporan.isi_laporan || '';
            document.getElementById('edit_temuan').value = laporan.temuan || '';
            document.getElementById('edit_rekomendasi').value = laporan.rekomendasi || '';
            document.getElementById('edit_status_laporan').value = laporan.status_laporan || 'Draft';

            // Handle temuan pemeriksaan checkboxes
            const checkboxes = document.querySelectorAll('#edit_temuan_pemeriksaan_container input[type="checkbox"]');
            checkboxes.forEach(cb => cb.checked = false);

            if (laporan.temuan_pemeriksaan) {
                let temuanArray = [];
                if (typeof laporan.temuan_pemeriksaan === 'string') {
                    try {
                        temuanArray = JSON.parse(laporan.temuan_pemeriksaan);
                    } catch (e) {
                        temuanArray = laporan.temuan_pemeriksaan.split(',').map(s => s.trim());
                    }
                } else if (Array.isArray(laporan.temuan_pemeriksaan)) {
                    temuanArray = laporan.temuan_pemeriksaan;
                }

                checkboxes.forEach(cb => {
                    if (temuanArray.includes(cb.value)) {
                        cb.checked = true;
                    }
                });
            }

            // Buka modal
            document.getElementById('editLaporanModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeEditLaporanModal(event) {
            if (event && event.target !== event.currentTarget) return;
            document.getElementById('editLaporanModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    </script>
@endpush
