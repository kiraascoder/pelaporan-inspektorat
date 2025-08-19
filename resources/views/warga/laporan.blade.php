@extends('layouts.dashboard')

@section('title', 'Laporan Saya')

@section('content')
    <div class="space-y-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $colors = [
                    'bg-blue-100 text-blue-800',
                    'bg-yellow-100 text-yellow-800',
                    'bg-purple-100 text-purple-800',
                    'bg-green-100 text-green-800',
                ];
                $titles = ['Total Laporan', 'Pending', 'Dalam Investigasi', 'Selesai'];
                $values = [
                    $stats['total_laporan'],
                    $stats['laporan_pending'],
                    $stats['laporan_dalam_investigasi'],
                    $stats['laporan_selesai'],
                ];
            @endphp
            @foreach ($titles as $index => $title)
                <div class="p-4 rounded-lg shadow bg-white border border-gray-200">
                    <div class="text-sm font-medium text-gray-700">{{ $title }}</div>
                    <div class="mt-1 text-2xl font-bold text-gray-900">{{ $values[$index] }}</div>
                </div>
            @endforeach
        </div>

        <!-- Recent Reports -->
        <div class="bg-white rounded-lg shadow border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-800">Laporan Saya</h3>
                <button onclick="openModal()"
                    class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Buat Laporan
                </button>
            </div>
            <div class="p-4">
                @if ($laporanTerbaru->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($laporanTerbaru as $laporan)
                            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                {{-- Status Badge + tanggal pengaduan --}}
                                <div class="flex justify-between items-start mb-3">
                                    @php
                                        // Status di DB: 'Pending', 'Dalam_Investigasi', 'Selesai', 'Ditolak'
                                        $statusClasses = [
                                            'Pending' => 'bg-yellow-100 text-yellow-800',
                                            'Dalam_Investigasi' => 'bg-blue-100 text-blue-800',
                                            'Selesai' => 'bg-green-100 text-green-800',
                                            'Ditolak' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusClass = $statusClasses[$laporan->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="text-xs px-2 py-1 rounded-full {{ $statusClass }}">
                                        {{ str_replace('_', ' ', $laporan->status) }}
                                    </span>

                                    <span class="text-xs text-gray-500">
                                        {{-- tampilkan tanggal pengaduan jika ada, fallback ke created_at --}}
                                        {{ optional($laporan->tanggal_pengaduan)->format('d M Y') ?? $laporan->created_at->format('d M Y') }}
                                    </span>
                                </div>

                                {{-- Judul/Card header: ringkas isi permasalahan --}}
                                <h4 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2">
                                    {{ \Illuminate\Support\Str::limit($laporan->permasalahan, 80) }}
                                </h4>

                                {{-- Ringkasan pelapor & terlapor --}}
                                <div class="text-xs text-gray-600 mb-3 space-y-1">
                                    <div class="flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                                        </svg>
                                        Pelapor: {{ $laporan->pelapor_nama ?? '-' }}
                                    </div>

                                    @if ($laporan->terlapor_nama)
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5.121 17.804A13.937 13.937 0 0112 15c2.5 0 4.847.655 6.879 1.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                                            </svg>
                                            Terlapor: {{ \Illuminate\Support\Str::limit($laporan->terlapor_nama, 40) }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Metadata tambahan: jumlah lampiran & harapan singkat --}}
                                <div class="text-xs text-gray-500 mb-3 space-y-1">
                                    @php
                                        $lampiranCount = is_array($laporan->bukti_pendukung)
                                            ? count($laporan->bukti_pendukung)
                                            : ($laporan->bukti_pendukung
                                                ? count((array) $laporan->bukti_pendukung)
                                                : 0);
                                    @endphp

                                    @if ($lampiranCount > 0)
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                                </path>
                                            </svg>
                                            {{ $lampiranCount }} Lampiran
                                        </div>
                                    @endif

                                    @if (!empty($laporan->harapan))
                                        <div class="flex items-start">
                                            <svg class="w-3 h-3 mr-1 mt-0.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5h12M9 3v2m-4 8h12M9 11v2m-4 8h12M9 19v2" />
                                            </svg>
                                            <span>Harapan:
                                                {{ \Illuminate\Support\Str::limit($laporan->harapan, 80) }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Actions --}}
                                <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('warga.laporan.show', $laporan->laporan_id) }}"
                                            class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                            Lihat Detail
                                        </a>
                                    </div>

                                    <form action="{{ route('warga.laporan.destroy', $laporan->laporan_id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination or Load More -->
                    @if ($laporanTerbaru->hasPages())
                        <div class="mt-6">
                            {{ $laporanTerbaru->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <p class="mt-4 text-sm text-gray-500">Belum ada laporan yang dibuat</p>
                        <button onclick="openModal()"
                            class="mt-4 px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                            Buat Laporan Pertama
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tips Section -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-start">
                <svg class="h-5 w-5 text-green-600 mt-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                        clip-rule="evenodd" />
                </svg>
                <div class="ml-3">
                    <h4 class="text-sm font-semibold text-green-800">Tips Laporan Efektif</h4>
                    <ul class="list-disc pl-4 text-sm text-green-700 mt-1 space-y-1">
                        <li>Detail kejadian secara lengkap</li>
                        <li>Tambahkan bukti jika tersedia</li>
                        <li>Tentukan waktu & lokasi</li>
                        <li>Gunakan bahasa sopan dan jelas</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Laporan -->
    <!-- MODAL WRAPPER -->
    <div id="laporanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50" onclick="closeModal(event)">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto"
                onclick="event.stopPropagation()">

                <form id="laporanForm" action="{{ route('warga.laporan.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="px-6 py-4 space-y-5">

                        <!-- HEADER FORM -->
                        <div class="text-center space-y-1">
                            <p class="text-xs tracking-wide">LAMPIRAN I</p>
                            <p class="text-xs">PERATURAN BUPATI SIDENRENG RAPPANG NOMOR 19 TAHUN 2024</p>
                            <p class="text-xs">TENTANG</p>
                            <p class="text-xs font-semibold uppercase">
                                PEDOMAN PENANGANAN PENGADUAN MASYARAKAT DI LINGKUNGAN INSPEKTORAT DAERAH
                            </p>
                        </div>

                        <h4 class="text-sm font-semibold">FORMAT FORMULIR PENGADUAN</h4>

                        <!-- No & Tanggal Pengaduan -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center">
                                <label class="w-44 text-sm text-gray-700">No. Pengaduan</label>
                                <input type="text" name="no_pengaduan"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                                    placeholder="(diisi petugas / sistem)">
                            </div>
                            <div class="flex items-center">
                                <label class="w-44 text-sm text-gray-700">Tanggal Pengaduan</label>
                                <input type="date" name="tanggal_pengaduan" value="{{ now()->toDateString() }}"
                                    class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500">
                            </div>
                        </div>

                        <!-- DATA PELAPOR -->
                        <div class="border border-gray-300 rounded-lg">
                            <div class="bg-gray-100 px-4 py-2 font-semibold text-sm">DATA PELAPOR</div>
                            <div class="p-4 space-y-3">
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm mb-1">Nama</label>
                                        <input type="text" name="pelapor_nama" required
                                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1">Pekerjaan/Jabatan</label>
                                        <input type="text" name="pelapor_pekerjaan"
                                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm mb-1">Alamat</label>
                                    <input type="text" name="pelapor_alamat"
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                                </div>
                                <div>
                                    <label class="block text-sm mb-1">No. Telp/HP</label>
                                    <input type="text" name="pelapor_telp"
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                                </div>
                            </div>
                        </div>

                        <!-- DATA TERLAPOR -->
                        <div class="border border-gray-300 rounded-lg">
                            <div class="bg-gray-100 px-4 py-2 font-semibold text-sm">DATA TERLAPOR</div>
                            <div class="p-4 space-y-3">
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm mb-1">Nama</label>
                                        <input type="text" name="terlapor_nama"
                                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm mb-1">Pekerjaan/Jabatan</label>
                                        <input type="text" name="terlapor_pekerjaan"
                                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm mb-1">Alamat</label>
                                    <input type="text" name="terlapor_alamat"
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                                </div>
                                <div>
                                    <label class="block text-sm mb-1">No. Telp/HP</label>
                                    <input type="text" name="terlapor_telp"
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                                </div>
                            </div>
                        </div>

                        <!-- SUBSTANSI PENGADUAN -->
                        <div class="border border-gray-300 rounded-lg">
                            <div class="bg-gray-100 px-4 py-2 font-semibold text-sm">SUBSTANSI PENGADUAN</div>
                            <div class="p-4 space-y-4">
                                <div>
                                    <label class="block text-sm mb-1">Permasalahan yang diadukan <span
                                            class="text-red-500">*</span></label>
                                    <textarea name="permasalahan" rows="5" required
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                                        placeholder="Uraikan kronologi, pihak terlibat, waktu & tempat kejadian"></textarea>
                                </div>

                                <div>
                                    <label class="block text-sm mb-1">Bukti pendukung pengaduan</label>
                                    <input type="file" name="bukti_pendukung[]" multiple
                                        accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                                    <p class="text-xs text-gray-500 mt-1">Bisa unggah lebih dari satu file. Maks. 10MB per
                                        file.</p>
                                </div>

                                <div>
                                    <label class="block text-sm mb-1">Harapan</label>
                                    <textarea name="harapan" rows="3" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                                        placeholder="Tuliskan harapan/permintaan penanganan"></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Pernyataan -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                            <p class="text-xs text-yellow-800">
                                <strong>Pernyataan:</strong> Saya menyatakan bahwa informasi yang saya berikan adalah benar
                                dan dapat
                                dipertanggungjawabkan. Laporan palsu dapat dikenakan sanksi hukum.
                            </p>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="agreement" id="agreement" required
                                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="agreement" class="ml-2 block text-sm text-gray-700">
                                Saya setuju dengan pernyataan di atas <span class="text-red-500">*</span>
                            </label>
                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700">Kirim
                            Laporan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>



    <script>
        function openModal() {
            document.getElementById('laporanModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal(event) {
            if (event && event.target !== event.currentTarget) return;
            document.getElementById('laporanModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            document.getElementById('laporanForm').reset();
        }

        function editLaporan(laporanId) {
            // Implementasi edit laporan
            window.location.href = `/laporan/${laporanId}/edit`;
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeModal();
            }
        });

        // Form validation
        document.getElementById('laporanForm').addEventListener('submit', function(e) {
            const agreement = document.getElementById('agreement');
            if (!agreement.checked) {
                e.preventDefault();
                alert('Anda harus menyetujui pernyataan sebelum mengirim laporan.');
                return false;
            }
        });
    </script>
@endsection
