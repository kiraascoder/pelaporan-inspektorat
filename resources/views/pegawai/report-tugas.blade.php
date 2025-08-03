@extends('layouts.dashboard')

@section('title', 'Laporan Tugas')

@section('content')
    <div class="space-y-6">

        <!-- Header Actions -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Laporan Tugas</h1>
                <p class="text-gray-600">Kelola laporan tugas dan monitoring progress</p>
            </div>
            <button onclick="openModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                + Buat Laporan
            </button>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Laporan -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Laporan</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalLaporan ?? 0 }}</p>
                        <p class="text-xs text-green-600 mt-1">↑ 5 laporan baru minggu ini</p>
                    </div>
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Laporan Dalam Progress -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Dalam Progress</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $laporanProgress ?? 0 }}</p>
                        <p class="text-xs text-gray-500 mt-1">Sedang dikerjakan</p>
                    </div>
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Laporan Menunggu Review -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Menunggu Review</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $laporanReview ?? 0 }}</p>
                        <p class="text-xs text-yellow-600 mt-1">Perlu perhatian segera</p>
                    </div>
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Laporan Selesai -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Selesai</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $laporanSelesai ?? 0 }}</p>
                        <p class="text-xs text-green-600 mt-1">↑ 15% dari bulan lalu</p>
                    </div>
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" placeholder="Cari judul laporan atau pelapor..."
                        class="w-full border-gray-300 rounded-md shadow-sm">
                </div>

            </div>
        </div>

        <!-- Laporan List -->
        <div class="grid grid-cols-1 gap-6">
            @forelse ($laporanList ?? [] as $laporan)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $laporan->judul_laporan ?? 'Judul Laporan' }}</h3>
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-gray-100 text-gray-800',
                                            'progress' => 'bg-orange-100 text-orange-800',
                                            'review' => 'bg-yellow-100 text-yellow-800',
                                            'selesai' => 'bg-green-100 text-green-800',
                                        ];
                                        $priorityColors = [
                                            'tinggi' => 'bg-red-100 text-red-800',
                                            'sedang' => 'bg-yellow-100 text-yellow-800',
                                            'rendah' => 'bg-blue-100 text-blue-800',
                                        ];
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$laporan->status ?? 'draft'] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($laporan->status ?? 'Draft') }}
                                    </span>
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $priorityColors[$laporan->prioritas ?? 'sedang'] ?? 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($laporan->prioritas ?? 'Sedang') }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 mb-3">
                                    {{ Str::limit($laporan->deskripsi ?? 'Deskripsi laporan tugas...', 150) }}</p>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span class="text-gray-600">
                                            <strong>Pelapor:</strong>
                                            {{ $laporan->pelapor->nama_lengkap ?? 'Tidak diketahui' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3a4 4 0 118 0v4m-4 6a4 4 0 100-8 4 4 0 000 8zm0 0v4a4 4 0 100 8 4 4 0 000-8z" />
                                        </svg>
                                        <span class="text-gray-600">
                                            <strong>Kategori:</strong> {{ $laporan->kategori ?? 'Umum' }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3a4 4 0 118 0v4m-4 6a4 4 0 100-8 4 4 0 000 8zm0 0v4a4 4 0 100 8 4 4 0 000-8z" />
                                        </svg>
                                        <span class="text-gray-600">
                                            <strong>Tanggal:</strong>
                                            {{ $laporan->created_at ? $laporan->created_at->format('d/m/Y') : date('d/m/Y') }}
                                        </span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-gray-600">
                                            <strong>Deadline:</strong>
                                            {{ $laporan->deadline ? \Carbon\Carbon::parse($laporan->deadline)->format('d/m/Y') : 'Tidak ada' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Progress Bar -->
                                @if (isset($laporan->progress))
                                    <div class="mt-4">
                                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                                            <span>Progress</span>
                                            <span>{{ $laporan->progress }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full"
                                                style="width: {{ $laporan->progress }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="flex items-center space-x-2">
                                @if (isset($laporan->tim_assigned))
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                        Tim: {{ $laporan->tim_assigned }}
                                    </span>
                                @endif
                                @if (isset($laporan->attachments_count) && $laporan->attachments_count > 0)
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ $laporan->attachments_count }} File
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center space-x-2">
                                <button
                                    class="text-blue-600 hover:text-blue-700 text-sm font-medium px-3 py-1 border border-blue-200 rounded hover:bg-blue-50 transition-colors">
                                    Edit
                                </button>
                                <button class="text-green-600 hover:text-green-700 text-sm font-medium">
                                    <a href="{{ route('pegawai.report.show', $laporan->laporan_tugas_id) }}">
                                        Detail →
                                    </a>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada laporan</h3>
                    <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat laporan tugas pertama Anda.</p>
                    <div class="mt-6">
                        <button onclick="openModal()"
                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            + Buat Laporan
                        </button>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Modal Buat Laporan -->
    <div id="laporanModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50" onclick="closeModal(event)">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                onclick="event.stopPropagation()">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Buat Laporan Tugas</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="laporanForm" action="{{ route('pegawai.laporan_tugas.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="px-6 py-4 space-y-6">
                        <!-- Judul Laporan -->
                        <x-form.input label="Judul Laporan" name="judul_laporan" required />

                        <!-- Isi Laporan -->
                        <x-form.textarea label="Isi Laporan" name="isi_laporan" required />

                        <!-- Temuan -->
                        <x-form.textarea label="Temuan" name="temuan" />

                        <!-- Rekomendasi -->
                        <x-form.textarea label="Rekomendasi" name="rekomendasi" />



                        <!-- Lampiran -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bukti Pendukung</label>
                            <input type="file" name="bukti_pendukung[]" multiple
                                accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <p class="text-xs text-gray-500 mt-1">Format: PDF, DOC, JPG, PNG. Max 5MB/file</p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Laporan</label>
                            <select name="status_laporan"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="Draft">Draft</option>
                                <option value="Submitted">Submitted</option>
                                <option value="Reviewed">Reviewed</option>
                                <option value="Approved">Approved</option>
                            </select>
                        </div>

                        <!-- Tanggal Submit -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Submit</label>
                            <input type="datetime-local" name="tanggal_submit"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 bg-gray-600 text-white text-sm rounded-lg hover:bg-gray-700">Batal</button>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">Simpan
                            Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    @push('scripts')
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

            // Handle form submission
            document.getElementById('laporanForm').addEventListener('submit', function(e) {
                const judul = this.querySelector('input[name="judul_laporan"]').value;
                const kategori = this.querySelector('select[name="kategori"]').value;
                const prioritas = this.querySelector('select[name="prioritas"]').value;
                const deskripsi = this.querySelector('textarea[name="deskripsi"]').value;

                if (!judul || !kategori || !prioritas || !deskripsi) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi!');
                    return;
                }

                return true;
            });

            // Set minimum date for deadline to today
            document.addEventListener('DOMContentLoaded', function() {
                const deadlineInput = document.querySelector('input[name="deadline"]');
                if (deadlineInput) {
                    const today = new Date().toISOString().split('T')[0];
                    deadlineInput.setAttribute('min', today);
                }
            });
        </script>
    @endpush
@endsection
