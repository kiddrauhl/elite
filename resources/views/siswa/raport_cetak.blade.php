<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raport | {{ $raport->nama_lengkap }} - {{ $raport->nama_kelas }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,400;0,700;0,900;1,400&family=Inter:wght@400;500;600;700&display=swap');

        body {
            background: #cbd5e1;
            font-family: 'Inter', sans-serif;
            color: #0f172a;
        }

        /* Layout Kertas A4 */
        .a4-kertas {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 40px auto;
            padding: 20mm;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            position: relative;
            overflow: hidden; /* Untuk watermark */
        }

        .font-serif { font-family: 'Merriweather', serif; }

        /* Pengaturan Border Tabel Formal */
        .table-raport { width: 100%; border-collapse: collapse; }
        .table-raport th, .table-raport td {
            border: 1px solid #1e293b;
            padding: 10px 14px;
        }

        /* Watermark Background */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.05; /* Mengatur tingkat kesamaran */
            width: 450px;
            z-index: 1; /* Naikkan sedikit z-index nya */
            pointer-events: none;
        }

        /* Area Konten Utama agar berada di atas watermark */
        .content-area {
            position: relative;
            z-index: 10;
        }

        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .a4-kertas { margin: 0; box-shadow: none; padding: 15mm; width: 100%; height: 100%; }
            .no-print { display: none !important; }
            @page { size: A4; margin: 0; }
        }
    </style>
