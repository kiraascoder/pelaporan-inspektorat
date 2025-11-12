<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Tugas</title>
    <style>
        @page {
            margin: 2.2cm 2.2cm;
        }

        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .center {
            text-align: center;
        }

        .title {
            font-weight: 700;
            font-size: 16px;
            margin: 8px 0 4px;
            text-decoration: underline;
        }

        .mb-4 {
            margin-bottom: 14px;
        }

        .mb-2 {
            margin-bottom: 8px;
        }

        .mt-2 {
            margin-top: 8px;
        }

        .mt-4 {
            margin-top: 14px;
        }

        .bold {
            font-weight: 700;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .li {
            display: flex;
        }

        .li>.no {
            width: 14px;
        }

        .li>.txt {
            flex: 1;
        }

        .tiny {
            font-size: 11px;
            color: #333;
        }

        .signature {
            width: 100%;
            margin-top: 32px;
        }

        .signature .place {
            text-align: right;
            margin-bottom: 40px;
        }

        .signature .name {
            text-align: right;
            margin-top: 60px;
            font-weight: 700;
            text-decoration: underline;
        }

        .signature .meta {
            text-align: right;
        }

        .caps {
            text-transform: uppercase;
        }

        .underline {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    {{-- KOP SINGKAT (bisa diganti gambar jika ada logo) --}}
    <div class="center">
        <div class="bold caps">PEMERINTAH KABUPATEN SIDENRENG RAPPANG</div>
        <div class="bold caps">INSPEKTORAT DAERAH</div>
        <div class="tiny">JL. Harapan Baru Blok C No.17 Komplek SKPD Sidrap 91611 – Telp (0421) 3590015</div>
    </div>
    <hr class="mb-4">

    <div class="center">
        <div class="title">SURAT TUGAS</div>
        <div class="bold">NOMOR: {{ $surat->nomor_surat }}</div>
    </div>

    {{-- DASAR --}}
    <div class="mt-4 mb-2 bold">Dasar :</div>
    <div class="mb-4">
        @foreach ($dasar as $i => $item)
            <div class="li">
                <div class="no">{{ $i + 1 }}.</div>
                <div class="txt">{{ $item }}</div>
            </div>
        @endforeach
    </div>

    {{-- MENUGASKAN --}}
    <div class="center bold mb-2 caps">MENUGASKAN :</div>

    {{-- Kepada (daftar orang & role) --}}
    <div class="mb-2 bold">Kepada :</div>
    <table class="table mb-4">
        <tbody>
            @foreach ($surat->anggota as $i => $agt)
                <tr>
                    <td style="width: 16px; vertical-align: top;">{{ $i + 1 }}.</td>
                    <td style="vertical-align: top;">
                        {{ $agt->pegawai->nama_lengkap ?? '-' }}
                    </td>
                    <td style="width: 180px; text-align:right; vertical-align: top;">
                        {{ $agt->role_dalam_tim ?? 'Anggota Tim' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- UNTUK (point dari deskripsi_umum) --}}
    <div class="bold mb-2">Untuk :</div>
    <div class="mb-4">
        @forelse($untuk as $i => $poin)
            <div class="li">
                <div class="no">{{ $i + 1 }}.</div>
                <div class="txt">{{ $poin }}</div>
            </div>
        @empty
            <div class="li">
                <div class="no">1.</div>
                <div class="txt">Melaksanakan tugas sesuai ketentuan yang berlaku.</div>
            </div>
        @endforelse
    </div>

    {{-- Paragraf biaya & integritas (sesuai contoh) --}}
    <div class="mb-4">
        Biaya kegiatan ini menjadi beban anggaran Inspektorat Daerah Kabupaten Sidenreng Rappang.
        Pegawai Inspektorat Daerah dalam melaksanakan tugas tidak menerima/meminta gratifikasi dan suap.
        Demikian surat tugas ini diberikan untuk dilaksanakan dengan penuh rasa tanggung jawab.
    </div>

    {{-- TTD --}}
    <div class="signature">
        <div class="place">
            Dikeluarkan di Pangkajene Sidenreng<br>
            Pada Tanggal {{ now()->translatedFormat('d F Y') }}
        </div>
        <div class="meta caps"><strong>{{ $surat->penandatangan->jabatan ?? 'INSPEKTUR DAERAH' }}</strong></div>

        {{-- ruang tanda tangan --}}
        <div style="height: 70px;"></div>

        <div class="name">{{ $surat->penandatangan->nama_lengkap ?? '-' }}</div>
        <div class="meta">Pangkat : {{ $surat->penandatangan->pangkat ?? '-' }}</div>
        <div class="meta">NIP : {{ $surat->penandatangan->nip ?? '-' }}</div>
    </div>

    {{-- Tembusan (opsional, jika mau tambahkan) --}}
    {{-- <div class="mt-4">
        <div class="bold">Tembusan:</div>
        <div class="li"><div class="no">1.</div><div class="txt">Bupati Sidenreng Rappang</div></div>
        <div class="li"><div class="no">2.</div><div class="txt">Kepala BKPSDM Kab. Sidenreng Rappang</div></div>
        <div class="li"><div class="no">3.</div><div class="txt">Kepala Dinas Pendidikan dan Kebudayaan Kab. Sidenreng Rappang</div></div>
    </div> --}}

</body>

</html>
 