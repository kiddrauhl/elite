<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penempatan Lanjutan Siswa Elite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { size: portrait; margin: 1cm; }
            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-white p-8 text-slate-800">

    <div class="mb-6 no-print text-center">
        <button onclick="window.print()" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg shadow-md hover:bg-blue-700">
            Simpan PDF / Cetak Sekarang
        </button>
    </div>

    <div class="text-center border-b-2 border-slate-800 pb-4 mb-6">
        <h1 class="text-2xl font-black uppercase tracking-wider">Sistem Manajemen Akademi Elite</h1>
        <h2 class="text-lg font-bold text-slate-600 mt-1">Laporan Riwayat Penempatan Kelas Lanjutan</h2>
        <p class="text-sm text-slate-500 mt-1">Dicetak pada: {{ date('d F Y H:i') }}</p>
    </div>

    <table class="w-full text-left border-collapse text-sm">
        <thead>
            <tr class="bg-slate-100 border border-slate-300">
                <th class="px-4 py-3 border border-slate-300 w-12 text-center">No</th>
                <th class="px-4 py-3 border border-slate-300">Data Transaksi Siswa</th>
                <th class="px-4 py-3 border border-slate-300 text-center">Status Transisi</th>
                <th class="px-4 py-3 border border-slate-300">Keterangan / Posisi Kelas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataPenempatan as $index => $data)
            <tr class="border-b border-slate-300">
                <td class="px-4 py-3 border border-slate-300 text-center">{{ $index + 1 }}</td>
                <td class="px-4 py-3 border border-slate-300">
                    <span class="font-bold block">{{ $data->nama_lengkap }}</span>
                    <span class="text-xs text-slate-500 font-mono">{{ $data->order_id }}</span>
                </td>

                <td class="px-4 py-3 border border-slate-300 text-center">
                    @if($data->id_level_lanjutan)
                        <span class="px-2 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded">Menunggu</span>
                    @else
                        <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded">Selesai</span>
                    @endif
                </td>

                <td class="px-4 py-3 border border-slate-300">
                    @if($data->id_level_lanjutan)
                        <span class="text-slate-500 text-xs">{{ $data->level_sekarang ?? 'Lama' }} ➔</span>
                        <span class="font-bold text-blue-700">{{ $data->level_tujuan ?? 'Baru' }}</span>
                    @else
                        <span class="font-bold text-emerald-700">Aktif di {{ $data->level_sekarang }}</span><br>
                        <span class="text-xs text-slate-600">Kelas: {{ $data->nama_kelas ?? 'Belum terdata' }}</span>
                    @endif
                </td>
            </tr>
            @endforeach

            @if($dataPenempatan->isEmpty())
            <tr>
                <td colspan="4" class="px-4 py-8 text-center text-slate-500 italic border border-slate-300">
                    Belum ada riwayat transaksi penempatan lanjutan saat ini.
                </td>
            </tr>
            @endif
        </tbody>
    </table>

    <script>
        window.onload = function() { window.print(); }
    </script>
</body>
</html>
