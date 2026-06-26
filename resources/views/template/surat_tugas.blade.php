<html>

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 2cm;
        }

        * {
            box-sizing: border-box;
        }

        @font-face {
            font-family: 'TimesNewRoman';
            src: url("{{ storage_path('fonts/times.ttf') }}") format('truetype');
            font-weight: normal;
        }

        @font-face {
            font-family: 'TimesNewRoman';
            src: url("{{ storage_path('fonts/timesbd.ttf') }}") format('truetype');
            font-weight: bold;
        }

        body {
            font-family: "Times-Roman", serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }

        h1,
        h2,
        h3,
        h4 {
            margin: 0;
            padding: 0;
        }

        .kop {
            text-align: center;
            margin-bottom: 10px;
        }

        .kop .pemda {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop .instansi {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop .alamat {
            font-size: 12px;
        }

        .garis {
            border-top: 2px solid #000;
            margin: 6px 0 10px 0;
        }

        .judul-surat {
            text-align: center;
            margin-bottom: 12px;
        }

        .judul-surat .judul {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .judul-surat .nomor {
            font-size: 12px;
            margin-top: 4px;
        }

        .paragraf {
            text-align: justify;
            margin-top: 8px;
        }

        table.simple {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        table.simple td {
            vertical-align: top;
        }

        .kolom-label {
            width: 55px;
        }

        .kolom-titik {
            width: 10px;
        }

        ol {
            margin: 0;
            padding-left: 18px;
        }

        .center-bold {
            text-align: center;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 6px;
        }

        .ttd-table {
            width: 100%;
            margin-top: 20px;
        }

        .ttd-table td {
            vertical-align: top;
        }

        .tembusan-title {
            margin-top: 14px;
            font-size: 12px;
        }

        .tembusan-list {
            font-size: 12px;
            margin-top: 2px;
        }
    </style>
</head>

<body>
    {{-- KOP DENGAN LOGO --}}
    <table style="width:100%; margin-bottom:6px;">
        <tr>
            <td style="width:80px; text-align:left; vertical-align:middle;">
                @if (!empty($logoBase64))
                    <img src="data:image/png;base64,{{ $logoBase64 }}" style="width:70px;">
                @endif
            </td>
            <td style="text-align:center;">
                <div class="pemda">PEMERINTAH KABUPATEN SIDENRENG RAPPANG</div>
                <div class="instansi">INSPEKTORAT DAERAH</div>
                <div class="alamat">
                    JL. Harapan Baru Blok C No.17 Komplek SKPD Sidrap 91611 Sul-Sel<br>
                    Telepon : (0421) 3590015 &nbsp;&nbsp; FAX : (0421) 3590015
                </div>
            </td>
            <td style="width:80px;"></td>
        </tr>
    </table>

    <div class="garis"></div>

    {{-- JUDUL --}}
    <div class="judul-surat">
        <div class="judul">SURAT TUGAS</div>
        <div class="nomor">
            NOMOR : {{ $pengajuan->nomor_surat ?? '700.1 / 62 / Inspektorat' }}
        </div>
    </div>

    {{-- DASAR --}}
    <table class="simple">
        <tr>
            <td class="kolom-label">Dasar</td>
            <td class="kolom-titik">:</td>
            <td>
                <ol>
                    <li>
                        Peraturan Pemerintah Nomor 12 Tahun 2017 Tentang Pembinaan dan
                        Pengawasan Penyelenggaraan Pemerintahan Daerah.
                    </li>
                    <li>
                        Program Kerja Pengawasan Tahunan Inspektorat Daerah Kabupaten
                        Sidenreng Rappang Tahun {{ now()->format('Y') }}.
                    </li>
                </ol>
            </td>
        </tr>
    </table>

    <div class="center-bold">M E N U G A S K A N :</div>

    {{-- KEPADA --}}
    <table class="simple">
        <tr>
            <td class="kolom-label">Kepada</td>
            <td class="kolom-titik">:</td>
            <td>
                @php
                    $namaDitugaskanRaw = $pengajuan->nama_ditugaskan ?? [];

                    if (is_string($namaDitugaskanRaw)) {
                        $decoded = json_decode($namaDitugaskanRaw, true);
                        $namaDitugaskan = is_array($decoded) ? $decoded : [$namaDitugaskanRaw];
                    } elseif (is_null($namaDitugaskanRaw)) {
                        $namaDitugaskan = [];
                    } else {
                        $namaDitugaskan = is_array($namaDitugaskanRaw) ? $namaDitugaskanRaw : [$namaDitugaskanRaw];
                    }

                    $roleMap = [
                        0 => 'Penanggung Jawab',
                        1 => 'Wakil Penanggung Jawab',
                        2 => 'Pengendali Teknis',
                        3 => 'Ketua Tim',
                    ];

                    $getNama = function ($item) {
                        if (is_array($item)) {
                            if (!empty($item['nama'])) {
                                return $item['nama'];
                            }
                            return $item[0] ?? '[Tidak Diketahui]';
                        } elseif (is_object($item)) {
                            return $item->nama ?? '[Tidak Diketahui]';
                        }

                        return (string) $item;
                    };

                    $getJabatan = function ($item) {
                        if (is_array($item) && !empty($item['jabatan'])) {
                            return $item['jabatan'];
                        }
                        if (is_object($item) && !empty($item->jabatan)) {
                            return $item->jabatan;
                        }
                        return null;
                    };
                @endphp

                @if (count($namaDitugaskan))
                    <table class="simple">
                        @foreach ($namaDitugaskan as $i => $item)
                            @php
                                $displayName = $getNama($item);
                                $jabatanItem = $getJabatan($item);
                                $roleLabel = $roleMap[$i] ?? 'Anggota Tim';
                            @endphp
                            <tr>
                                <td style="width:18px;">{{ $i + 1 }}.</td>
                                <td>{{ $displayName }}</td>
                                <td style="width:190px; text-align:right; font-size:12px;">
                                    {{ $jabatanItem ?? $roleLabel }}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <p>[Belum ada nama yang ditugaskan]</p>
                @endif
            </td>
        </tr>

        {{-- UNTUK --}}
        <tr>
            <td class="kolom-label" style="padding-top:6px;">Untuk</td>
            <td class="kolom-titik" style="padding-top:6px;">:</td>
            <td style="padding-top:6px;">
                @php
                    $deskripsiLines = preg_split("/\r\n|\n|\r/", $pengajuan->deskripsi_umum ?? '');
                    $deskripsiLines = array_filter($deskripsiLines, fn($line) => trim($line) !== '');
                @endphp

                <ol>
                    @if (!empty($deskripsiLines))
                        @foreach ($deskripsiLines as $line)
                            <li>{{ $line }}</li>
                        @endforeach
                    @else
                        <li>[Belum ada uraian tugas, isi melalui deskripsi_umum]</li>
                    @endif
                </ol>
            </td>
        </tr>
    </table>

    <p class="paragraf">
        Biaya kegiatan ini menjadi beban anggaran Inspektorat Daerah Kabupaten Sidenreng Rappang.
        Pegawai Inspektorat Daerah dalam melaksanakan tugas tidak menerima/meminta gratifikasi dan suap.
    </p>

    <p class="paragraf">
        Demikian surat tugas ini diberikan untuk dilaksanakan dengan penuh rasa tanggung jawab.
    </p>

    @php
        $tgl = \Carbon\Carbon::parse($pengajuan->created_at ?? now())->translatedFormat('d F Y');

        $jabatanTtd = optional($pengajuan->penandatangan)->jabatan ?? '[Jabatan penandatangan belum diatur]';
        $namaTtd = optional($pengajuan->penandatangan)->nama ?? '[Penandatangan belum dipilih]';
        $pangkatTtd = optional($pengajuan->penandatangan)->pangkat ?? '-';
        $nipTtd = optional($pengajuan->penandatangan)->nip ?? '-';

        $ttdFile = optional($pengajuan->penandatangan)->ttd_image;
        $ttdImagePath = null;
        $ttdBase64 = null;
        $ttdMime = 'image/png';

        if (!empty($ttdFile)) {
            $ttdFile = ltrim($ttdFile, '/');
            $ttdImagePath = storage_path('app/public/' . $ttdFile);

            if (file_exists($ttdImagePath)) {
                $ext = strtolower(pathinfo($ttdImagePath, PATHINFO_EXTENSION));
                $ttdMime = in_array($ext, ['jpg', 'jpeg']) ? 'image/jpeg' : 'image/png';
                $ttdBase64 = base64_encode(file_get_contents($ttdImagePath));
            }
        }
    @endphp

    <table class="ttd-table">
        <tr>
            <td style="width:50%;"></td>
            <td style="width:50%; text-align:left;">
                Dikeluarkan di Pangkajene Sidenreng<br>
                Pada Tanggal {{ $tgl }}<br><br>

                {{ strtoupper($jabatanTtd) }}<br>
                KABUPATEN SIDENRENG RAPPANG<br><br>

                @if ($ttdBase64)
                    <img src="data:{{ $ttdMime }};base64,{{ $ttdBase64 }}"
                        style="height:90px; max-width:200px;"><br>
                @else
                    <div style="height:80px;"></div>
                @endif

                <u>{{ $namaTtd }}</u><br>
                Pangkat : {{ $pangkatTtd }}<br>
                Nip : {{ $nipTtd }}
            </td>
        </tr>
    </table>

    <div class="tembusan-title">
        Tembusan :
    </div>
    <div class="tembusan-list">
        1. Bupati Sidenreng Rappang;<br>
        2. Kepala BKPSDM Kab. Sidenreng Rappang;<br>
        3. Kepala Kelurahan Sidenreng;
    </div>
</body>

</html>
