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

        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 11px;
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
            font-size: 10px;
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
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .judul-surat .nomor {
            font-size: 11px;
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
            font-size: 10px;
        }

        .tembusan-list {
            font-size: 10px;
            margin-top: 2px;
        }
    </style>
</head>

<body>
    {{-- KOP SEDERHANA --}}
    <div class="kop">
        <div class="pemda">PEMERINTAH KABUPATEN SIDENRENG RAPPANG</div>
        <div class="instansi">INSPEKTORAT DAERAH</div>
        <div class="alamat">
            JL. Harapan Baru Blok C No.17 Komplek SKPD Sidrap 91611 Sul-Sel<br>
            Telepon : (0421) 3590015 &nbsp;&nbsp; FAX : (0421) 3590015
        </div>
    </div>
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
                    $namaDitugaskan = $pengajuan->nama_ditugaskan ?? [];
                    $roleMap = [
                        0 => 'Penanggung jawab',
                        1 => 'Wakil Penanggung jawab',
                        2 => 'Pengendali Teknis',
                        3 => 'Ketua Tim',
                    ];
                @endphp

                @if (is_array($namaDitugaskan) && count($namaDitugaskan))
                    <table class="simple">
                        @foreach ($namaDitugaskan as $i => $nama)
                            <tr>
                                <td style="width: 18px;">{{ $i + 1 }}.</td>
                                <td>{{ $nama }}</td>
                                <td style="width: 190px; text-align:right; font-size:10px;">
                                    {{ $roleMap[$i] ?? 'Anggota Tim' }}
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
                        <li>[Belum ada uraian tugas, isi melalui deskripsi_umum pengajuan]</li>
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

    {{-- TTD --}}
    <table class="ttd-table">
        <tr>
            <td style="width:50%;"></td>
            <td style="width:50%; text-align:left;">
                @php
                    $tgl = \Carbon\Carbon::parse($pengajuan->created_at ?? now())->translatedFormat('d F Y');
                @endphp
                Dikeluarkan di Pangkajene Sidenreng<br>
                Pada Tanggal {{ $tgl }}<br><br>
                INSPEKTUR DAERAH<br>
                KABUPATEN SIDENRENG RAPPANG<br><br><br><br>

                <u>{{ $pengajuan->penandatangan->nama_lengkap ?? 'Drs. MUSTARI KADIR, M.Si.' }}</u><br>
                Pangkat : {{ $pengajuan->penandatangan->jabatan ?? 'Pembina Utama Muda' }}<br>
                Nip : {{ $pengajuan->penandatangan->nip ?? '19680119 199112 1 002' }}
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
