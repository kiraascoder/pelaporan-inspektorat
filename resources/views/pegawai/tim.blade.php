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

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Dalam Penanganan</p>
                        {{-- <p class="text-2xl font-semibold text-gray-900">{{ $dalamInvestigasi }}</p> --}}
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
                        {{-- <p class="text-2xl font-semibold text-gray-900">{{ $kasusSelesai }}</p> --}}
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
        <!-- Filter & Search -->
        <form action="{{ url()->current() }}" method="GET"
            class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Cari nama tim / ketua tim / kategori…"
                        class="w-full border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div class="flex gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-primary-600 text-white rounded-md text-sm font-medium hover:bg-primary-700">
                        Cari
                    </button>
                    <a href="{{ url()->current() }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md text-sm font-medium hover:bg-gray-200">
                        Reset
                    </a>
                </div>
            </div>
        </form>


        <!-- Tim List -->
        <!-- Tim List (Table) -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tim
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ketua
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Anggota
                        </th>                        
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status
                            Tim</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Dibuat
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @php
                        $auth = auth()->user();
                        $authId = $auth->user_id ?? ($auth->id ?? null);

                        $badge = function ($status) {
                            $map = [
                                'aktif' => 'bg-green-100 text-green-800 ring-green-200',
                                'nonaktif' => 'bg-gray-100 text-gray-800 ring-gray-200',
                                'Aktif' => 'bg-green-100 text-green-800 ring-green-200',
                                'Nonaktif' => 'bg-gray-100 text-gray-800 ring-gray-200',
                            ];
                            $cls = $map[$status] ?? 'bg-gray-100 text-gray-800 ring-gray-200';
                            return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ring-1 ' .
                                $cls .
                                '">' .
                                e($status) .
                                '</span>';
                        };
                    @endphp

                    @forelse ($timList as $data)
                        @php
                            $ketua = $data->ketuaTim ?? null;
                            $ketuaId = $ketua->user_id ?? ($ketua->id ?? null);
                            $isAndaKetua = $authId && $ketuaId && $authId === $ketuaId;

                            $kategori = optional($data->laporanPengaduan)->kategori;
                            if ($kategori) {
                                $kategori = str_replace('_', ' ', $kategori);
                            }

                            $anggotaCount = method_exists($data->anggotaAktif, 'count')
                                ? $data->anggotaAktif->count()
                                : (is_countable($data->anggotaAktif ?? [])
                                    ? count($data->anggotaAktif)
                                    : 0);
                        @endphp

                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $data->nama_tim }}</div>
                                <div class="text-xs text-gray-500">#{{ $data->tim_id }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">
                                    {{ $ketua->nama_lengkap ?? '—' }}
                                    @if ($isAndaKetua)
                                        <span class="text-xs text-gray-500">(Anda)</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $anggotaCount }} orang</div>
                            </td>
                        
                            <td class="px-6 py-4">
                                {!! $badge($data->status_tim) !!}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $data->created_at?->format('d M Y') ?? '—' }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('pegawai.tim.show', $data->tim_id) }}"
                                    class="text-primary-600 hover:text-primary-800 text-sm font-medium">
                                    Detail →
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">
                                Belum ada tim investigasi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            @if (method_exists($timList, 'links'))
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $timList->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Tambah Tim -->
    <div id="timModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50" onclick="closeModal(event)">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                onclick="event.stopPropagation()">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Buat Tim Investigasi</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form id="timForm" action="{{ route('ketua_bidang.store-tim') }}" method="POST">
                    @csrf
                    <div class="px-6 py-4 space-y-6">
                        <!-- Nama Tim -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tim <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="nama_tim" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Masukkan nama tim">
                        </div>

                        <!-- Deskripsi Tim -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Tim</label>
                            <textarea name="deskripsi_tim" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Jelaskan tugas atau fokus tim ini (opsional)"></textarea>
                        </div>

                        <!-- Anggota Tim -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Anggota Tim</label>

                            <!-- Select untuk memilih pegawai -->
                            <div class="mb-4">
                                <select id="pegawaiSelect"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">Pilih Pegawai untuk ditambahkan</option>
                                </select>
                            </div>

                            <!-- Container untuk anggota yang dipilih -->
                            <div id="selectedAnggota" class="space-y-2">
                                <p class="text-sm text-gray-500" id="emptyMessage">Belum ada anggota dipilih</p>
                            </div>

                            <!-- Hidden inputs untuk form submission -->
                            <div id="hiddenInputs"></div>
                        </div>

                        <!-- Laporan Terkait -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Laporan Terkait</label>
                            <select name="laporan_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Pilih laporan (jika ada)</option>
                                @foreach ($laporanList as $laporan)
                                    <option value="{{ $laporan->id }}">{{ $laporan->permasalahan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Ketua Tim -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ketua Tim <span
                                    class="text-red-500">*</span></label>
                            <select name="ketua_tim_id" id="ketuaSelect" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Pilih Ketua Tim</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Hanya anggota yang sudah dipilih yang bisa menjadi ketua
                            </p>
                        </div>

                        <!-- Status Tim -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Tim</label>
                            <select name="status_tim"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
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
                            Simpan Tim
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
@endsection
