<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Siswa Elite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page { size: landscape; margin: 1cm; }
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
        <h1 class="text-2xl font-black uppercase tracking-wider">Sistem Manajemen Akademi Elite English</h1>
        <h2 class="text-lg font-bold text-slate-600 mt-1">Laporan Data Siswa Aktif</h2>
        <p class="text-sm text-slate-500 mt-1">Dicetak pada: {{ date('d F Y H:i') }}</p>
    </div>

    <table class="w-full text-left border-collapse text-sm">
        <thead>
            <tr class="bg-slate-100 border border-slate-300">
                <th class="px-4 py-3 border border-slate-300">No</th>
                <th class="px-4 py-3 border border-slate-300">Nama Siswa</th>
                <th class="px-4 py-3 border border-slate-300">Kontak (HP & Email)</th>
                <th class="px-4 py-3 border border-slate-300">Asal Sekolah</th>
                <th class="px-4 py-3 border border-slate-300 text-center">Level</th>
                <th class="px-4 py-3 border border-slate-300 text-center">Point</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $row)
            <tr class="border-b border-slate-300">
                <td class="px-4 py-3 border border-slate-300 text-center">{{ $index + 1 }}</td>
                <td class="px-4 py-3 border border-slate-300 font-bold">{{ $row->nama_lengkap }}</td>
                <td class="px-4 py-3 border border-slate-300">
                    {{ $row->no_hp }}<br><span class="text-xs text-slate-500">{{ $row->email }}</span>
                </td>
                <td class="px-4 py-3 border border-slate-300">{{ $row->asal_sekolah }}</td>
                <td class="px-4 py-3 border border-slate-300 text-center">{{ $row->nama_level ?? '-' }}</td>
                <td class="px-4 py-3 border border-slate-300 text-center">{{ $row->total_point ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
