{{-- resources/views/pegawai/laporan_tugas/index.blade.php --}}
@extends('layouts.dashboard')

@section('title', 'Laporan Tugas')

@section('content')
    {{-- ================= Flash Message ================= --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-start justify-between">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span class="text-sm font-medium">
                    {{ session('success') }}
                </span>
            </div>
            <button @click="show = false" class="text-green-600 hover:text-green-800">
                ✕
            </button>
        </div>
    @endif

    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-start justify-between">
            <div class="flex items-start gap-2">
                <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
                <span class="text-sm font-medium">
                    {{ session('error') }}
                </span>
            </div>
            <button @click="show = false" class="text-red-600 hover:text-red-800">
                ✕
            </button>
        </div>
    @endif

    <div class="space-y-6">

        {{-- ================= Header Actions ================= --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Tugas</h1>
                <p class="text-gray-600">Kelola laporan tugas dari pengaduan yang sudah selesai</p>
            </div>
        </div>

        {{-- ================= Key Metrics ================= --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total Laporan Pengaduan Selesai --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pengaduan Selesai</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalPengaduanSelesai ?? 0 }}</p>
                        <p class="text-xs text-green-600 mt-1">Siap dibuatkan laporan</p>
                    </div>
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Total Laporan Tugas --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Laporan Tugas</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalLaporanTugas ?? 0 }}</p>
                        <p class="text-xs text-blue-600 mt-1">Laporan yang telah dibuat</p>
                    </div>
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Submitted --}}
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Submitted</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $laporanSubmitted ?? 0 }}</p>
                        <p class="text-xs text-green-600 mt-1">Telah disubmit</p>
                    </div>
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= Filter + Table Pengaduan Selesai ================= --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col md:flex-row md:items-center justify-between space-y-4 md:space-y-0">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Pengaduan Selesai</h3>

                    <div class="flex flex-col sm:flex-row gap-3">
                        {{-- Cari --}}
                        <div class="flex items-center">
                            <input id="q" type="text" placeholder="Cari no pengaduan/pelapor..."
                                value="{{ request('q') }}"
                                class="w-60 border border-gray-300 rounded-md px-3 py-1.5 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div class="flex space-x-2">
                            <button type="button" id="apply_filter"
                                class="bg-blue-600 text-white px-4 py-1.5 rounded-md text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Cari
                            </button>
                            <button type="button" id="reset_filter"
                                class="bg-gray-500 text-white px-4 py-1.5 rounded-md text-sm font-medium hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= Table Pengaduan ================= --}}
            <div class="overflow-x-auto">
                @if (($pengaduanSelesai ?? collect())->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    No Pengaduan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pelapor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Permasalahan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tanggal Pengaduan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($pengaduanSelesai as $pengaduan)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $pengaduan->no_pengaduan ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $pengaduan->pelapor_nama ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="max-w-xs text-sm text-gray-900">
                                            {{ \Illuminate\Support\Str::limit($pengaduan->permasalahan ?? '—', 80) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $pengaduan->tanggal_pengaduan ? $pengaduan->tanggal_pengaduan->format('d M Y') : '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Selesai
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('pegawai.report.show', $pengaduan->laporan_id) }}">
                                                <button class="text-blue-600 hover:text-blue-800">
                                                    Detail
                                                </button>
                                            </a>
                                            <button
                                                onclick="openLaporanModal({{ $pengaduan->laporan_id }}, '{{ $pengaduan->no_pengaduan }}')"
                                                class="text-green-600 hover:text-green-800">
                                                Buat Laporan
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    @if (method_exists($pengaduanSelesai, 'links'))
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $pengaduanSelesai->appends(request()->query())->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pengaduan selesai</h3>
                            <p class="mt-1 text-sm text-gray-500">Pengaduan yang statusnya selesai akan muncul di sini.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- ================= Modal Detail Pengaduan ================= --}}
    <div id="detailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50" onclick="closeDetailModal(event)">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto"
                onclick="event.stopPropagation()">
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Detail Pengaduan</h3>
                    <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <div id="detailContent" class="px-6 py-4 space-y-4">
                    <div class="flex justify-center py-8">
                        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= Modal Buat Laporan Tugas ================= --}}
    <div id="laporanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50"
        onclick="closeLaporanModal(event)">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                onclick="event.stopPropagation()">
                {{-- Header --}}
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Buat Laporan Tugas</h3>
                    <button onclick="closeLaporanModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Body --}}
                <form id="laporanForm" action="{{ route('pegawai.laporan_tugas.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="laporan_pengaduan_id" id="laporan_pengaduan_id">

                    <div class="px-6 py-4 space-y-6">
                        {{-- Info Pengaduan --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-gray-700">
                                <span class="font-medium">No Pengaduan:</span>
                                <span id="info_no_pengaduan" class="ml-2">-</span>
                            </p>
                        </div>

                        {{-- Judul Laporan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Laporan <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="judul_laporan" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        {{-- Isi Laporan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Isi Laporan <span
                                    class="text-red-500">*</span></label>
                            <textarea name="isi_laporan" required rows="4"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        {{-- Temuan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Temuan</label>
                            <textarea name="temuan" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>

                        {{-- Rekomendasi --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rekomendasi</label>
                            <textarea name="rekomendasi" rows="3"
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
                            <div class="grid sm:grid-cols-2 gap-2">
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
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Pendukung</label>
                            <input type="file" name="bukti_pendukung[]" multiple
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, JPG, PNG. Max 5MB/file</p>
                        </div>

                        {{-- Status Laporan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Laporan</label>
                            <select name="status_laporan"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="Draft">Draft</option>
                                <option value="Submitted">Submitted</option>
                            </select>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button" onclick="closeLaporanModal()"
                            class="px-4 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Simpan
                            Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Modal Buat Laporan
        function openLaporanModal(laporanId, noPengaduan) {
            document.getElementById('laporan_pengaduan_id').value = laporanId;
            document.getElementById('info_no_pengaduan').textContent = noPengaduan;
            document.getElementById('laporanModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeLaporanModal(event) {
            if (event && event.target !== event.currentTarget) return;
            document.getElementById('laporanModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            const f = document.getElementById('laporanForm');
            if (f) f.reset();
        }

        // Modal Detail
        function openDetailModal(laporanId) {
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('detailContent');

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            // Fetch detail via AJAX
            fetch(`/pegawai/laporan/${laporanId}`)
                .then(response => response.json())
                .then(data => {
                    content.innerHTML = `
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500">No Pengaduan</p>
                                    <p class="text-sm text-gray-900">${data.no_pengaduan || '—'}</p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500">Tanggal Pengaduan</p>
                                    <p class="text-sm text-gray-900">${data.tanggal_pengaduan || '—'}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Pelapor</p>
                                <p class="text-sm text-gray-900">${data.pelapor_nama || '—'}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Permasalahan</p>
                                <p class="text-sm text-gray-900">${data.permasalahan || '—'}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Harapan</p>
                                <p class="text-sm text-gray-900">${data.harapan || '—'}</p>
                            </div>
                        </div>
                    `;
                })
                .catch(error => {
                    content.innerHTML = '<p class="text-red-600 text-center">Gagal memuat detail</p>';
                });
        }

        function closeDetailModal(event) {
            if (event && event.target !== event.currentTarget) return;
            document.getElementById('detailModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Filter
        document.addEventListener('DOMContentLoaded', function() {
            const applyFilterBtn = document.getElementById('apply_filter');
            const resetFilterBtn = document.getElementById('reset_filter');
            const qInput = document.getElementById('q');

            if (applyFilterBtn) {
                applyFilterBtn.addEventListener('click', function() {
                    const params = new URLSearchParams(window.location.search);
                    ['q', 'page'].forEach(k => params.delete(k));
                    if (qInput && qInput.value) params.set('q', qInput.value);
                    const baseUrl = window.location.pathname;
                    const newUrl = params.toString() ? `${baseUrl}?${params}` : baseUrl;
                    window.location.href = newUrl;
                });
            }

            if (resetFilterBtn) {
                resetFilterBtn.addEventListener('click', function() {
                    if (qInput) qInput.value = '';
                    window.location.href = window.location.pathname;
                });
            }

            if (qInput) {
                qInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        if (applyFilterBtn) applyFilterBtn.click();
                    }
                });
            }
        });
    </script>
@endpush
