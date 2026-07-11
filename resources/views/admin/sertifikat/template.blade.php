<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat Kelulusan - {{ $siswa->nama_lengkap }}</title>
    <style>
        /* Menghilangkan margin bawaan kertas PDF */
        @page { margin: 0; }

        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #1f2937;
        }

        /* Bingkai Biru Tua (Luar) */
        .background {
            position: absolute;
            top: 25px;
            left: 25px;
            right: 25px;
            bottom: 25px;
            border: 12px solid #1e3a8a;
            background-color: #fafbfc;
            z-index: -1;
        }

        /* Bingkai Emas (Dalam) */
        .inner-border {
            position: absolute;
            top: 45px;
            left: 45px;
            right: 45px;
            bottom: 45px;
            border: 2px solid #d4af37;
            z-index: -1;
        }

        .content {
            padding: 80px 60px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        /* Pengaturan Logo */
        .logo {
            width: 140px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 50px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 5px;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 20px;
            color: #64748b;
            letter-spacing: 3px;
            margin-bottom: 45px;
            font-family: 'Arial', sans-serif;
            text-transform: uppercase;
        }

        .presented-to {
            font-size: 16px;
            color: #475569;
            margin-bottom: 25px;
            font-family: 'Arial', sans-serif;
            font-style: italic;
        }

        .name {
            font-size: 48px;
            font-weight: bold;
            color: #0f172a;
            display: block;
            margin-bottom: 5px;
        }

        .name-underline {
            width: 400px;
            border-bottom: 2px solid #d4af37;
            margin: 0 auto 35px auto;
        }

        .description {
            font-size: 17px;
            line-height: 1.7;
            color: #334155;
            margin: 0 auto;
            width: 85%;
            font-family: 'Arial', sans-serif;
        }

        .level-badge {
            display: block;
            margin: 30px auto 10px auto;
            font-size: 22px;
            font-weight: bold;
            color: #d4af37;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        table.footer {
            width: 100%;
            margin-top: 50px;
            font-family: 'Arial', sans-serif;
        }

        table.footer td {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
        }

        .date {
            font-size: 16px;
            color: #1f2937;
            font-style: italic;
        }

        .signature-line {
            width: 220px;
            border-bottom: 1px solid #1f2937;
            margin: 50px auto 10px auto;
        }

        .signature-name {
            font-weight: bold;
            font-size: 16px;
            color: #0f172a;
        }

        .signature-title {
            font-size: 13px;
            color: #64748b;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <div class="background"></div>
    <div class="inner-border"></div>

    <div class="content">
        <img src="{{ public_path('images/elite.png') }}" class="logo" alt="Logo Elite English Course">

        <div class="title">Sertifikat Kelulusan</div>
        <div class="subtitle">Elite English Course</div>

        <div class="presented-to">Dianugerahkan dengan penuh kebanggaan kepada:</div>

        <div class="name">{{ $siswa->nama_lengkap }}</div>
        <div class="name-underline"></div>

        <div class="description">
            Telah memenuhi seluruh kualifikasi dan standar evaluasi akademik yang ditetapkan, serta menunjukkan dedikasi luar biasa dalam menyelesaikan program pembelajaran tingkat akhir dengan pencapaian yang sangat memuaskan.
        </div>

        <div class="level-badge">
            Program Tingkat Expert
        </div>

        <table class="footer">
            <tr>
                <td>
                    <div class="date">Banjarmasin, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                </td>
                <td>
                    <div class="signature-line"></div>
                    <div class="signature-name">Direktur Akademik</div>
                    <div class="signature-title">Elite English Course</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
