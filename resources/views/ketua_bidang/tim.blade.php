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
            <button onclick="openModal()"
                class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition-colors">
                Buat Tim Baru
            </button>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Tim -->
            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Tim</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalTim }}</p>
                        <p class="text-xs text-green-600 mt-1">Total semua tim</p>
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
                        <p class="text-xs text-gray-500 mt-1">Semua tim aktif</p>
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
                        <p class="text-xs text-orange-600 mt-1">Tim sedang dalam investigasi</p>
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
                        <p class="text-xs text-green-600 mt-1">Tim selesai investigasi</p>
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
                                <a href="{{ route('ketua_bidang.tim.show', $data->tim_id) }}"
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
                    <button type="button" onclick="closeModal()" class="text-gray-400 hover:text-gray-600"
                        aria-label="Tutup">
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

                        {{-- Nama Tim --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Tim <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_tim" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                placeholder="Misal: Tim Investigasi Drainase RW 05">
                        </div>
                                                

                        {{-- Laporan Terkait (tetap tampil) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Laporan Terkait</label>
                            <select name="laporan_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Pilih laporan (jika ada)</option>
                                @foreach ($laporanList as $lp)
                                    @php
                                        $lpId = $lp->laporan_id ?? $lp->id;
                                        $lpText = $lp->permasalahan ?? ($lp->judul ?? 'Laporan #' . $lpId);
                                    @endphp
                                    <option value="{{ $lpId }}">{{ $lpText }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Opsional. Jika halaman sudah konteks 1 laporan, biarkan
                                terpilih.</p>
                        </div>

                        {{-- Anggota Tim --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Anggota Tim</label>

                            {{-- Select untuk memilih pegawai (value = user_id) --}}
                            <div class="mb-4">
                                <select id="pegawaiSelect"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">Pilih Pegawai untuk ditambahkan</option>
                                </select>
                            </div>

                            {{-- Daftar anggota yang dipilih --}}
                            <div id="selectedAnggota" class="space-y-2">
                                <p class="text-sm text-gray-500" id="emptyMessage">Belum ada anggota dipilih</p>
                            </div>

                            {{-- Hidden inputs untuk submit (anggota_ids[] & anggota_roles[]) --}}
                            <div id="hiddenInputs"></div>
                        </div>

                        {{-- Ketua Tim (harus dari anggota yang dipilih) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Ketua Tim <span class="text-red-500">*</span>
                            </label>
                            <select name="ketua_tim_id" id="ketuaSelect" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">Pilih Ketua Tim</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Hanya anggota yang sudah dipilih yang bisa menjadi ketua.
                            </p>
                        </div>

                        {{-- Status Tim (default Dibentuk) --}}
                        <input type="hidden" name="status_tim" value="Dibentuk">
                        <div class="text-sm">
                            <span class="text-gray-700 font-medium">Status Tim</span>
                            <div
                                class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold ring-1 bg-green-100 text-green-800 ring-green-200">
                                Dibentuk
                            </div>
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
@endsection

@push('scripts')
    <script>
        // ================== CONFIG / DATA DARI BACKEND ==================
        // Pastikan tiap item punya { user_id, name }.
        const PEGAWAI_LIST = @json($pegawaiList ?? []); // contoh: [{user_id: 3, name: "Budi"}, ...]
        const ROLE_OPTIONS = ['Ketua', 'Anggota', 'Penanggung_Jawab', 'Wakil_Penanggung_Jawab', 'Pengendali_Teknis'];

        // ================== DOM ELEMEN ==================
        const modalEl = document.getElementById('timModal');
        const formEl = document.getElementById('timForm');
        const pegawaiSel = document.getElementById('pegawaiSelect');
        const selectedWrap = document.getElementById('selectedAnggota');
        const hiddenWrap = document.getElementById('hiddenInputs');
        const ketuaSel = document.getElementById('ketuaSelect');

        // ================== STATE ==================
        // { user_id: "7", name: "Nama", role: "Anggota" }
        const selected = [];

        // ================== MODAL CONTROL ==================
        function openModal() {
            modalEl.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            initForm();
        }

        function closeModal(e) {
            if (e && e.target && e.currentTarget && e.target !== e.currentTarget) return; // klik luar saja
            modalEl.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            formEl.reset();
            selected.length = 0;
            initForm();
        }
        window.openModal = openModal;
        window.closeModal = closeModal;

        // ================== INIT ==================
        function initForm() {
            renderPegawaiOptions();
            redrawSelected();
            redrawHiddenInputs();
            redrawKetuaOptions();
        }

        // Render opsi pegawai (value = user_id)
        function renderPegawaiOptions() {
            pegawaiSel.innerHTML = '<option value="">Pilih Pegawai untuk ditambahkan</option>';
            (PEGAWAI_LIST || []).forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.user_id; // WAJIB user_id untuk lolos exists:users,user_id
                opt.textContent = p.name ?? ('User #' + p.user_id);
                pegawaiSel.appendChild(opt);
            });
        }

        // Tambah anggota
        pegawaiSel.addEventListener('change', () => {
            const userId = pegawaiSel.value;
            if (!userId) return;
            if (selected.some(s => String(s.user_id) === String(userId))) {
                pegawaiSel.value = '';
                return;
            }

            const peg = (PEGAWAI_LIST || []).find(p => String(p.user_id) === String(userId));
            selected.push({
                user_id: String(userId),
                name: peg?.name || ('User #' + userId),
                role: 'Anggota'
            });

            pegawaiSel.value = '';
            redrawSelected();
            redrawHiddenInputs();
            redrawKetuaOptions();
        });

        // Gambar daftar anggota + kontrol rolenya
        function redrawSelected() {
            selectedWrap.innerHTML = '';
            if (selected.length === 0) {
                selectedWrap.innerHTML = '<p class="text-sm text-gray-500" id="emptyMessage">Belum ada anggota dipilih</p>';
                return;
            }

            selected.forEach((s) => {
                const row = document.createElement('div');
                row.className = 'flex items-center justify-between gap-3 border rounded-lg px-3 py-2';

                const left = document.createElement('div');
                left.className = 'flex items-center gap-3';
                left.innerHTML = `
        <div class="font-medium text-gray-900">${s.name}</div>
        <div class="text-xs text-gray-500">#${s.user_id}</div>
      `;

                const roleSel = document.createElement('select');
                roleSel.className =
                    'px-2 py-1 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 text-sm';
                ROLE_OPTIONS.forEach(r => {
                    const o = document.createElement('option');
                    o.value = r;
                    o.textContent = r.replaceAll('_', ' ');
                    if (r === s.role) o.selected = true;
                    roleSel.appendChild(o);
                });
                roleSel.addEventListener('change', () => {
                    s.role = roleSel.value;
                    if (s.role === 'Ketua') {
                        // hanya satu Ketua
                        selected.forEach(x => {
                            if (x !== s && x.role === 'Ketua') x.role = 'Anggota';
                        });
                        ketuaSel.value = String(s.user_id);
                    }
                    redrawSelected();
                    redrawHiddenInputs();
                });

                const rm = document.createElement('button');
                rm.type = 'button';
                rm.className = 'text-red-600 hover:text-red-700 text-sm';
                rm.textContent = 'Hapus';
                rm.addEventListener('click', () => {
                    const i = selected.findIndex(x => String(x.user_id) === String(s.user_id));
                    if (i !== -1) selected.splice(i, 1);
                    if (String(ketuaSel.value) === String(s.user_id)) ketuaSel.value = '';
                    redrawSelected();
                    redrawHiddenInputs();
                    redrawKetuaOptions();
                });

                const right = document.createElement('div');
                right.className = 'flex items-center gap-3';
                right.appendChild(roleSel);
                right.appendChild(rm);

                row.appendChild(left);
                row.appendChild(right);
                selectedWrap.appendChild(row);
            });
        }

        // Hidden inputs untuk submit (harus cocok dengan controller)
        function redrawHiddenInputs() {
            hiddenWrap.innerHTML = '';
            selected.forEach(s => {
                const inId = document.createElement('input');
                inId.type = 'hidden';
                inId.name = 'anggota_ids[]';
                inId.value = s.user_id; // HARUS user_id

                const inRole = document.createElement('input');
                inRole.type = 'hidden';
                inRole.name = 'anggota_roles[]';
                inRole.value = s.role;

                hiddenWrap.appendChild(inId);
                hiddenWrap.appendChild(inRole);
            });
        }

        // Ketua options: hanya anggota terpilih (value = user_id)
        function redrawKetuaOptions() {
            const curr = ketuaSel.value;
            ketuaSel.innerHTML = '<option value="">Pilih Ketua Tim</option>';
            selected.forEach(s => {
                const o = document.createElement('option');
                o.value = s.user_id;
                o.textContent = s.name;
                ketuaSel.appendChild(o);
            });
            if (selected.some(s => String(s.user_id) === String(curr))) {
                ketuaSel.value = curr;
            } else {
                ketuaSel.value = '';
            }
        }

        // Saat ketua dipilih manual → role jadi 'Ketua', yang lain bukan 'Ketua'
        ketuaSel.addEventListener('change', () => {
            const ketuaId = ketuaSel.value;
            if (!ketuaId) return;
            selected.forEach(s => {
                if (String(s.user_id) === String(ketuaId)) s.role = 'Ketua';
                else if (s.role === 'Ketua') s.role = 'Anggota';
            });
            redrawSelected();
            redrawHiddenInputs();
        });

        // Validasi ringan di client
        formEl.addEventListener('submit', function(e) {
            if (selected.length === 0) {
                e.preventDefault();
                alert('Pilih minimal satu anggota tim!');
                return;
            }
            if (!ketuaSel.value) {
                e.preventDefault();
                alert('Pilih ketua tim!');
                return;
            }
        });

        // Init awal
        document.addEventListener('DOMContentLoaded', initForm);
    </script>
@endpush
