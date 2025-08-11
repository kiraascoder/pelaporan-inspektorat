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
                        <p class="text-xs text-green-600 mt-1">↑ 2 tim baru bulan ini <b>Statis</b></p>
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
                        <p class="text-xs text-gray-500 mt-1">Sedang menangani kasus <b>Statis</b></p>
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
                        <p class="text-xs text-orange-600 mt-1">↓ 3 hari dari target <b>Statis</b></p>
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
                        <p class="text-xs text-green-600 mt-1">↑ 12% dari bulan lalu <b>Statis</b></p>
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
            <div class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" placeholder="Cari tim atau ketua tim..."
                        class="w-full border-gray-300 rounded-md shadow-sm">
                </div>
            </div>
        </div>

        <!-- Tim List - Fixed Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach ($dataTim as $data)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <p class="text-sm text-gray-600 capitalize">
                                        Kategori: {{ $data->laporanPengaduan->kategori ?? 'Tidak ada kategori' }}
                                    </p>
                                </div>
                            </div>
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                {{ $data->status_tim == 'aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($data->status_tim) }}
                            </span>
                        </div>

                        <div class="mb-4">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="text-sm text-gray-600">
                                    <strong>Ketua:</strong> {{ $data->ketuaTim->nama_lengkap ?? 'Belum ditentukan' }}
                                </span>
                            </div>
                            <div class="flex items-center space-x-2 mt-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <span class="text-sm text-gray-600">
                                    <strong>Anggota:</strong> {{ $data->anggotaAktif->count() ?? 0 }} Anggota
                                </span>
                            </div>

                            <!-- Tanggal Dibuat -->
                            <div class="flex items-center space-x-2 mt-1">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3a4 4 0 118 0v4m-4 6v6m0-6a4 4 0 100-8 4 4 0 000 8zm0 0v4a4 4 0 100 8 4 4 0 000-8z" />
                                </svg>
                                <span class="text-sm text-gray-600">
                                    <strong>Dibuat:</strong>
                                    {{ $data->created_at ? $data->created_at->format('d M Y') : 'Tidak diketahui' }}
                                </span>
                            </div>
                        </div>

                        <!-- Progress/Status Info (jika ada) -->
                        @if (isset($data->progress_kasus))
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs font-medium text-gray-700">Progress Kasus</span>
                                    <span class="text-xs text-gray-600">{{ $data->progress_kasus }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full"
                                        style="width: {{ $data->progress_kasus }}%"></div>
                                </div>
                            </div>
                        @endif

                        <!-- Action Button -->
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center space-x-2 text-xs text-gray-500">
                                @if ($data->laporanPengaduan)
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-800">
                                        {{ $data->laporanPengaduan->status ?? 'Status tidak dikenal' }}
                                    </span>
                                @endif
                            </div>

                            <div class="flex items-center space-x-2">
                                <button
                                    class="text-primary-600 hover:text-primary-700 text-sm font-medium transition-colors">
                                    <a href="{{ route('admin.tim.show', $data->tim_id) }}"
                                        class="flex items-center">
                                        Detail
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Empty State jika tidak ada tim -->
            @if ($dataTim->isEmpty())
                <div class="col-span-full">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Belum ada tim investigasi</h3>
                        <p class="mt-2 text-sm text-gray-500">Mulai dengan membuat tim investigasi pertama Anda.</p>
                        <div class="mt-6">                            
                        </div>
                    </div>
                </div>
            @endif
        </div>
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
            selectedAnggotaList = []; // Reset selected list
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
                // Check if pegawai is already selected
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
                        this.value = ''; // Reset select
                    }
                });
            }

            // Initialize modal when DOM is ready
            initModal();
        });

        // Add anggota to selected list
        function addAnggota(pegawai) {
            // Check if already selected using parseInt for comparison
            const isAlreadySelected = selectedAnggotaList.some(selected =>
                parseInt(selected.id) === parseInt(pegawai.id)
            );

            if (isAlreadySelected) {
                console.log('Pegawai sudah dipilih:', pegawai.nama);
                return;
            }

            selectedAnggotaList.push(pegawai);
            console.log('Added anggota:', pegawai.nama, 'Total:', selectedAnggotaList.length);

            updateAnggotaDisplay();
            updateKetuaOptions();
            populatePegawaiSelect(); // Refresh available options
        }

        // Remove anggota from selected list
        function removeAnggota(pegawaiId) {
            console.log('Removing pegawai with ID:', pegawaiId);

            const initialLength = selectedAnggotaList.length;
            selectedAnggotaList = selectedAnggotaList.filter(anggota =>
                parseInt(anggota.id) !== parseInt(pegawaiId)
            );

            console.log('Removed. Before:', initialLength, 'After:', selectedAnggotaList.length);

            updateAnggotaDisplay();
            updateKetuaOptions();
            populatePegawaiSelect(); // Refresh available options
        }

        // Update display of selected anggota
        function updateAnggotaDisplay() {
            const container = document.getElementById('selectedAnggota');
            const hiddenInputs = document.getElementById('hiddenInputs');

            if (!container || !hiddenInputs) return;

            if (selectedAnggotaList.length === 0) {
                container.innerHTML = '<p class="text-sm text-gray-500" id="emptyMessage">Belum ada anggota dipilih</p>';
                hiddenInputs.innerHTML = '';
                return;
            }

            // Create display for each selected anggota
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
                </div>
                `;

                // Add hidden input for form submission
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

            // Reset form dan state
            document.getElementById('timForm').reset();
            selectedAnggotaList = [];
            initModal(); // Reinitialize everything
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
        document.getElementById('timForm').addEventListener('submit', function(e) {
            // Validate anggota
            if (selectedAnggotaList.length === 0) {
                e.preventDefault();
                alert('Pilih minimal satu anggota tim!');
                return;
            }

            // Validate ketua
            const ketuaId = document.getElementById('ketuaSelect').value;
            if (!ketuaId) {
                e.preventDefault();
                alert('Pilih ketua tim!');
                return;
            }

            // Form valid, allow submission
            return true;
        });
    </script>
@endpush
