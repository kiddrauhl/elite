<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $pembayaran->order_id }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.5;
        }
        .invoice-box {
            max-width: 100%;
            padding: 15px;
        }

        /* Layout Header menggunakan Table agar aman di DomPDF */
        .header-table { width: 100%; margin-bottom: 10px; }
        .header-table td { vertical-align: top; }

        /* Bagian Kiri (Logo & Info Perusahaan) */
        .logo { width: 140px; margin-bottom: 15px; }
        .company-name { font-size: 18px; font-weight: bold; color: #1e3a8a; margin: 0 0 5px 0; }
        .company-address { font-size: 12px; color: #555; line-height: 1.4; }

        /* Bagian Kanan (Judul Invoice & Meta) */
        .invoice-title { font-size: 32px; font-weight: 900; color: #1e3a8a; text-transform: uppercase; margin: 0 0 5px 0; letter-spacing: 2px; }
        .invoice-meta { font-size: 12px; color: #555; margin: 0; }
        .invoice-meta strong { color: #1e3a8a; }

        /* Garis Pemisah Elegan (Biru & Amber) */
        .separator { border-top: 3px solid #1e3a8a; border-bottom: 1px solid #f59e0b; padding-top: 2px; margin: 25px 0; }

        /* Informasi Klien */
        .client-info { width: 100%; margin-bottom: 30px; }
        .client-info td { vertical-align: top; }
        .client-title { font-size: 11px; font-weight: bold; color: #999; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px; }
        .client-details { font-size: 14px; color: #333; line-height: 1.6; }
        .client-details strong { color: #1e3a8a; font-size: 16px; }

        /* Tabel Rincian Pembayaran */
        .details-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .details-table th, .details-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .details-table th { background-color: #1e3a8a; color: #ffffff; font-weight: bold; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }
        .details-table tbody tr:nth-child(even) { background-color: #f8fafc; }

        /* Bagian Total & Lunas */
        .total-wrapper { width: 100%; }
        .total-wrapper td { vertical-align: top; }
        .total-table { width: 100%; border-collapse: collapse; }
        .total-table td { padding: 10px 15px; border-bottom: 1px solid #e2e8f0; }
        .total-table .total-row td { font-weight: 900; color: #1e3a8a; font-size: 18px; border-top: 2px solid #1e3a8a; border-bottom: none; background-color: #eff6ff; }

        /* Stempel Lunas */
        .status-stamp {
            display: inline-block;
            padding: 10px 25px;
            background-color: #ecfdf5;
            color: #059669;
            border: 3px solid #059669;
            font-size: 20px;
            font-weight: 900;
            text-transform: uppercase;
            border-radius: 8px;
            transform: rotate(-15deg);
            margin-top: 20px;
            margin-left: 30px;
            opacity: 0.8;
        }

        /* Footer */
        .footer { text-align: center; margin-top: 60px; font-size: 11px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 20px; line-height: 1.5; }
    </style>
</head>
<body>
    <div class="invoice-box">

        <!-- HEADER: Logo & Invoice Text -->
        <table class="header-table">
            <tr>
                <td width="50%">
                    <!-- 🌟 PERHATIAN: Gunakan public_path() agar DomPDF bisa membaca gambar lokal -->
                    <img src="{{ public_path('images/elite.png') }}" alt="Elite English Logo" class="logo">
                    <p class="company-name">Elite English Course</p>
                    <p class="company-address">
                        Jl. Ahmad Yani Km. 3,5<br>
                        Banjarmasin Timur, Kalimantan Selatan 70234<br>
                        Telp: (0511) 6790378<br>
                        Email: info@eliteenglish.id
                    </p>
                </td>
                <td width="50%" style="text-align: right;">
                    <h1 class="invoice-title">Invoice</h1>
                    <p class="invoice-meta">
                        <strong>No. Pesanan:</strong> {{ $pembayaran->order_id }}<br>
                        <strong>Tanggal:</strong> {{ date('d F Y', strtotime($pembayaran->tanggal_pembayaran ?? now())) }}<br>
                        <strong>Metode:</strong> {{ strtoupper(str_replace('_', ' ', $pembayaran->tipe_pembayaran ?? 'Midtrans')) }}
                    </p>
                </td>
            </tr>
        </table>

        <!-- GARIS PEMISAH -->
        <div class="separator"></div>

        <!-- INFO KLIEN -->
        <table class="client-info">
            <tr>
                <td width="60%">
                    <div class="client-title">Ditagihkan Kepada:</div>
                    <div class="client-details">
                        <strong>{{ $pendaftar->nama_lengkap ?? ($pendaftar->nama ?? 'Siswa Elite') }}</strong><br>
                        No. Registrasi: REG-{{ str_pad($pendaftar->id_pendaftar ?? 0, 5, '0', STR_PAD_LEFT) }}<br>
                        Email: {{ $pendaftar->email ?? '-' }}
                    </div>
                </td>
                <td width="40%" style="text-align: right;">
                    <div class="client-title">Status Pembayaran:</div>
                    <div style="font-weight: bold; color: #059669; font-size: 16px;">PAID / BERHASIL</div>
                </td>
            </tr>
        </table>

        <!-- TABEL RINCIAN -->
        <table class="details-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="65%">Deskripsi Tagihan</th>
                    <th width="30%" style="text-align: right;">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>
                        <strong>Biaya Administrasi & Program Kelas</strong><br>
                        <span style="font-size: 11px; color: #666;">Termasuk biaya modul, buku panduan, dan akses fasilitas fasilitas bimbingan belajar Elite Academy.</span>
                    </td>
                    <td style="text-align: right;">{{ number_format($pembayaran->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <!-- BAGIAN TOTAL & STEMPEL -->
        <table class="total-wrapper">
            <tr>
                <td width="60%" style="text-align: left;">
                    <div class="status-stamp">LUNAS</div>
                </td>
                <td width="40%">
                    <table class="total-table">
                        <tr>
                            <td>Subtotal</td>
                            <td style="text-align: right;">Rp {{ number_format($pembayaran->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Biaya Admin / Pajak</td>
                            <td style="text-align: right;">Rp 0</td>
                        </tr>
                        <tr class="total-row">
                            <td>TOTAL</td>
                            <td style="text-align: right;">Rp {{ number_format($pembayaran->jumlah_bayar ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- FOOTER -->
        <div class="footer">
            <p><strong>Terima kasih atas kepercayaan Anda memilih Elite English Course.</strong><br>
            Simpan kuitansi ini sebagai referensi Anda.</p>
        </div>
    </div>
</body>
</html>
