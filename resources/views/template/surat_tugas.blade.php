@php
    use Carbon\Carbon;
    Carbon::setLocale('id');
    $dasar = is_array($surat->dasar ?? null) ? $surat->dasar : json_decode($surat->dasar ?? '[]', true);
    $anggota = is_array($surat->anggota ?? null) ? $surat->anggota : json_decode($surat->anggota ?? '[]', true);
    $untuk = is_array($surat->untuk ?? null) ? $surat->untuk : json_decode($surat->untuk ?? '[]', true);
    $tembusan = is_array($surat->tembusan ?? null) ? $surat->tembusan : json_decode($surat->tembusan ?? '[]', true);
    function tgl($d)
    {
        return $d ? \Carbon\Carbon::parse($d)->translatedFormat('d F Y') : '-';
    }
@endphp
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Surat Tugas {{ $surat->nomor_surat }}</title>
    <style>
        @page {
            margin: 2.2cm 2cm 2cm 2cm;
        }

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .kop {
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .kop img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .kop-text {
            text-align: center;
            flex: 1;
        }

        .kop-text .l1 {
            font-weight: 700;
            font-size: 16px;
            text-transform: uppercase;
            margin: 0;
        }

        .kop-text .l2 {
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            margin: 2px 0 0;
        }

        .kop-text .addr {
            font-size: 10px;
            margin-top: 4px;
        }

        .divider {
            border-top: 2px solid #000;
            margin: 8px 0 16px;
        }

        .title {
            text-align: center;
            font-weight: 700;
            text-transform: uppercase;
            margin-top: 6px;
        }

        .nomor {
            text-align: center;
            margin: 4px 0 14px;
        }

        .section {
            margin: 10px 0;
        }

        .bold {
            font-weight: 700;
        }

        .list-num {
            counter-reset: item;
            margin: 0;
            padding-left: 20px;
        }

        .list-num>li {
            counter-increment: item;
            list-style: none;
            margin: 4px 0;
        }

        .list-num>li::before {
            content: counter(item) ". ";
            margin-left: -20px;
            width: 20px;
            display: inline-block;
        }

        .dua-kolom {
            width: 100%;
            border-collapse: collapse;
        }

        .dua-kolom td {
            vertical-align: top;
            padding: 2px 0;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .isi {
            text-align: justify;
            line-height: 1.5;
        }

        .ttd {
            width: 100%;
            margin-top: 24px;
        }

        .small {
            font-size: 11px;
        }
    </style>
</head>

<body>

    {{-- KOP --}}
    <div class="kop">
        {{-- ganti ke logo asli: <img src="{{ public_path('images/logo.png') }}"> --}}
        <div style="width:70px;height:70px;border:1px solid #000;"></div>
        <div class="kop-text">
            <p class="l1">Pemerintah Kabupaten Sidenreng Rappang</p>
            <p class="l2">Inspektorat Daerah</p>
            <p class="addr">JL. Harapan Baru Blok C No.17 Kompleks SKPD Sidrap 91611 Sul-Sel<br>Telepon : (0421)
                3590015 — FAX (0421) 3590015</p>
        </div>
    </div>
    <div class="divider"></div>

    <div class="title">Surat Tugas</div>
    <div class="nomor bold">NOMOR : {{ $surat->nomor_surat }}</div>

    {{-- DASAR --}}
    <div class="section">
        <span class="bold">Dasar :</span>
        <ol class="list-num">
            @forelse($dasar as $d)
            <li>{{ $d }}</li>@empty<li>-</li>
            @endforelse
        </ol>
    </div>

    {{-- MENUGASKAN --}}
    <div class="section">
        <span class="bold">MENUGASKAN :</span>
        <table class="dua-kolom" style="margin-top:6px">
            @forelse(($anggota['nama'] ?? []) as $i => $nm)
                <tr>
                    <td style="width:24px">{{ $i + 1 }}.</td>
                    <td class="bold">{{ $nm }}</td>
                    <td style="width:12px"></td>
                    <td class="right">{{ $anggota['jabatan'][$i] ?? '' }}</td>
                </tr>
            @empty
                <tr>
                    <td></td>
                    <td>-</td>
                    <td></td>
                    <td></td>
                </tr>
            @endforelse
        </table>
    </div>

    {{-- UNTUK --}}
    <div class="section">
        <span class="bold">Untuk :</span>
        <ol class="list-num">
            @forelse($untuk as $u)
            <li class="isi">{{ $u }}</li>@empty<li>-</li>
            @endforelse
        </ol>
    </div>

    {{-- PARAGRAF BIAYA & GRATIFIKASI (sesuai contoh) --}}
    <div class="section isi">
        Biaya Kegiatan ini menjadi beban anggaran Inspektorat Daerah Kabupaten Sidenreng Rappang.
        Pegawai Inspektorat Daerah dalam melaksanakan tugas tidak menerima/meminta gratifikasi dan suap.
        Demikian surat tugas ini diberikan untuk dilaksanakan dengan penuh rasa tanggung jawab.
    </div>

    {{-- TTD --}}
    <table class="ttd">
        <tr>
            <td></td>
            <td class="right small">
                Dikeluarkan di {{ $surat->kota_terbit ?? '—' }}<br>
                Pada Tanggal {{ tgl($surat->tanggal_surat) }}<br><br>
                <span
                    class="bold">{{ $surat->jabatan_ttd ?? 'INSPEKTUR DAERAH KABUPATEN SIDENRENG RAPPANG' }}</span><br><br><br><br>
                <span class="bold">{{ $surat->nama_ttd ?? '-' }}</span><br>
                Pangkat : {{ $surat->pangkat_ttd ?? '-' }}<br>
                Nip : {{ $surat->nip_ttd ?? '-' }}
            </td>
        </tr>
    </table>

    {{-- TEMBUSAN --}}
    @if (!empty($tembusan))
        <div class="section small">
            <span class="bold">Tembusan :</span>
            <ol class="list-num">
                @foreach ($tembusan as $t)
                    <li>{{ $t }}</li>
                @endforeach
            </ol>
        </div>
    @endif

</body>

</html>
