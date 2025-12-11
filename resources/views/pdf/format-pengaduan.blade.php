<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Formulir Pengaduan - Cetak (Final)</title>
    <style>
        /* ========== PAGE & ROOT ========== */
        @page {
            size: A4;
            margin: 6mm 8mm;
            /* updated: lebih rapat agar muat 1 halaman */
        }

        :root {
            --text: #111;
            --border: #000;
            --base-font: 13px;
            /* font dasar tabel */
            --header-font: 14px;
            /* font header tabel */
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #fff;
            font-family: "Times New Roman", Times, serif;
            color: var(--text);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ========== SHEET ========== */
        .sheet {
            /* sedikit lebih lebar agar area isi maksimal */
            width: 175mm;
            /* masih aman untuk A4 dengan margin di atas */
            margin: 4mm auto;
            /* ruang sisi diperkecil */
            background: #fff;
            padding: 6mm 6mm;
            /* padding diperkecil */
            overflow: visible;
        }

        /* ========== HEADER ATAS ========== */
        .top-block {
            text-align: center;
            line-height: 1.0;
            margin-bottom: 6px;
        }

        .top-block h2 {
            margin: 0;
            font-size: 13px;
            font-weight: 700;
        }

        .top-block h1 {
            margin: 6px 0 0 0;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.6px;
        }

        .top-meta {
            font-size: 11px;
            margin-top: 6px;
        }

        .meta-row {
            font-size: 13px;
            margin-top: 8px;
        }

        /* ========== TABEL FORM ========== */
        .form-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            table-layout: fixed;
            word-break: break-word;
            font-size: var(--base-font);
        }

        .form-table td,
        .form-table th {
            border: 1px solid var(--border);
            padding: 8px 10px;
            /* padding sedikit diperbesar untuk estetika */
            vertical-align: top;
        }

        .col-label {
            width: 34%;
            font-weight: 700;
            padding-left: 8px;
        }

        .col-colon {
            width: 4%;
            text-align: center;
            font-weight: 700;
        }

        .col-value {
            width: 62%;
        }

        /* SECTION HEADER */
        .section-row td {
            padding: 0;
        }

        .section-header-cell {
            padding: 10px 6px;
            /* agak tinggi agar menonjol */
            text-align: center;
            /* updated: center */
            font-size: var(--header-font);
            font-weight: 700;
            text-transform: uppercase;
            border-top: 2px solid var(--border);
            border-bottom: 2px solid var(--border);
            background: #e9e9e9;
        }

        /* ukuran baris */
        .smallbox {
            min-height: 40px;
            /* baris data lebih tinggi */
        }

        /* substansi pengaduan = sangat tinggi */
        .bigbox {
            min-height: 220px;
            /* dibuat lebih tinggi, tapi hati2; bisa dikecilkan jika masih membuat pagination */
        }

        /* space setelah form (bisa dikurangi jika masih tidak muat) */
        .after-form-space {
            height: 4mm;
        }

        /* ========== TANDA TANGAN ========== */
        .sign-table {
            width: 100%;
            margin-top: 10mm;
            /* diperpendek agar tanda tangan tidak turun ke halaman 2 */
            border-collapse: collapse;
            font-size: 12px;
        }

        .sign-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding-top: 4mm;
        }

        .sign-blank {
            display: block;
            height: 20mm;
            /* ruang untuk tanda tangan; turunkan jika masih mepet */
        }

        .sign-caption {
            margin-top: 4px;
        }

        /* hindari page break di tengah tabel */
        .no-break,
        .no-break tr,
        .no-break td {
            break-inside: avoid;
            page-break-inside: avoid;
            -webkit-column-break-inside: avoid;
            -webkit-page-break-inside: avoid;
        }

        @media print {
            body {
                background: #fff;
            }

            .sheet {
                margin: 0;
                padding: 6mm 6mm;
                width: auto;
            }
        }
    </style>
</head>

<body>
    <div class="sheet" id="content">
        <div class="top-block">
            <h2>LAMPIRAN I</h2>
            <h1>PERATURAN BUPATI SIDENRENG RAPPANG</h1>
            <div class="top-meta">
                NOMOR 19 TAHUN 2024<br />
                TENTANG PEDOMAN PENANGANAN PENGADUAN MASYARAKAT DI LINGKUNGAN INSPEKTORAT DAERAH
            </div>
        </div>

        <div class="meta-row">
            <div>No Pengaduan : </div>
            <div>Tanggal Pengaduan : </div>
        </div>

        <table class="form-table no-break" role="table" aria-label="Formulir Pengaduan">
            <tr class="section-row">
                <td colspan="3" class="section-header-cell">FORMAT FORMULIR PENGADUAN</td>
            </tr>

            <tr class="section-row">
                <td colspan="3" class="section-header-cell">DATA PELAPOR</td>
            </tr>

            <tr>
                <td class="col-label">Nama</td>
                <td class="col-colon">:</td>
                <td class="col-value smallbox"></td>
            </tr>

            <tr>
                <td class="col-label">Alamat</td>
                <td class="col-colon">:</td>
                <td class="col-value smallbox"></td>
            </tr>

            <tr>
                <td class="col-label">Pekerjaan / Jabatan</td>
                <td class="col-colon">:</td>
                <td class="col-value smallbox"></td>
            </tr>

            <tr>
                <td class="col-label">No. Telp / HP yang dapat dihubungi</td>
                <td class="col-colon">:</td>
                <td class="col-value smallbox"></td>
            </tr>

            <tr class="section-row">
                <td colspan="3" class="section-header-cell">DATA TERLAPOR</td>
            </tr>

            <tr>
                <td class="col-label">Nama</td>
                <td class="col-colon">:</td>
                <td class="col-value smallbox"></td>
            </tr>

            <tr>
                <td class="col-label">Alamat</td>
                <td class="col-colon">:</td>
                <td class="col-value smallbox"></td>
            </tr>

            <tr>
                <td class="col-label">Pekerjaan / Jabatan</td>
                <td class="col-colon">:</td>
                <td class="col-value smallbox"></td>
            </tr>

            <tr>
                <td class="col-label">No. Telp / HP</td>
                <td class="col-colon">:</td>
                <td class="col-value smallbox"></td>
            </tr>

            <tr class="section-row">
                <td colspan="3" class="section-header-cell">SUBSTANSI PENGADUAN</td>
            </tr>

            <tr>
                <td class="col-label">Permasalahan yang diadukan</td>
                <td class="col-colon">:</td>
                <td class="col-value bigbox"></td>
            </tr>

            <tr>
                <td class="col-label">Bukti pendukung pengaduan</td>
                <td class="col-colon">:</td>
                <td class="col-value bigbox"></td>
            </tr>

            <tr>
                <td class="col-label">Harapan</td>
                <td class="col-colon">:</td>
                <td class="col-value bigbox"></td>
            </tr>
        </table>

        <div class="after-form-space"></div>

        <div style="text-align:center; font-size:12px; margin-bottom:6px;">Pangkajene Sidenreng</div>

        <table class="sign-table" aria-label="Tanda Tangan">
            <tr>
                <td>
                    Pelapor
                    <div class="sign-blank"></div>
                    <div class="sign-caption">(__________________)</div>
                </td>
                <td>
                    Penerima
                    <div class="sign-blank"></div>
                    <div class="sign-caption">(__________________)</div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
