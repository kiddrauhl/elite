<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pendaftar - Elite English</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-blue-50 text-blue-950 flex h-screen overflow-hidden">

    <aside class="w-64 bg-blue-900 shadow-xl flex flex-col">
        <div class="p-6 border-b border-blue-800 text-center">
            <h1 class="text-2xl font-bold text-yellow-400 tracking-wide">ELITE ADMIN</h1>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="/admin/dashboard" class="flex items-center px-4 py-3 text-blue-200 hover:bg-blue-800 hover:text-yellow-400 rounded-lg transition-colors font-medium">
                <span class="mr-3">📊</span> Dashboard
            </a>
            <a href="#" class="flex items-center px-4 py-3 bg-yellow-400 text-blue-900 font-bold rounded-lg transition-colors">
                <span class="mr-3">📝</span> Verifikasi Pendaftar
            </a>
        </nav>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <header class="bg-blue-900 shadow-sm px-8 py-4 flex justify-between items-center border-b-4 border-yellow-400">
            <h2 class="text-xl font-bold text-blue-50">Profil Pendaftar</h2>
            <a href="/admin/dashboard" class="text-sm font-bold text-yellow-400 hover:underline">&larr; Kembali ke Dashboard</a>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-100 text-emerald-800 rounded-lg font-bold border-l-4 border-emerald-500">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-blue-100 p-8 rounded-2xl border border-blue-200 shadow-sm">
                        <h3 class="text-xl font-bold text-blue-900 mb-6 border-b border-blue-200 pb-2">Informasi Pribadi</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                            <div>
                                <p class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-1">Nama Lengkap</p>
                                <p class="text-lg font-semibold text-blue-950">{{ $pendaftar->nama_lengkap }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-1">Email Akun</p>
                                <p class="text-lg font-semibold text-blue-950">{{ $pendaftar->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-1">Jenis Kelamin</p>
                                <p class="text-lg font-semibold text-blue-950">{{ $pendaftar->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-1">No. HP / WhatsApp</p>
                                <p class="text-lg font-semibold text-blue-950">{{ $pendaftar->no_hp ?? 'Belum Diisi' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-xs text-blue-600 font-bold uppercase tracking-wider mb-1">Alamat</p>
                                <p class="text-lg font-semibold text-blue-950 leading-relaxed">{{ $pendaftar->alamat ?? 'Belum Diisi' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-blue-900 p-8 rounded-2xl shadow-lg border-b-8 border-yellow-400">
                        <h3 class="text-lg font-bold text-yellow-400 mb-6">Manajemen Status</h3>
                        <form action="/admin/pendaftar/{{ $pendaftar->id_pendaftar }}/status" method="POST">
                            @csrf
                            <div class="mb-6">
                                <label class="block text-blue-200 text-sm mb-2">Ubah Status Menjadi:</label>
                                <select name="status" class="w-full bg-blue-800 text-blue-50 border border-blue-700 rounded-lg p-3 outline-none focus:ring-2 focus:ring-yellow-400">
                                    <option value="Lengkapi Biodata" {{ $pendaftar->status == 'Lengkapi Biodata' ? 'selected' : '' }}>Lengkapi Biodata</option>
                                    <option value="Menunggu Pembayaran" {{ $pendaftar->status == 'Menunggu Pembayaran' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                    <option value="Proses Tes" {{ $pendaftar->status == 'Proses Tes' ? 'selected' : '' }}>Proses Tes</option>
                                    <option value="Diterima" {{ $pendaftar->status == 'Diterima' ? 'selected' : '' }}>Diterima (Jadi Siswa)</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full bg-yellow-400 text-blue-900 font-bold py-3 rounded-lg hover:bg-yellow-500 transition shadow-md">
                                Perbarui Status
                            </button>
                        </form>
                    </div>

                    <div class="bg-blue-100 p-6 rounded-2xl border border-blue-200 text-center">
                        <p class="text-sm text-blue-800 italic">Pendaftar ini terdaftar sejak {{ \Carbon\Carbon::parse($pendaftar->tanggal_daftar)->format('d F Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
