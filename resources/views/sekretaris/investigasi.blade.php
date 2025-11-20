@extends('layouts.dashboard')

@section('title', 'Tim Investigasi')

@section('content')
    <div class="space-y-6">

        <!-- Header Actions -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tim Investigasi</h1>
                <p class="text-gray-600">Kelola tim investigasi dan monitoring kinerja</p>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Tim -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Tim</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalTim }}</p>
                        <p class="text-xs text-green-600 mt-1">↑ 2 tim baru bulan ini</p>
                    </div>
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Tim Aktif -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Tim Aktif</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $timAktif }}</p>
                        <p class="text-xs text-gray-500 mt-1">Sedang menangani kasus</p>
                    </div>
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Dalam Penanganan -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Dalam Penanganan</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $dalamInvestigasi }}</p>
                        <p class="text-xs text-orange-600 mt-1">↓ 3 hari dari target</p>
                    </div>
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Kasus Diselesaikan -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Kasus Selesai</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $kasusSelesai }}</p>
                        <p class="text-xs text-green-600 mt-1">↑ 12% dari bulan lalu</p>
                    </div>
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <form method="GET" action="{{ route('admin.tim') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" placeholder="Cari tim, ketua tim, atau kategori..."
                        value="{{ request('search') }}"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="w-full sm:w-48">
                    <select name="status_tim"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Status Tim</option>
                        <option value="aktif" {{ request('status_tim') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status_tim') == 'nonaktif' ? 'selected' : '' }}>Nonaktif
                        </option>
                    </select>
                </div>
                <div class="w-full sm:w-48">
                    <select name="status_laporan"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Status Laporan</option>
                        <option value="diproses" {{ request('status_laporan') == 'diproses' ? 'selected' : '' }}>Diproses
                        </option>
                        <option value="dalam_investigasi"
                            {{ request('status_laporan') == 'dalam_investigasi' ? 'selected' : '' }}>Dalam Investigasi
                        </option>
                        <option value="ditunda" {{ request('status_laporan') == 'ditunda' ? 'selected' : '' }}>Ditunda
                        </option>
                        <option value="selesai" {{ request('status_laporan') == 'selesai' ? 'selected' : '' }}>Selesai
                        </option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                    @if (request()->hasAny(['search', 'status_tim', 'status_laporan']))
                        <a href="{{ route('admin.tim') }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Active Filters Info -->
        @if (request()->hasAny(['search', 'status_tim', 'status_laporan']))
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-medium text-blue-900">Filter Aktif:</span>
                        <div class="flex flex-wrap gap-2">
                            @if (request('search'))
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Pencarian: "{{ request('search') }}"
                                </span>
                            @endif
                            @if (request('status_tim'))
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Status Tim: {{ ucfirst(request('status_tim')) }}
                                </span>
                            @endif
                            @if (request('status_laporan'))
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Status Laporan: {{ ucfirst(str_replace('_', ' ', request('status_laporan'))) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <span class="text-sm text-blue-700">
                        Menampilkan {{ $dataTim->total() }} hasil
                    </span>
                </div>
            </div>
        @endif

        <!-- Tim List Table -->
        @if ($dataTim->isEmpty())
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada tim investigasi</h3>
                <p class="mt-2 text-sm text-gray-500">Mulai dengan membuat tim investigasi pertama Anda.</p>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Kategori / Laporan
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Ketua Tim
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Anggota
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status Tim
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Status Laporan
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Dibuat
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($dataTim as $data)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    {{-- Kategori / Laporan --}}
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">
                                            {{ $data->laporanPengaduan->judul ?? 'Tanpa judul' }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            Kategori: {{ $data->laporanPengaduan->kategori ?? 'Tidak ada kategori' }}
                                        </div>
                                    </td>

                                    {{-- Ketua Tim --}}
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">
                                            {{ optional($data->ketuaTim)->nama_lengkap ?? 'Belum ditentukan' }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ optional($data->ketuaTim)->jabatan ?? '—' }}
                                        </div>
                                    </td>

                                    {{-- Anggota --}}
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $data->anggotaAktif->count() ?? 0 }} Anggota
                                        </span>
                                    </td>

                                    {{-- Status Tim --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusTim = $data->status_tim ?? 'tidak_diketahui';
                                        @endphp
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if ($statusTim === 'aktif') bg-green-100 text-green-800
                                            @elseif($statusTim === 'nonaktif') bg-gray-100 text-gray-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst($statusTim) }}
                                        </span>
                                    </td>

                                    {{-- Status Laporan --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($data->laporanPengaduan)
                                            @php
                                                $statusLap = $data->laporanPengaduan->status ?? 'tidak_diketahui';
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if ($statusLap === 'selesai') bg-green-100 text-green-800
                                                @elseif($statusLap === 'diproses' || $statusLap === 'dalam_investigasi') bg-blue-100 text-blue-800
                                                @elseif($statusLap === 'ditunda') bg-orange-100 text-orange-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst(str_replace('_', ' ', $statusLap)) }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-500">Tidak ada data</span>
                                        @endif
                                    </td>

                                    {{-- Dibuat --}}
                                    <td class="px-6 py-4 text-sm text-gray-700">
                                        <div>{{ $data->created_at ? $data->created_at->format('d M Y') : '—' }}</div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $data->created_at ? $data->created_at->format('H:i') : '' }}
                                        </div>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="px-6 py-4 text-right text-sm">
                                        <a href="{{ route('sekretaris.tim.show', $data->tim_id) }}"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if ($dataTim->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        {{ $dataTim->links() }}
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Data pegawai dari backend
        const pegawaiList = @json($pegawaiList ?? []);
        let selectedAnggotaList = [];

        // Initialize modal
        function initModal() {
            selectedAnggotaList = [];
            populatePegawaiSelect();
            updateAnggotaDisplay();
            updateKetuaOptions();
        }

        // Populate select dengan data pegawai
        function populatePegawaiSelect() {
            const select = document.getElementById('pegawaiSelect');
            if (!select) return;

            select.innerHTML = '<option value="">Pilih Pegawai untuk ditambahkan</option>';

            pegawaiList.forEach(pegawai => {
                const isSelected = selectedAnggotaList.some(selected =>
                    parseInt(selected.id) === parseInt(pegawai.id)
                );

                if (!isSelected) {
                    const option = document.createElement('option');
                    option.value = pegawai.id;
                    option.textContent = `${pegawai.nama_lengkap} - ${pegawai.jabatan || 'Tidak ada jabatan'}`;
                    option.dataset.userId = pegawai.user_id || pegawai.id;
                    option.dataset.nama = pegawai.nama_lengkap;
                    option.dataset.jabatan = pegawai.jabatan || 'Tidak ada jabatan';
                    select.appendChild(option);
                }
            });
        }

        // Handle pegawai selection
        document.addEventListener('DOMContentLoaded', function() {
            const pegawaiSelect = document.getElementById('pegawaiSelect');

            if (pegawaiSelect) {
                pegawaiSelect.addEventListener('change', function() {
                    if (this.value) {
                        const selectedOption = this.options[this.selectedIndex];
                        const pegawai = {
                            id: parseInt(this.value),
                            user_id: selectedOption.dataset.userId,
                            nama: selectedOption.dataset.nama,
                            jabatan: selectedOption.dataset.jabatan
                        };

                        addAnggota(pegawai);
                        this.value = '';
                    }
                });
            }

            initModal();
        });

        // Add anggota to selected list
        function addAnggota(pegawai) {
            const isAlreadySelected = selectedAnggotaList.some(selected =>
                parseInt(selected.id) === parseInt(pegawai.id)
            );

            if (isAlreadySelected) {
                console.log('Pegawai sudah dipilih:', pegawai.nama);
                return;
            }

            selectedAnggotaList.push(pegawai);
            updateAnggotaDisplay();
            updateKetuaOptions();
            populatePegawaiSelect();
        }

        // Remove anggota from selected list
        function removeAnggota(pegawaiId) {
            selectedAnggotaList = selectedAnggotaList.filter(anggota =>
                parseInt(anggota.id) !== parseInt(pegawaiId)
            );

            updateAnggotaDisplay();
            updateKetuaOptions();
            populatePegawaiSelect();
        }

        // Update display of selected anggota
        function updateAnggotaDisplay() {
            const container = document.getElementById('selectedAnggota');
            const hiddenInputs = document.getElementById('hiddenInputs');

            if (!container || !hiddenInputs) return;

            if (selectedAnggotaList.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-500">Belum ada anggota dipilih</p>';
                hiddenInputs.innerHTML = '';
                return;
            }

            let html = '';
            let hiddenInputsHtml = '';

            selectedAnggotaList.forEach(anggota => {
                html += `
                <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-lg p-3">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900">${anggota.nama}</div>
                        <div class="text-sm text-gray-600">${anggota.jabatan}</div>
                    </div>
                    <button type="button" onclick="removeAnggota(${anggota.id})"
                        class="ml-3 text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>`;

                hiddenInputsHtml += `<input type="hidden" name="pegawai_id[]" value="${anggota.user_id}">`;
            });

            container.innerHTML = html;
            hiddenInputs.innerHTML = hiddenInputsHtml;
        }

        // Update ketua options based on selected anggota
        function updateKetuaOptions() {
            const ketuaSelect = document.getElementById('ketuaSelect');
            if (!ketuaSelect) return;

            const currentValue = ketuaSelect.value;
            ketuaSelect.innerHTML = '<option value="">Pilih Ketua Tim</option>';

            selectedAnggotaList.forEach(anggota => {
                const option = document.createElement('option');
                option.value = anggota.id;
                option.textContent = anggota.nama;
                if (currentValue == anggota.id) {
                    option.selected = true;
                }
                ketuaSelect.appendChild(option);
            });
        }

        function openModal() {
            document.getElementById('timModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            initModal();
        }

        function closeModal(event) {
            if (event && event.target !== event.currentTarget) return;
            document.getElementById('timModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');

            document.getElementById('timForm').reset();
            selectedAnggotaList = [];
            initModal();
        }

        // Team Performance Chart
        if (document.getElementById('teamPerformanceChart')) {
            const teamPerformanceCtx = document.getElementById('teamPerformanceChart').getContext('2d');
            new Chart(teamPerformanceCtx, {
                type: 'radar',
                data: {
                    labels: ['Kecepatan', 'Kualitas', 'Komunikasi', 'Kepatuhan', 'Inovasi'],
                    datasets: [{
                        label: 'Tim Alpha',
                        data: [85, 90, 88, 92, 75],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        pointBackgroundColor: 'rgb(59, 130, 246)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(59, 130, 246)'
                    }, {
                        label: 'Tim Beta',
                        data: [78, 85, 90, 87, 82],
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.2)',
                        pointBackgroundColor: 'rgb(34, 197, 94)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(34, 197, 94)'
                    }, {
                        label: 'Tim Gamma',
                        data: [92, 88, 85, 95, 88],
                        borderColor: 'rgb(147, 51, 234)',
                        backgroundColor: 'rgba(147, 51, 234, 0.2)',
                        pointBackgroundColor: 'rgb(147, 51, 234)',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: 'rgb(147, 51, 234)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    scales: {
                        r: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        }

        // Handle form submission
        const timForm = document.getElementById('timForm');
        if (timForm) {
            timForm.addEventListener('submit', function(e) {
                if (selectedAnggotaList.length === 0) {
                    e.preventDefault();
                    alert('Pilih minimal satu anggota tim!');
                    return;
                }

                const ketuaId = document.getElementById('ketuaSelect').value;
                if (!ketuaId) {
                    e.preventDefault();
                    alert('Pilih ketua tim!');
                    return;
                }

                return true;
            });
        }
    </script>
@endpush
