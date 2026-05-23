@extends('layouts.dashboard')

@section('title', 'Detail Tim Investigasi')

@section('content')
    @php
        $lap = $tim->laporanPengaduan;
        $suratTugas = $lap?->suratTugas;

        $auth = auth()->user();
        $authId = $auth->user_id ?? ($auth->id ?? null);

        $ketua = $tim->ketuaTim ?? null;
        $ketuaId = $ketua->user_id ?? ($ketua->id ?? null);
        $isAndaKetua = $authId && $ketuaId && (string) $authId === (string) $ketuaId;

        $anggotaAktif = collect($tim->anggotaAktif ?? [])
            ->reject(function ($a) use ($ketuaId) {
                $aid = $a->user_id ?? ($a->id ?? null);
                return $ketuaId && (string) $aid === (string) $ketuaId;
            })
            ->values();

        $totalOrang = ($ketua ? 1 : 0) + $anggotaAktif->count();

        $statusColors = [
            'Pending' => 'bg-yellow-100 text-yellow-800 ring-yellow-200',
            'Diterima' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
            'Dalam_Investigasi' => 'bg-blue-100 text-blue-800 ring-blue-200',
            'Dalam Investigasi' => 'bg-blue-100 text-blue-800 ring-blue-200',
            'Selesai' => 'bg-green-100 text-green-800 ring-green-200',
            'Ditolak' => 'bg-red-100 text-red-800 ring-red-200',
        ];

        $statusTimColors = [
            'Dibentuk' => 'bg-blue-100 text-blue-800 ring-blue-200',
            'Aktif' => 'bg-green-100 text-green-800 ring-green-200',
            'aktif' => 'bg-green-100 text-green-800 ring-green-200',
            'Nonaktif' => 'bg-gray-100 text-gray-800 ring-gray-200',
            'nonaktif' => 'bg-gray-100 text-gray-800 ring-gray-200',
        ];

        $statusLaporan = $lap->status ?? '-';
        $statusColor = $statusColors[$statusLaporan] ?? 'bg-gray-100 text-gray-800 ring-gray-200';
        $statusTimColor = $statusTimColors[$tim->status_tim ?? ''] ?? 'bg-gray-100 text-gray-800 ring-gray-200';

        $isFinalStatus = in_array($lap->status ?? null, ['Selesai', 'Ditolak']);

        $pelaporNama = $lap->pelapor_nama ?? ($lap->user->nama_lengkap ?? '-');
        $terlaporNama = $lap->terlapor_nama ?? '-';
        $kategori = $lap->kategori_pengaduan ?? ($lap->kategori ?? '-');

        $storageUrl = function ($path) {
            if (!$path) {
                return null;
            }

            $cleanPath = ltrim($path, '/');

            if (\Illuminate\Support\Str::startsWith($cleanPath, ['http://', 'https://'])) {
                return $cleanPath;
            }

            if (\Illuminate\Support\Str::startsWith($cleanPath, 'storage/')) {
                return asset($cleanPath);
            }

            return asset('storage/' . $cleanPath);
        };

        $lampiran = $lap->bukti_pendukung ?? [];

        if (is_string($lampiran)) {
            $decoded = json_decode($lampiran, true);
            $lampiran = is_array($decoded) ? $decoded : ($lampiran ? [$lampiran] : []);
        } elseif ($lampiran instanceof \Illuminate\Support\Collection) {
            $lampiran = $lampiran->toArray();
        } elseif (!is_array($lampiran)) {
            $lampiran = $lampiran ? (array) $lampiran : [];
        }

        $lampiran = array_values(array_filter($lampiran));
        $lampiranCount = count($lampiran);

        $getLampiranPath = function ($item) {
            if (is_array($item)) {
                return $item['path_file'] ?? ($item['path'] ?? ($item['file'] ?? null));
            }

            if (is_object($item)) {
                return $item->path_file ?? ($item->path ?? ($item->file ?? null));
            }

            return $item;
        };

        $getLampiranName = function ($item) use ($getLampiranPath) {
            if (is_array($item) && !empty($item['nama_file'])) {
                return $item['nama_file'];
            }

            if (is_object($item) && !empty($item->nama_file)) {
                return $item->nama_file;
            }

            $path = $getLampiranPath($item);

            return $path ? basename($path) : 'Lampiran';
        };

        $formatTanggal = function ($tanggal) {
            if (!$tanggal) {
                return '-';
            }

            try {
                return \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y');
            } catch (\Throwable $e) {
                return '-';
            }
        };

        $roleBadge = function ($role) {
            $map = [
                'Ketua' => 'bg-yellow-100 text-yellow-800 ring-yellow-200',
                'Penanggung_Jawab' => 'bg-purple-100 text-purple-800 ring-purple-200',
                'Wakil_Penanggung_Jawab' => 'bg-fuchsia-100 text-fuchsia-800 ring-fuchsia-200',
                'Pengendali_Teknis' => 'bg-blue-100 text-blue-800 ring-blue-200',
                'Anggota' => 'bg-gray-100 text-gray-800 ring-gray-200',
            ];

            return $map[$role] ?? 'bg-gray-100 text-gray-800 ring-gray-200';
        };

        $suratPath = $suratTugas->surat_tugas_path ?? null;
        $suratUrl = $storageUrl($suratPath);
    @endphp

    <div class="space-y-6">

        {{-- HEADER --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    {{ $tim->nama_tim }}
                </h1>
                <p class="text-gray-600 mt-1">
                    Detail tim investigasi, laporan pengaduan, bukti pendukung, dan surat tugas.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <span
                    class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium ring-1 {{ $statusColor }}">
                    <span class="w-2 h-2 rounded-full bg-current mr-2"></span>
                    {{ str_replace('_', ' ', $statusLaporan) }}
                </span>

                @if ($isAndaKetua && !$isFinalStatus)
                    <div x-data="{ openUpdate: false }" class="relative">
                        <button type="button" @click="openUpdate = !openUpdate"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm">
                            Update Status
                        </button>

                        <div x-show="openUpdate" x-transition
                            class="absolute right-0 mt-2 z-20 w-[520px] max-w-[90vw] bg-white border border-gray-200 rounded-xl p-4 shadow-lg">
                            <form action="{{ route('pegawai.laporan.updateStatus', $lap) }}" method="POST"
                                class="space-y-3">
                                @csrf
                                @method('PUT')

                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Status Laporan</label>
                                    <select name="status"
                                        class="w-full text-sm rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                                        <option value="Pending" @selected(($lap->status ?? '') === 'Pending')>Pending</option>
                                        <option value="Diterima" @selected(($lap->status ?? '') === 'Diterima')>Diterima</option>
                                        <option value="Dalam_Investigasi" @selected(($lap->status ?? '') === 'Dalam_Investigasi')>
                                            Dalam Investigasi
                                        </option>
                                        <option value="Selesai" @selected(($lap->status ?? '') === 'Selesai')>Selesai</option>
                                        <option value="Ditolak" @selected(($lap->status ?? '') === 'Ditolak')>Ditolak</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Catatan</label>
                                    <input type="text" name="keterangan_admin" placeholder="Catatan opsional"
                                        class="w-full text-sm rounded-md border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                                        value="{{ old('keterangan_admin', $lap->keterangan_admin) }}">
                                </div>

                                <div class="flex justify-end gap-2">
                                    <button type="button" @click="openUpdate = false"
                                        class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-md">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm rounded-md">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif

                <a href="{{ route('pegawai.tim') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Kembali
                </a>
            </div>
        </div>

        @if (!$lap)
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-5 text-yellow-800">
                Laporan pengaduan belum terhubung dengan tim ini.
            </div>
        @else
            {{-- RINGKASAN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="text-xs text-gray-500 uppercase tracking-wide">Nomor Pengaduan</div>
                    <div class="mt-1 font-semibold text-gray-900">
                        {{ $lap->no_pengaduan ?? 'LP-' . $lap->laporan_id }}
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="text-xs text-gray-500 uppercase tracking-wide">Kategori</div>
                    <div class="mt-1 font-semibold text-gray-900">
                        {{ str_replace('_', ' ', $kategori) }}
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="text-xs text-gray-500 uppercase tracking-wide">Tanggal Pengaduan</div>
                    <div class="mt-1 font-semibold text-gray-900">
                        {{ $formatTanggal($lap->tanggal_pengaduan ?? $lap->created_at) }}
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
                    <div class="text-xs text-gray-500 uppercase tracking-wide">Lampiran Bukti</div>
                    <div class="mt-1 font-semibold text-gray-900">
                        {{ $lampiranCount }} file
                    </div>
                </div>
            </div>

            {{-- SURAT TUGAS --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Surat Tugas</h2>
                        <p class="text-sm text-gray-500">
                            Surat tugas yang dibuat otomatis saat tim investigasi dibentuk.
                        </p>
                    </div>

                    @if ($suratUrl)
                        <a href="{{ $suratUrl }}" target="_blank"
                            class="inline-flex justify-center items-center px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">
                            Lihat Surat Tugas
                        </a>
                    @else
                        <span class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-600 text-sm rounded-lg">
                            File surat belum tersedia
                        </span>
                    @endif
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <div class="text-xs text-gray-500">Nomor Surat</div>
                        <div class="font-medium text-gray-900">
                            {{ $suratTugas->nomor_surat ?? '-' }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500">Tanggal Dibuat</div>
                        <div class="font-medium text-gray-900">
                            {{ $formatTanggal($suratTugas->created_at ?? null) }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs text-gray-500">File</div>
                        <div class="font-medium text-gray-900">
                            {{ $suratPath ? basename($suratPath) : '-' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- KONTEN UTAMA --}}
            <div class="grid grid-cols-12 gap-6">

                {{-- KIRI --}}
                <div class="col-span-12 xl:col-span-8 space-y-6">

                    {{-- DATA PELAPOR & TERLAPOR --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-900">Data Pelapor</h3>
                            </div>

                            <div class="p-6 space-y-4">
                                <div>
                                    <div class="text-xs text-gray-500">Nama</div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $pelaporNama }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-xs text-gray-500">Pekerjaan/Jabatan</div>
                                    <div class="text-sm text-gray-900">
                                        {{ $lap->pelapor_pekerjaan ?? '-' }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-xs text-gray-500">Alamat</div>
                                    <div class="text-sm text-gray-900 whitespace-pre-line">
                                        {{ $lap->pelapor_alamat ?? '-' }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-xs text-gray-500">No. Telp/HP</div>
                                    <div class="text-sm text-gray-900">
                                        {{ $lap->pelapor_telp ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                                <h3 class="font-semibold text-gray-900">Data Terlapor</h3>
                            </div>

                            <div class="p-6 space-y-4">
                                <div>
                                    <div class="text-xs text-gray-500">Nama</div>
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $terlaporNama }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-xs text-gray-500">Pekerjaan/Jabatan</div>
                                    <div class="text-sm text-gray-900">
                                        {{ $lap->terlapor_pekerjaan ?? '-' }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-xs text-gray-500">Alamat</div>
                                    <div class="text-sm text-gray-900 whitespace-pre-line">
                                        {{ $lap->terlapor_alamat ?? '-' }}
                                    </div>
                                </div>

                                <div>
                                    <div class="text-xs text-gray-500">No. Telp/HP</div>
                                    <div class="text-sm text-gray-900">
                                        {{ $lap->terlapor_telp ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SUBSTANSI --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-900">Substansi Pengaduan</h3>
                        </div>

                        <div class="p-6 space-y-6">
                            <div>
                                <div class="text-xs text-gray-500 mb-1">Permasalahan yang Diadukan</div>
                                <div class="text-sm text-gray-900 whitespace-pre-line leading-relaxed">
                                    {{ $lap->permasalahan ?? '-' }}
                                </div>
                            </div>

                            <div class="pt-5 border-t border-gray-100">
                                <div class="text-xs text-gray-500 mb-1">Harapan</div>
                                <div class="text-sm text-gray-900 whitespace-pre-line leading-relaxed">
                                    {{ $lap->harapan ?? '-' }}
                                </div>
                            </div>

                            @if (!empty($lap->keterangan_admin))
                                <div class="pt-5 border-t border-gray-100">
                                    <div class="text-xs text-gray-500 mb-1">Catatan / Keterangan</div>
                                    <div class="text-sm text-gray-900 whitespace-pre-line leading-relaxed">
                                        {{ $lap->keterangan_admin }}
                                    </div>
                                </div>
                            @endif

                            <div class="pt-5 border-t border-gray-100">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">Bukti Pendukung</div>
                                        <div class="text-xs text-gray-500">{{ $lampiranCount }} file terlampir</div>
                                    </div>
                                </div>

                                @if ($lampiranCount)
                                    <div class="grid sm:grid-cols-2 gap-3">
                                        @foreach ($lampiran as $i => $file)
                                            @php
                                                $path = $getLampiranPath($file);
                                                $namaFile = $getLampiranName($file);
                                                $url = $storageUrl($path);
                                            @endphp

                                            @if ($url)
                                                <a href="{{ $url }}" target="_blank"
                                                    class="flex items-center justify-between gap-3 px-4 py-3 rounded-lg border border-gray-200 hover:bg-blue-50 hover:border-blue-300">
                                                    <div class="min-w-0">
                                                        <div class="text-sm font-medium text-blue-700 truncate">
                                                            Lampiran {{ $i + 1 }}
                                                        </div>
                                                        <div class="text-xs text-gray-500 truncate">
                                                            {{ $namaFile }}
                                                        </div>
                                                    </div>
                                                    <span class="text-xs text-blue-600">Lihat</span>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div
                                        class="text-sm text-gray-500 bg-gray-50 border border-dashed border-gray-300 rounded-lg p-4">
                                        Belum ada bukti pendukung terlampir.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KANAN --}}
                <div class="col-span-12 xl:col-span-4 space-y-6">

                    {{-- TIM --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                            <h3 class="font-semibold text-gray-900">Tim Investigasi</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ $totalOrang }} orang dalam tim</p>
                        </div>

                        <div class="p-6 space-y-4">
                            <div class="p-4 rounded-lg bg-emerald-50 border border-emerald-100">
                                <div class="text-xs text-emerald-700">Ketua Tim</div>
                                <div class="font-semibold text-gray-900">
                                    {{ $ketua->nama_lengkap ?? '-' }}
                                    @if ($isAndaKetua)
                                        <span class="text-xs text-gray-500">(Anda)</span>
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-3">
                                @forelse ($anggotaAktif as $anggota)
                                    @php
                                        $anggotaId = $anggota->user_id ?? ($anggota->id ?? null);
                                        $isAndaAnggota =
                                            $authId && $anggotaId && (string) $authId === (string) $anggotaId;
                                        $role = $anggota->pivot->role_dalam_tim ?? 'Anggota';
                                        $label = str_replace('_', ' ', $role);
                                    @endphp

                                    <div
                                        class="flex items-start justify-between gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                                        <div class="min-w-0">
                                            <div class="text-sm font-medium text-gray-900 truncate">
                                                {{ $anggota->nama_lengkap ?? ($anggota->name ?? '-') }}
                                                @if ($isAndaAnggota)
                                                    <span class="text-xs text-gray-500">(Anda)</span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                User ID: {{ $anggotaId }}
                                            </div>
                                        </div>

                                        <span
                                            class="shrink-0 inline-flex px-2 py-0.5 rounded-full text-[11px] font-semibold ring-1 {{ $roleBadge($role) }}">
                                            {{ $label }}
                                        </span>
                                    </div>
                                @empty
                                    <div class="text-sm text-gray-500">
                                        Belum ada anggota selain ketua.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- INFO TIM --}}
                    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-4">
                        <h3 class="font-semibold text-gray-900">Informasi Tim</h3>

                        <div>
                            <div class="text-xs text-gray-500">Status Tim</div>
                            <span
                                class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ring-1 {{ $statusTimColor }}">
                                {{ str_replace('_', ' ', $tim->status_tim ?? '-') }}
                            </span>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Dibuat</div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $tim->created_at?->format('d M Y, H:i') ?? '-' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Laporan ID</div>
                            <div class="text-sm font-medium text-gray-900">
                                #{{ $lap->laporan_id }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">Tim ID</div>
                            <div class="text-sm font-medium text-gray-900">
                                #{{ $tim->tim_id }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