</head>
<body>

    <div class="text-center pt-6 pb-2 no-print space-x-4">
        <button onclick="window.close()" class="px-6 py-2.5 bg-slate-700 text-white font-bold rounded-lg hover:bg-slate-800 transition-colors text-sm">
            Tutup Tab
        </button>
        <button onclick="window.print()" class="px-6 py-2.5 bg-blue-700 text-white font-bold rounded-lg hover:bg-blue-800 transition-colors text-sm shadow-lg">
            <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Dokumen
        </button>
    </div>

    <div class="a4-kertas">

        <img src="{{ asset('images/elite.png') }}" class="watermark" onerror="this.style.display='none'">

        <div class="content-area">
            <div class="flex items-center justify-between pb-4">
                <div class="w-24 flex-shrink-0">
                    <img src="{{ asset('images/elite.png') }}" alt="Logo Institusi" class="w-full object-contain" onerror="this.src='https://placehold.co/300x300/1e3a8a/ffffff?text=LOGO\nELITE'">
                </div>

                <div class="text-center flex-1 px-4">
                    <h1 class="text-3xl font-black text-slate-900 font-serif uppercase tracking-widest mb-1">Elite English Course</h1>
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-widest mb-1">Pelatihan Bimbingan Belajar Bahasa Inggris</p>
                    <p class="text-[11px] text-slate-600">Jl. Ahmad Yani Km. 3,5 Banjarmasin Timur, Kalimantan Selatan, Kode Pos 70234</p>
                    <p class="text-[11px] text-slate-600">Telp: (0511) 6790378 | Web: eliteenglish.my.id</p>
                </div>

                <div class="w-24 flex-shrink-0"></div> </div>

            <div class="border-b-[3px] border-slate-900 border-double mb-8"></div>

            <div class="text-center mb-8">
                <h2 class="text-xl font-bold font-serif text-slate-900 uppercase tracking-widest underline underline-offset-4">Laporan Hasil Evaluasi Belajar</h2>
                <p class="text-xs font-semibold text-slate-500 mt-1 uppercase tracking-widest">Academic Progress Report</p>
            </div>

            <div class="grid grid-cols-2 gap-x-8 mb-8 text-[13px]">
                <table class="w-full">
                    <tr>
                        <td class="w-36 py-1 font-semibold text-slate-600">Nama Peserta Didik</td>
                        <td class="w-4 text-center">:</td>
                        <td class="font-bold uppercase text-slate-900">{{ $raport->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 font-semibold text-slate-600">Nomor Induk Siswa</td>
                        <td class="text-center">:</td>
                        <td class="font-bold text-slate-900">{{ str_pad($siswa->id_siswa, 4, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                </table>
                <table class="w-full">
                    <tr>
                        <td class="w-32 py-1 font-semibold text-slate-600">Program Kelas</td>
                        <td class="w-4 text-center">:</td>
                        <td class="font-bold uppercase text-slate-900">{{ $raport->nama_kelas }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 font-semibold text-slate-600">Tingkat Level</td>
                        <td class="text-center">:</td>
                        <td class="font-bold uppercase text-slate-900">{{ $raport->nama_level }}</td>
                    </tr>
                </table>
            </div>

            <table class="table-raport text-[13px] mb-8">
                <thead>
                    <tr class="bg-slate-100/80">
                        <th class="w-12 text-center font-bold font-serif tracking-wider">NO</th>
                        <th class="text-left font-bold font-serif tracking-wider">KOMPONEN PENILAIAN AKADEMIK</th>
                        <th class="w-28 text-center font-bold font-serif tracking-wider">SKOR NILAI</th>
                        <th class="w-44 text-center font-bold font-serif tracking-wider">PREDIKAT AKHIR</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $komponen = [
                            ['nama' => 'Speaking (Berbicara & Komunikasi)', 'nilai' => $raport->nilai_speaking],
                            ['nama' => 'Listening (Menyimak Audio)', 'nilai' => $raport->nilai_listening],
                            ['nama' => 'Reading (Pemahaman Teks Berbahasa)', 'nilai' => $raport->nilai_reading],
                            ['nama' => 'Writing (Penulisan Struktur Tata Bahasa)', 'nilai' => $raport->nilai_writing],
                        ];

                        function getPredikat($skor) {
                            if($skor >= 85) return 'A (Sangat Baik)';
                            if($skor >= 70) return 'B (Baik)';
                            if($skor >= 50) return 'C (Cukup)';
                            return 'D (Kurang)';
                        }
                    @endphp

                    @foreach($komponen as $index => $k)
                    <tr>
                        <td class="text-center font-medium">{{ $index + 1 }}</td>
                        <td class="font-semibold text-slate-800">{{ $k['nama'] }}</td>
                        <td class="text-center font-black text-[15px]">{{ $k['nilai'] }}</td>
                        <td class="text-center font-bold text-slate-700">{{ getPredikat($k['nilai']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-blue-50/50">
                        <td colspan="2" class="text-right font-black uppercase tracking-widest py-4 text-[12px]">Rata-Rata Nilai Capaian Keseluruhan :</td>
                        <td class="text-center font-black text-xl text-blue-900">{{ number_format($raport->rata_rata, 1) }}</td>
                        <td class="text-center font-black text-blue-900">{{ getPredikat($raport->rata_rata) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-right font-bold uppercase tracking-widest py-3 bg-slate-50 text-[11px] text-slate-600">Total Poin Reward Terkumpul Selama Pembelajaran :</td>
                        <td colspan="2" class="text-center font-black text-base bg-white">{{ $siswa->total_point ?? 0 }} <span class="text-xs text-slate-500 font-semibold">Poin</span></td>
                    </tr>
                </tfoot>
            </table>

            <div class="border border-slate-400 p-5 mb-12 bg-slate-50/30 rounded-sm">
                <h4 class="font-bold font-serif text-[12px] uppercase tracking-wider text-slate-900 mb-2 border-b border-slate-300 pb-1 inline-block">Catatan & Evaluasi Instruktur</h4>
                <p class="text-[13px] font-medium text-slate-800 italic leading-relaxed mt-2">
                    "{{ $raport->catatan_pengajar ?? 'Siswa telah menyelesaikan tahapan program kelas ini dengan tingkat pemahaman yang komprehensif. Pertahankan motivasi belajar untuk pencapaian di level selanjutnya.' }}"
                </p>
            </div>

            <div class="flex justify-between text-[13px] text-slate-900 mt-8">
                <div class="text-center w-64">
                    <p class="mb-1 text-transparent">.</p>
                    <p class="mb-1 text-transparent">.</p>
                    <p class="font-bold mb-20">Mengetahui,<br>Kepala Akademik</p>
                    <p class="font-bold uppercase underline underline-offset-4">( ........................................ )</p>
                    <p class="text-[11px] mt-1 text-slate-500">Elite English Course</p>
                </div>

                <div class="text-center w-64">
                    <p class="mb-1">Diterbitkan di Kota Banjarmasin,</p>
                    <p class="mb-1">Tanggal: <span class="font-bold">{{ date('d F Y', strtotime($raport->tanggal_dibagikan)) }}</span></p>
                    <p class="font-bold mb-20">Instruktur Pembimbing,</p>
                    <p class="font-bold uppercase underline underline-offset-4">{{ $raport->nama_pengajar }}</p>
                </div>
            </div>

        </div> </div> <script>
        // Opsional: Hilangkan tanda // di bawah ini untuk auto-print saat halaman terbuka
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
