@extends('layouts.dashboard')

@section('title', 'Detail Laporan Tugas')

@section('content')
    <div class="space-y-6">
        <!-- Header with Breadcrumb -->
        <div class="flex items-center justify-between">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('pegawai.laporan_tugas') }}" class="text-gray-700 hover:text-blue-600">
                                Laporan Tugas
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-gray-500">Detail</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl font-bold text-gray-900">{{ $laporan->judul_laporan ?? 'Detail Laporan Tugas' }}</h1>
            </div>

            <div class="flex items-center space-x-3">
                @if ($laporan->isDraft())
                    <button onclick="openEditModal()"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                        Edit
                    </button>
                @endif

                @if ($laporan->isSubmitted())
                    <button onclick="downloadPDF()"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Download PDF
                    </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Informasi Utama -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-3">
                                @php
                                    $statusColors = [
                                        'Draft' => 'bg-gray-100 text-gray-800',
                                        'Submitted' => 'bg-yellow-100 text-yellow-800',
                                        'Reviewed' => 'bg-blue-100 text-blue-800',
                                        'Approved' => 'bg-green-100 text-green-800',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$laporan->status_laporan] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $laporan->status_laporan ?? 'Draft' }}
                                </span>

                                @if ($laporan->suratTugas)
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                        {{ $laporan->suratTugas->kategori_surat ?? 'Surat Tugas' }}
                                    </span>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="text-gray-600">
                                        <strong>Pelapor:</strong> {{ $laporan->pegawai->nama_lengkap ?? 'Admin User' }}
                                    </span>
                                </div>

                                @if ($laporan->suratTugas)
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <span class="text-gray-600">
                                            <strong>Surat Tugas:</strong> {{ $laporan->suratTugas->nomor_surat ?? '-' }}
                                        </span>
                                    </div>
                                @endif

                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3a4 4 0 118 0v4m-4 6a4 4 0 100-8 4 4 0 000 8zm0 0v4a4 4 0 100 8 4 4 0 000-8z" />
                                    </svg>
                                    <span class="text-gray-600">
                                        <strong>Dibuat:</strong>
                                        {{ $laporan->created_at ? $laporan->created_at->format('d M Y, H:i') : date('d M Y, H:i') }}
                                    </span>
                                </div>

                                @if ($laporan->tanggal_submit)
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-gray-600">
                                            <strong>Tanggal Submit:</strong>
                                            {{ $laporan->tanggal_submit->format('d M Y, H:i') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Isi Laporan -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Isi Laporan</h3>
                        <div class="prose max-w-none text-gray-700 bg-gray-50 p-4 rounded-lg">
                            {!! nl2br(e($laporan->isi_laporan ?? 'Belum ada isi laporan.')) !!}
                        </div>
                    </div>

                    <!-- Temuan -->
                    @if ($laporan->temuan)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Temuan</h3>
                            <div class="prose max-w-none text-gray-700 bg-orange-50 border-l-4 border-orange-400 p-4">
                                {!! nl2br(e($laporan->temuan)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Rekomendasi -->
                    @if ($laporan->rekomendasi)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Rekomendasi</h3>
                            <div class="prose max-w-none text-gray-700 bg-green-50 border-l-4 border-green-400 p-4">
                                {!! nl2br(e($laporan->rekomendasi)) !!}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Timeline Aktivitas -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline Aktivitas</h3>
                    <div class="space-y-4">
                        @php
                            $activities = [];

                            // Activity untuk created
                            $activities[] = [
                                'type' => 'created',
                                'user' => $laporan->pegawai->nama_lengkap ?? 'User',
                                'time' => $laporan->created_at
                                    ? $laporan->created_at->diffForHumans()
                                    : 'Waktu tidak diketahui',
                                'desc' => 'Laporan dibuat',
                            ];

                            // Activity untuk submit jika ada
                            if ($laporan->tanggal_submit) {
                                $activities[] = [
                                    'type' => 'submitted',
                                    'user' => $laporan->pegawai->nama_lengkap ?? 'User',
                                    'time' => $laporan->tanggal_submit->diffForHumans(),
                                    'desc' => 'Laporan disubmit',
                                ];
                            }

                            // Activity berdasarkan status
                            if ($laporan->status_laporan == 'Reviewed') {
                                $activities[] = [
                                    'type' => 'reviewed',
                                    'user' => 'Supervisor',
                                    'time' => $laporan->updated_at
                                        ? $laporan->updated_at->diffForHumans()
                                        : 'Waktu tidak diketahui',
                                    'desc' => 'Laporan telah direview',
                                ];
                            }

                            if ($laporan->status_laporan == 'Approved') {
                                $activities[] = [
                                    'type' => 'approved',
                                    'user' => 'Manager',
                                    'time' => $laporan->updated_at
                                        ? $laporan->updated_at->diffForHumans()
                                        : 'Waktu tidak diketahui',
                                    'desc' => 'Laporan telah disetujui',
                                ];
                            }
                        @endphp

                        @foreach ($activities as $activity)
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    @if ($activity['type'] == 'created')
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                        </div>
                                    @elseif($activity['type'] == 'submitted')
                                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                    @elseif($activity['type'] == 'reviewed')
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                </path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">
                                        <span class="font-medium">{{ $activity['user'] }}</span> {{ $activity['desc'] }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $activity['time'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Informasi Surat Tugas -->
                @if ($laporan->suratTugas)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Surat Tugas</h3>

                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Nomor Surat:</label>
                                <p class="text-sm text-gray-900">{{ $laporan->suratTugas->nomor_surat ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Kategori:</label>
                                <p class="text-sm text-gray-900">{{ $laporan->suratTugas->kategori_surat ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Tanggal Mulai:</label>
                                <p class="text-sm text-gray-900">
                                    {{ $laporan->suratTugas->tanggal_mulai ? \Carbon\Carbon::parse($laporan->suratTugas->tanggal_mulai)->format('d M Y') : '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-700">Tanggal Selesai:</label>
                                <p class="text-sm text-gray-900">
                                    {{ $laporan->suratTugas->tanggal_selesai ? \Carbon\Carbon::parse($laporan->suratTugas->tanggal_selesai)->format('d M Y') : '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Status dan Aksi -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status dan Aksi</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">Status Laporan:</span>
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$laporan->status_laporan] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $laporan->status_laporan }}
                            </span>
                        </div>

                        @if ($laporan->isDraft())
                            <div class="border-t pt-4">
                                <button onclick="submitLaporan({{ $laporan->laporan_tugas_id }})"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                                    Submit Laporan
                                </button>
                            </div>
                        @endif

                        @if ($laporan->isSubmitted())
                            <div class="border-t pt-4 text-center">
                                <svg class="mx-auto h-8 w-8 text-green-400 mb-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm text-gray-600">Laporan telah disubmit</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- File Bukti Pendukung -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">File Bukti Pendukung</h3>

                    @if ($laporan->bukti_pendukung && count($laporan->bukti_pendukung) > 0)
                        <div class="space-y-3">
                            @foreach ($laporan->bukti_pendukung as $file)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-3">
                                        @php
                                            $extension = pathinfo($file, PATHINFO_EXTENSION);
                                        @endphp

                                        @if (in_array(strtolower($extension), ['pdf']))
                                            <div class="p-2 bg-red-100 rounded">
                                                <svg class="w-4 h-4 text-red-600" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @elseif(in_array(strtolower($extension), ['xlsx', 'xls']))
                                            <div class="p-2 bg-green-100 rounded">
                                                <svg class="w-4 h-4 text-green-600" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @elseif(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                            <div class="p-2 bg-blue-100 rounded">
                                                <svg class="w-4 h-4 text-blue-600" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="p-2 bg-gray-100 rounded">
                                                <svg class="w-4 h-4 text-gray-600" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        @endif

                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ basename($file) }}</p>
                                            <p class="text-xs text-gray-500">{{ ucfirst($extension) }} File</p>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/' . $file) }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            <p class="text-sm text-gray-500 mt-2">Tidak ada file bukti pendukung</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Modal -->
    <div id="submitModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi Submit Laporan</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Setelah laporan disubmit, Anda tidak dapat mengeditnya lagi. Pastikan semua data sudah benar.
                </p>
                <div class="flex justify-end space-x-3">
                    <button onclick="closeSubmitModal()"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">
                        Batal
                    </button>
                    <button onclick="confirmSubmit()"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg">
                        Ya, Submit
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function submitLaporan(id) {
            document.getElementById('submitModal').classList.remove('hidden');
        }

        function closeSubmitModal() {
            document.getElementById('submitModal').classList.add('hidden');
        }

        function confirmSubmit() {
            // Ajax call to submit laporan
            // Implementation depends on your backend
            window.location.reload();
        }

        function downloadPDF() {
            // Implementation untuk download PDF
            window.open(`/laporan-tugas/{{ $laporan->laporan_tugas_id }}/pdf`, '_blank');
        }

        function openEditModal() {
            // Implementation untuk edit modal
            window.location.href = `/laporan-tugas/{{ $laporan->laporan_tugas_id }}/edit`;
        }
    </script>
@endsection
