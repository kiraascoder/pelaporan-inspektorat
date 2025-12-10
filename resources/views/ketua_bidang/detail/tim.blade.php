@extends('layouts.dashboard')

@section('title', 'Detail Tim Investigasi')

@section('content')
    @php
        $lap = $tim->laporanPengaduan;

        $statusColors = [
            'Pending' => 'bg-yellow-100 text-yellow-800 ring-yellow-200',
            'Diterima' => 'bg-emerald-100 text-emerald-800 ring-emerald-200',
            'Dalam_Investigasi' => 'bg-blue-100 text-blue-800 ring-blue-200',
            'Selesai' => 'bg-green-100 text-green-800 ring-green-200',
            'Ditolak' => 'bg-red-100 text-red-800 ring-red-200',
        ];
        $statusColor = $statusColors[$lap->status ?? ''] ?? 'bg-gray-100 text-gray-800 ring-gray-200';

        // normalisasi lampiran
        $lampiran = $lap->bukti_pendukung ?? [];
        if (is_string($lampiran)) {
            $dec = json_decode($lampiran, true);
            $lampiran = is_array($dec) ? $dec : ($lampiran ? [$lampiran] : []);
        } elseif (!is_array($lampiran)) {
            $lampiran = $lampiran ? (array) $lampiran : [];
        }
        $lampiranCount = count($lampiran);

        $pelaporNama = $lap->pelapor_nama ?? ($lap->user->nama_lengkap ?? '-');
        $terlaporNama = $lap->terlapor_nama ?? '-';
    @endphp

    <div class="space-y-6 w-full">

        {{-- Header bar full --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $tim->nama_tim }}</h1>
                <p class="text-gray-600 mt-1">Detail tim & laporan yang ditangani</p>
            </div>
            <div class="flex items-center gap-3">
                <span
                    class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium ring-1 {{ $statusColor }}">
                    <span class="w-2 h-2 rounded-full bg-current mr-2"></span>
                    {{ str_replace('_', ' ', $lap->status ?? '-') }}
                </span>
                <a href="{{ route('ketua_bidang.tim') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali
                </a>
            </div>
        </div>

        {{-- Banner info full width --}}
        <div class="w-full bg-gradient-to-r from-blue-50 to-indigo-50 border border-gray-200 rounded-xl p-6">
            <div class="flex flex-col md:flex-row gap-4 md:items-start">
                <div class="flex-shrink-0 bg-white/70 p-3 rounded-lg shadow-sm ring-1 ring-white">
                    <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0" />
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="bg-white rounded-lg p-3 ring-1 ring-gray-200">
                            <div class="text-[11px] text-gray-500">Pelapor</div>
                            <div class="text-sm font-medium text-gray-900">{{ $pelaporNama }}</div>
                        </div>
                        <div class="bg-white rounded-lg p-3 ring-1 ring-gray-200">
                            <div class="text-[11px] text-gray-500">Terlapor</div>
                            <div class="text-sm font-medium text-gray-900">{{ $terlaporNama }}</div>
                        </div>
                        <div class="bg-white rounded-lg p-3 ring-1 ring-gray-200">
                            <div class="text-[11px] text-gray-500">Tgl Pengaduan</div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ optional($lap->tanggal_pengaduan)->format('d M Y') ?? ($lap->created_at?->format('d M Y') ?? '-') }}
                            </div>
                        </div>
                        <div class="bg-white rounded-lg p-3 ring-1 ring-gray-200">
                            <div class="text-[11px] text-gray-500">Lampiran</div>
                            <div class="text-sm font-medium text-gray-900">{{ $lampiranCount }} file</div>
                        </div>
                    </div>
                    @if (!empty($lap->permasalahan))
                        <p class="mt-4 text-sm text-gray-700">
                            {{ \Illuminate\Support\Str::limit($lap->permasalahan, 220) }}
                        </p>
                    @endif
                    @if ($lap->surat_tugas_file)
                        <div class="mt-4">
                            <a href="{{ asset('storage/' . $lap->surat_tugas_file) }}" class="text-blue-600 underline"
                                target="_blank">
                                📄 Lihat Surat Tugas
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Grid konten full width --}}
        <div class="grid grid-cols-12 gap-6 w-full">
            {{-- Kolom kiri: informasi laporan --}}
            <div class="col-span-12 xl:col-span-7">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 space-y-6">
                    <h3 class="text-sm font-semibold text-gray-700 tracking-wide">INFORMASI LAPORAN</h3>

                    <div>
                        <div class="text-xs text-gray-500 mb-1">Permasalahan</div>
                        <div class="text-gray-900 text-sm whitespace-pre-line">
                            {{ $lap->permasalahan ?? '-' }}
                        </div>
                    </div>

                    @if (!empty($lap->harapan))
                        <div class="pt-4 border-t border-gray-100">
                            <div class="text-xs text-gray-500 mb-1">Harapan</div>
                            <div class="text-gray-900 text-sm whitespace-pre-line">
                                {{ $lap->harapan }}
                            </div>
                        </div>
                    @endif

                    @if ($lampiranCount > 0)
                        <div class="pt-4 border-t border-gray-100">
                            <div class="text-xs text-gray-500 mb-2">Bukti Pendukung</div>
                            <div class="grid sm:grid-cols-2 gap-2">
                                @foreach ($lampiran as $i => $path)
                                    <a href="{{ asset('storage/' . $path) }}" target="_blank"
                                        class="group flex items-center justify-between px-3 py-2 rounded-md ring-1 ring-gray-200 hover:ring-primary-300 hover:bg-primary-50">
                                        <span class="text-sm text-blue-700 group-hover:underline">Lampiran
                                            {{ $i + 1 }}</span>
                                        <span class="text-[11px] text-gray-500 truncate ml-3">{{ basename($path) }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Kolom kanan: anggota tim --}}
            <div class="col-span-12 xl:col-span-5">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-sm font-semibold text-gray-700 tracking-wide mb-4">
                        ANGGOTA TIM ({{ count($tim->anggotaAktif) }} orang)
                    </h3>

                    {{-- Ketua --}}
                    <div class="flex items-center p-4 rounded-lg bg-emerald-50 ring-1 ring-emerald-100 mb-4">
                        <div class="flex-shrink-0 bg-white p-2 rounded-full mr-3 ring-1 ring-emerald-100">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $tim->ketuaTim->nama_lengkap }}</p>
                            <p class="text-xs text-emerald-700">Ketua Tim</p>
                        </div>
                    </div>

                    {{-- Anggota lain --}}
                    @php
                        // Aman: bandingkan via ID, bukan nama
                        $ketuaId = optional($tim->ketuaTim)->user_id ?? optional($tim->ketuaTim)->id;

                        // Warna badge per role
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

                        // Ikon kecil per role (opsional)
                        $roleIcon = function ($role) {
                            switch ($role) {
                                case 'Ketua':
                                    return 'M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3zm0 2c-3.314 0-6 2.239-6 5v1h12v-1c0-2.761-2.686-5-6-5z'; // user icon
                                case 'Penanggung_Jawab':
                                    return 'M12 6v6l4 2'; // clock-ish
                                case 'Wakil_Penanggung_Jawab':
                                    return 'M5 13l4 4L19 7'; // check
                                case 'Pengendali_Teknis':
                                    return 'M12 8v8m-4-4h8'; // plus
                                default:
                                    return 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'; // default
                            }
                        };
                    @endphp

                    <div class="grid sm:grid-cols-2 gap-3">
                        @foreach ($tim->anggotaAktif as $anggota)
                            @php
                                // Ambil role dari pivot
                                $role = $anggota->pivot->role_dalam_tim ?? 'Anggota';
                                $label = str_replace('_', ' ', $role);

                                // Ambil id anggota (user_id atau id)
                                $anggotaId = $anggota->user_id ?? $anggota->id;

                                // Skip ketua bila ketua ditampilkan di tempat lain
                                if ($ketuaId && (string) $anggotaId === (string) $ketuaId) {
                                    continue;
                                }
                            @endphp

                            <div class="flex items-center p-3 bg-gray-50 rounded-lg ring-1 ring-gray-100">
                                <div class="flex-shrink-0 bg-white p-2 rounded-full mr-3 ring-1 ring-gray-200">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="{{ $roleIcon($role) }}" />
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-medium text-gray-900 truncate">
                                        {{ $anggota->nama_lengkap ?? ($anggota->name ?? '—') }}
                                    </p>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold ring-1 {{ $roleBadge($role) }}">
                                        {{ $label }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>

                {{-- Footer info --}}
                <div class="mt-6 text-sm text-gray-500">
                    Dibuat: {{ $tim->created_at?->format('d M Y, H:i') ?? '-' }} WIB
                </div>
            </div>
        </div>

    </div>
@endsection
