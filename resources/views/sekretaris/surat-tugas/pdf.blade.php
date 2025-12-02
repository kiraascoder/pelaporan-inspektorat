<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Tugas</title>
    <style>
        @page {
            margin: 2cm 2.2cm 2cm 2.2cm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.5;
        }

        .wrapper {
            width: 100%;
        }

        /* KOP SURAT */
        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }

        .kop-logo {
            width: 80px;
            text-align: left;
            vertical-align: top;
        }

        .kop-text {
            text-align: center;
            vertical-align: top;
        }

        .kop-text .pemda {
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-text .instansi {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-text .alamat {
            font-size: 10px;
        }

        .kop-garis {
            border-top: 2px solid #000;
            margin-top: 4px;
            margin-bottom: 8px;
        }

        /* JUDUL SURAT */
        .judul-surat {
            text-align: center;
            margin-top: 6px;
            margin-bottom: 10px;
        }

        .judul-surat .judul {
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .judul-surat .nomor {
            font-size: 11px;
            margin-top: 3px;
        }

        /* TABEL KONTEN UTAMA */
        .tabel-konten {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .tabel-konten td {
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

        .paragraf {
            text-align: justify;
            margin-top: 8px;
        }

        /* TTD */
        .ttd-table {
            width: 100%;
            margin-top: 18px;
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
    <div class="wrapper">

        {{-- KOP SURAT --}}
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    {{-- Sesuaikan path logo di public_path() --}}
                    {{-- <img src="{{ public_path('images/logo-sidrap.png') }}" width="70"> --}}
                </td>
                <td class="kop-text">
                    <div class="pemda">PEMERINTAH KABUPATEN SIDENRENG RAPPANG</div>
                    <div class="instansi">INSPEKTORAT&nbsp;DAERAH</div>
                    <div class="alamat">
                        JL. Harapan Baru Blok C No.17 Komplek SKPD Sidrap 91611 Sul-Sel<br>
                        Telepon : (0421) 3590015 &nbsp;&nbsp; FAX : (0421) 3590015
                    </div>
                </td>
            </tr>
        </table>
        <div class="kop-garis"></div>

        {{-- JUDUL --}}
        <div class="judul-surat">
            <div class="judul">SURAT TUGAS</div>
            <div class="nomor">
                NOMOR : {{ $pengajuan->nomor_surat ?? '700.1 / 62 / Inspektorat' }}
            </div>
        </div>

        {{-- DASAR / KEPADA / UNTUK --}}
        <table class="tabel-konten">
            {{-- DASAR --}}
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
        <table class="tabel-konten">
            <tr>
                <td class="kolom-label">Kepada</td>
                <td class="kolom-titik">:</td>
                <td>
                    @php
                        $namaDitugaskan = is_array($pengajuan->nama_ditugaskan) ? $pengajuan->nama_ditugaskan : [];

                        // urutan keterangan jabatan di sisi kanan
                        $roleMap = [
                            0 => 'Penanggung jawab',
                            1 => 'Wakil Penanggung jawab',
                            2 => 'Pengendali Teknis',
                            3 => 'Ketua Tim',
                        ];
                    @endphp

                    @if (count($namaDitugaskan))
                        <table style="width:100%; border-collapse:collapse;">
                            @foreach ($namaDitugaskan as $i => $nama)
                                <tr>
                                    <td style="width:18px; vertical-align:top;">{{ $i + 1 }}.</td>
                                    <td style="vertical-align:top;">{{ $nama }}</td>
                                    <td style="width:190px; text-align:right; font-size:10px;">
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

        {{-- PARAGRAF BAWAH --}}
        <p class="paragraf">
            Biaya kegiatan ini menjadi beban anggaran Inspektorat Daerah Kabupaten Sidenreng Rappang.
            Pegawai Inspektorat Daerah dalam melaksanakan tugas tidak menerima/meminta gratifikasi dan suap.
        </p>

        <p class="paragraf">
            Demikian surat tugas ini diberikan untuk dilaksanakan dengan penuh rasa tanggung jawab.
        </p>

        {{-- TANDA TANGAN --}}
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

        {{-- TEMBUSAN --}}
        <div class="tembusan-title">
            Tembusan :
        </div>
        <div class="tembusan-list">
            1. Bupati Sidenreng Rappang;<br>
            2. Kepala BKPSDM Kab. Sidenreng Rappang;<br>
            3. Kepala Kelurahan Sidenreng;
        </div>

    </div>
</body>

</html>
