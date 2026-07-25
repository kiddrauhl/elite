<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Kelulusan - {{ $siswa->nama_lengkap }}</title>
    <style>
        /* Setup Halaman Utama */
        @page {
            size: A4 landscape;
            margin: 0px;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0px;
            padding: 0px;
            color: #1f2937;
            background-color: #ffffff;
        }

        /* BINGKAI (Satu-satunya yang menggunakan absolute) */
        .border-outer {
            position: absolute;
            top: 25px; left: 25px; right: 25px; bottom: 25px;
            border: 8px solid #1e3a8a; /* Biru Tua */
            z-index: -2;
        }
        .border-inner {
            position: absolute;
            top: 40px; left: 40px; right: 40px; bottom: 40px;
            border: 2px solid #d4af37; /* Emas */
            z-index: -1;
        }

        /* KONTEN UTAMA */
        .content {
            position: relative;
            z-index: 1;
            text-align: center;
            width: 100%;
        }

        /* Spacer untuk mendorong konten ke bawah agar tidak menabrak bingkai */
        .top-spacer {
            height: 75px;
        }

        /* Tipografi */
        .title {
            font-size: 46px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 6px;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 16px;
            color: #64748b;
            letter-spacing: 4px;
            margin-bottom: 35px;
            font-family: 'Arial', sans-serif;
        }

        .presented-to {
            font-size: 16px;
            color: #475569;
            margin-bottom: 15px;
            font-family: 'Arial', sans-serif;
            font-style: italic;
        }

        .name {
            font-size: 44px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 5px;
        }

        .name-line {
            width: 50%;
            margin: 0 auto;
            border-bottom: 2px solid #d4af37;
            margin-bottom: 25px;
        }

        .description {
            font-size: 16px;
            line-height: 1.6;
            color: #334155;
            margin: 0 auto;
            width: 80%;
            font-family: 'Arial', sans-serif;
        }

        .level-text {
            margin-top: 25px;
            font-size: 20px;
            font-weight: bold;
            color: #d4af37;
            letter-spacing: 3px;
            text-transform: uppercase;
        }

        /* FOOTER & TANDA TANGAN (Menggunakan tabel agar tidak bergeser) */
        .footer-spacer {
            height: 40px;
        }

        table.footer-table {
            width: 85%;
            margin: 0 auto;
            font-family: 'Arial', sans-serif;
        }

        table.footer-table td {
            width: 50%;
            vertical-align: bottom;
        }

        /* Bagian Kiri (Tanggal & QR Validasi) */
        .footer-left {
            text-align: left;
        }
        .date-text {
            font-size: 14px;
            color: #1f2937;
            font-style: italic;
            margin-bottom: 15px;
        }
        .qr-verify {
            width: 65px;
            height: 65px;
        }
        .qr-label {
            font-size: 9px;
            color: #64748b;
            letter-spacing: 1px;
            margin-top: 5px;
        }

        /* Bagian Kanan (Tanda Tangan & QR TTD) */
        .footer-right {
            text-align: right;
        }
        .qr-ttd {
            width: 70px;
            height: 70px;
            margin-bottom: 5px;
        }
        .sign-line {
            border-bottom: 1px solid #1f2937;
            width: 220px;
            margin-left: auto;
            margin-bottom: 5px;
        }
        .sign-name {
            font-weight: bold;
            font-size: 15px;
            color: #0f172a;
        }
        .sign-title {
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <!-- Lapisan Bingkai -->
    <div class="border-outer"></div>
    <div class="border-inner"></div>

    <!-- Lapisan Konten -->
    <div class="content">
        <!-- Pendorong agar teks tidak kena bingkai atas -->
        <div class="top-spacer"></div>

        <div class="title">Sertifikat Kelulusan</div>
        <div class="subtitle">Elite English Course</div>

        <div class="presented-to">Dianugerahkan dengan penuh kebanggaan kepada:</div>

        <div class="name">{{ $siswa->nama_lengkap }}</div>
        <div class="name-line"></div>

        <div class="description">
            Telah memenuhi seluruh kualifikasi dan standar evaluasi akademik yang ditetapkan, serta menunjukkan dedikasi luar biasa dalam menyelesaikan program pembelajaran tingkat <strong>{{ $siswa->nama_level ?? 'Level Akademik' }}</strong> dengan pencapaian yang sangat memuaskan.
        </div>

        <div class="level-text">
            Program Tingkat {{ $siswa->nama_level ?? 'Terverifikasi' }}
        </div>

        <div class="footer-spacer"></div>

        <!-- Tabel Footer (Kiri: Verifikasi | Kanan: TTD) -->
        <table class="footer-table">
            <tr>
                <!-- KIRI -->
                <td class="footer-left">
                    <div class="date-text">Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>

                    <div>
                        <img src="data:image/svg+xml;base64, {!! base64_encode(QrCode::format('svg')->size(70)->margin(0)->generate($urlVerifikasi)) !!}" class="qr-verify">
                        <div class="qr-label">SCAN VALIDASI</div>
                    </div>
                </td>

                <!-- KANAN -->
                <td class="footer-right">
                    @php
                        $teksTtd = "Ditandatangani secara elektronik oleh: Direktur Akademik Elite English Course. Diterbitkan pada: " . \Carbon\Carbon::now()->translatedFormat('d F Y');
                    @endphp

                    <div>
                        <img src="data:image/svg+xml;base64, {!! base64_encode(QrCode::format('svg')->size(70)->margin(0)->generate($teksTtd)) !!}" class="qr-ttd">
                    </div>

                    <div class="sign-line"></div>
                    <div class="sign-name">Direktur Akademik</div>
                    <div class="sign-title">Elite English Course</div>
                </td>
            </tr>
        </table>

    </div>
</body>
</html>
