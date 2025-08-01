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
                                <!-- Status Badge -->
                                <div class="flex justify-between items-start mb-3">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'dalam_investigasi' => 'bg-blue-100 text-blue-800',
                                            'selesai' => 'bg-green-100 text-green-800',
                                            'ditolak' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusColor = $statusColors[$laporan->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="text-xs px-2 py-1 rounded-full {{ $statusColor }}">
                                        {{ ucfirst(str_replace('_', ' ', $laporan->status)) }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $laporan->created_at->format('d M Y') }}
                                    </span>
                                </div>

                                <!-- Title -->
                                <h4 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2">
                                    {{ $laporan->judul_laporan }}
                                </h4>

                                <!-- Content Preview -->
                                <p class="text-xs text-gray-600 mb-3 line-clamp-3">
                                    {{ Str::limit($laporan->isi_laporan, 120) }}
                                </p>

                                <!-- Metadata -->
                                <div class="text-xs text-gray-500 mb-3 space-y-1">
                                    @if ($laporan->lokasi_kejadian)
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            {{ Str::limit($laporan->lokasi_kejadian, 40) }}
                                        </div>
                                    @endif
                                    @if ($laporan->tanggal_kejadian)
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            {{ \Carbon\Carbon::parse($laporan->tanggal_kejadian)->format('d M Y') }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex justify-between items-center pt-3 border-t border-gray-100">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('warga.laporan.show', $laporan->laporan_id) }}"
                                            class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                            Lihat Detail
                                        </a>
                                        @if ($laporan->status == 'pending')
                                            <button onclick="editLaporan({{ $laporan->laporan_id }})"
                                                class="text-green-600 hover:text-green-800 text-xs font-medium">
                                                Edit
                                            </button>
                                        @endif
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
                                    @if ($laporan->bukti_lampiran)
                                        <div class="flex items-center text-xs text-gray-500">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                </path>
                                            </svg>
                                            Ada Lampiran
                                        </div>
                                    @endif
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
    <div id="laporanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50" onclick="closeModal(event)">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                onclick="event.stopPropagation()">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Buat Laporan Baru</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="laporanForm" action="{{ route('warga.laporan.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="px-6 py-4 space-y-4">
                        <!-- Judul Laporan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Judul Laporan <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="judul_laporan" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Masukkan judul laporan yang jelas">
                        </div>

                        <!-- Isi Laporan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Isi Laporan <span
                                    class="text-red-500">*</span></label>
                            <textarea name="isi_laporan" rows="5" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Jelaskan secara detail kronologi kejadian, siapa yang terlibat, dan dampak yang ditimbulkan"></textarea>
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori<span
                                    class="text-red-500">*</span></label>
                            <select name="kategori" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Pilih Kategori</option>
                                <option value="korupsi">Korupsi</option>
                                <option value="penyalahgunaan_wewenang">Penyalahgunaan Wewenang</option>
                                <option value="pelayanan_publik">Pelayanan Publik</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>



                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Prioritas <span
                                    class="text-red-500">*</span></label>
                            <select name="prioritas" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Pilih Prioritas</option>
                                <option value="Rendah">Rendah</option>
                                <option value="Sedang">Sedang</option>
                                <option value="Tinggi">Tinggi</option>
                                <option value="Urgent">Urgent</option>
                            </select>
                        </div>

                        <!-- Tanggal dan Waktu Kejadian -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kejadian</label>
                                <input type="date" name="tanggal_kejadian"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Kejadian</label>
                                <input type="time" name="waktu_kejadian"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                        </div>

                        <!-- Lokasi Kejadian -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lokasi Kejadian</label>
                            <input type="text" name="lokasi_kejadian"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Masukkan lokasi lengkap kejadian">
                        </div>

                        <!-- Pihak Terlibat -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pihak Yang Terlibat</label>
                            <textarea name="pihak_terlibat" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Sebutkan nama, jabatan, atau identitas pihak yang terlibat (jika diketahui)"></textarea>
                        </div>

                        <!-- Bukti Lampiran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Lampiran</label>
                            <input type="file" name="bukti_dokumen" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <p class="text-xs text-gray-500 mt-1">Format yang didukung: JPG, PNG, PDF, DOC, DOCX (Max:
                                10MB)</p>
                        </div>


                        <!-- Pernyataan -->
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <div class="ml-3">
                                    <p class="text-xs text-yellow-800">
                                        <strong>Pernyataan:</strong> Saya menyatakan bahwa informasi yang saya berikan
                                        adalah benar dan dapat dipertanggungjawabkan. Laporan palsu dapat dikenakan sanksi
                                        hukum.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="agreement" id="agreement" required
                                class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                            <label for="agreement" class="ml-2 block text-sm text-gray-700">
                                Saya setuju dengan pernyataan di atas <span class="text-red-500">*</span>
                            </label>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors">
                            Kirim Laporan
                        </button>
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
