@extends('layouts.dashboard')

@section('title', 'Detail Laporan')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow p-6 border border-gray-200">
            <div class="mb-4 flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">Detail Laporan</h2>
                <a href="{{ route('ketua_bidang.laporan') }}" class="text-sm text-green-600 hover:underline">← Kembali ke
                    Daftar laporan</a>
            </div>

            {{-- =========================
                 STATUS & KETERANGAN ADMIN (UI saja, tanpa action)
                 ========================= --}}
            <div class="mt-3 mb-4 border border-gray-100 rounded-md p-4 bg-gray-50">
                <h3 class="text-sm font-semibold text-gray-700 mb-2">Ubah Status & Keterangan Admin</h3>

                <div class="flex flex-col md:flex-row md:items-center gap-3">
                    <div class="flex items-center gap-3">
                        <label for="statusSelect" class="text-xs text-gray-500">Status</label>
                        <select id="statusSelect" class="text-sm rounded-md border-gray-300 px-3 py-2"
                            data-laporan-id="{{ $laporan->laporan_id ?? ($laporan->id ?? '') }}">
                            <option value="Pending" {{ ($laporan->status ?? '') == 'Pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="Diterima" {{ ($laporan->status ?? '') == 'Diterima' ? 'selected' : '' }}>Diterima
                            </option>
                            <option value="Ditolak" {{ ($laporan->status ?? '') == 'Ditolak' ? 'selected' : '' }}>Ditolak
                            </option>                            
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button id="btnUpdateStatus" type="button"
                            class="px-3 py-1.5 rounded-md bg-primary-600 text-white text-sm hover:bg-primary-700"
                            title="Tombol ini belum terhubung ke backend">
                            Update Status
                        </button>
                    </div>
                </div>

                <div class="mt-4">
                    <label for="keteranganAdmin" class="text-sm font-medium text-gray-700">Keterangan Admin</label>
                    <p class="text-xs text-gray-500 mb-2">
                        Masukkan keterangan yang akan disimpan ketika status diubah. Placeholder berubah otomatis
                        berdasarkan status terpilih.
                    </p>

                    <textarea id="keteranganAdmin" rows="5"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm resize-y"
                        placeholder="Pilih status terlebih dahulu...">{{ old('keterangan_admin', $laporan->keterangan_admin ?? '') }}</textarea>

                    <div class="flex items-center gap-2 mt-2">
                        <button id="fillTemplateDiterima" type="button"
                            class="px-2 py-1 rounded-md text-sm border border-green-300 hover:bg-green-50">
                            Isi template (Diterima)
                        </button>
                        <button id="fillTemplateDitolak" type="button"
                            class="px-2 py-1 rounded-md text-sm border border-red-300 hover:bg-red-50">
                            Isi template (Ditolak)
                        </button>

                        <span class="text-xs text-gray-500 ml-3">Contoh template cepat untuk percepatan input.</span>
                    </div>
                </div>

                <div id="keteranganPreview" class="mt-4 hidden">
                    <h4 class="text-sm font-semibold text-gray-700 mb-1">Preview Keterangan</h4>
                    <div class="border border-dashed border-gray-200 rounded-md p-3 text-sm text-gray-800 whitespace-pre-line"
                        id="keteranganPreviewContent"></div>
                </div>
            </div>

            {{-- =========================
                 Status badge
                 ========================= --}}
            <div class="mb-4">
                @php
                    $statusColors = [
                        'Pending' => 'bg-yellow-100 text-yellow-800',
                        'Diterima' => 'bg-green-100 text-green-800',
                        'Dalam_Investigasi' => 'bg-blue-100 text-blue-800',
                        'Selesai' => 'bg-emerald-100 text-emerald-800',
                        'Ditolak' => 'bg-red-100 text-red-800',
                    ];
                    $statusColor = $statusColors[$laporan->status] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span id="currentStatusBadge" class="inline-block px-3 py-1 rounded-full text-sm {{ $statusColor }}">
                    {{ str_replace('_', ' ', $laporan->status) }}
                </span>
            </div>

            {{-- =========================
                 Info ringkas
                 ========================= --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Tanggal Pengaduan</h3>
                    <p class="text-gray-900">
                        {{ optional($laporan->tanggal_pengaduan)->format('d M Y') ?? $laporan->created_at->format('d M Y') }}
                    </p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">No. Pengaduan</h3>
                    <p class="text-gray-900">{{ $laporan->no_pengaduan ?? '-' }}</p>
                </div>
            </div>

            {{-- Data Pelapor & Terlapor --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="border border-gray-200 rounded-lg">
                    <div class="bg-gray-50 px-4 py-2 text-sm font-semibold">DATA PELAPOR</div>
                    <div class="p-4 space-y-2 text-sm">
                        <div><span class="text-gray-500">Nama:</span> <span
                                class="text-gray-900">{{ $laporan->pelapor_nama ?? ($laporan->user->nama_lengkap ?? '-') }}</span>
                        </div>
                        <div><span class="text-gray-500">Pekerjaan/Jabatan:</span> <span
                                class="text-gray-900">{{ $laporan->pelapor_pekerjaan ?? '-' }}</span></div>
                        <div><span class="text-gray-500">Alamat:</span> <span
                                class="text-gray-900">{{ $laporan->pelapor_alamat ?? '-' }}</span></div>
                        <div><span class="text-gray-500">Telp/HP:</span> <span
                                class="text-gray-900">{{ $laporan->pelapor_telp ?? '-' }}</span></div>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-lg">
                    <div class="bg-gray-50 px-4 py-2 text-sm font-semibold">DATA TERLAPOR</div>
                    <div class="p-4 space-y-2 text-sm">
                        <div><span class="text-gray-500">Nama:</span> <span
                                class="text-gray-900">{{ $laporan->terlapor_nama ?? '-' }}</span></div>
                        <div><span class="text-gray-500">Pekerjaan/Jabatan:</span> <span
                                class="text-gray-900">{{ $laporan->terlapor_pekerjaan ?? '-' }}</span></div>
                        <div><span class="text-gray-500">Alamat:</span> <span
                                class="text-gray-900">{{ $laporan->terlapor_alamat ?? '-' }}</span></div>
                        <div><span class="text-gray-500">Telp/HP:</span> <span
                                class="text-gray-900">{{ $laporan->terlapor_telp ?? '-' }}</span></div>
                    </div>
                </div>
            </div>

            {{-- Substansi Pengaduan --}}
            <div class="space-y-5">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Permasalahan yang Diadukan</h3>
                    <p class="text-gray-900 whitespace-pre-line">{{ $laporan->permasalahan }}</p>
                </div>

                @if (!empty($laporan->harapan))
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-1">Harapan</h3>
                        <p class="text-gray-900 whitespace-pre-line">{{ $laporan->harapan }}</p>
                    </div>
                @endif

                @php
                    $lampiran = $laporan->bukti_pendukung;
                    if (is_string($lampiran)) {
                        $decoded = json_decode($lampiran, true);
                        $lampiran = is_array($decoded) ? $decoded : ($lampiran ? [$lampiran] : []);
                    } elseif (!is_array($lampiran)) {
                        $lampiran = $lampiran ? (array) $lampiran : [];
                    }
                @endphp
                @if (count($lampiran) > 0)
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Bukti Pendukung</h3>
                        <ul class="list-disc pl-5 space-y-1 text-sm">
                            @foreach ($lampiran as $i => $path)
                                <li>
                                    <a href="{{ asset('storage/' . $path) }}" target="_blank"
                                        class="text-blue-600 hover:underline">
                                        Lampiran {{ $i + 1 }}
                                    </a>
                                    <span class="text-gray-400 ml-1">({{ basename($path) }})</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            @if (!empty($laporan->keterangan_admin))
                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-1">Keterangan Admin (tersimpan)</h3>
                    <p class="text-gray-900 whitespace-pre-line text-sm">{{ $laporan->keterangan_admin }}</p>
                </div>
            @endif

            <div class="mt-6 text-xs text-gray-500">
                Dibuat pada {{ $laporan->created_at ? $laporan->created_at->format('d M Y') : '-' }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function() {
                const statusSelect = document.getElementById('statusSelect');
                const keterangan = document.getElementById('keteranganAdmin');
                const previewBox = document.getElementById('keteranganPreview');
                const previewContent = document.getElementById('keteranganPreviewContent');
                const fillDiterima = document.getElementById('fillTemplateDiterima');
                const fillDitolak = document.getElementById('fillTemplateDitolak');
                const currentBadge = document.getElementById('currentStatusBadge');
                const btnUpdateStatus = document.getElementById('btnUpdateStatus');

                const templates = {
                    'Diterima': 'Status: Diterima\n\nKeterangan: Pengaduan diterima. Tindakan selanjutnya akan dilakukan oleh tim terkait. Mohon menunggu pemberitahuan selanjutnya.',
                    'Ditolak': 'Status: Ditolak\n\nKeterangan: Pengaduan ditolak karena tidak memenuhi ketentuan/berkas tidak lengkap. Silakan lengkapi berkas/penjelasan dan ajukan kembali jika perlu.'
                };

                function updatePlaceholder() {
                    const s = statusSelect.value;

                    if (s === 'Diterima') {
                        keterangan.placeholder =
                            'Tuliskan keterangan untuk status Diterima (alasan, tindak lanjut, PIC, dll).';
                    } else if (s === 'Ditolak') {
                        keterangan.placeholder = 'Tuliskan alasan penolakan secara singkat dan jelas.';
                    } else if (s === 'Pending') {
                        keterangan.placeholder = 'Opsional: catatan untuk pending (mis. perlu verifikasi berkas).';
                    } else {
                        keterangan.placeholder = 'Tambahkan catatan/komentar admin jika perlu.';
                    }

                    let colorClass = 'bg-gray-100 text-gray-800';
                    if (s === 'Diterima') colorClass = 'bg-green-100 text-green-800';
                    if (s === 'Ditolak') colorClass = 'bg-red-100 text-red-800';
                    if (s === 'Pending') colorClass = 'bg-yellow-100 text-yellow-800';
                    if (s === 'Dalam_Investigasi') colorClass = 'bg-blue-100 text-blue-800';
                    if (s === 'Selesai') colorClass = 'bg-emerald-100 text-emerald-800';

                    currentBadge.className = 'inline-block px-3 py-1 rounded-full text-sm ' + colorClass;
                    currentBadge.textContent = s.replace('_', ' ');
                }

                statusSelect.addEventListener('change', updatePlaceholder);
                updatePlaceholder();

                fillDiterima.onclick = () => keterangan.value = templates['Diterima'];
                fillDitolak.onclick = () => keterangan.value = templates['Ditolak'];

                btnUpdateStatus.onclick = function() {
                    const laporanId = statusSelect.dataset.laporanId;
                    if (!confirm('Yakin mengubah status laporan?')) return;

                    btnUpdateStatus.disabled = true;
                    btnUpdateStatus.textContent = 'Menyimpan...';

                    fetch(`/ketua-investigasi/laporan/${laporanId}/status`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status: statusSelect.value,
                                keterangan_admin: keterangan.value
                            })
                        })
                        .then(() => location.reload())
                        .catch(() => alert('Gagal menyimpan'))
                        .finally(() => {
                            btnUpdateStatus.disabled = false;
                            btnUpdateStatus.textContent = 'Update Status';
                        });
                };
            })();
        </script>
    @endpush
@endsection
