<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Sertifikat - Elite English Course</title>
    <!-- Gunakan CDN Tailwind untuk halaman publik -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-white rounded-3xl shadow-xl overflow-hidden">

        <!-- Header -->
        <div class="bg-blue-950 p-6 text-center">
            <h1 class="text-white font-black text-xl tracking-wide uppercase">Verifikasi Sistem</h1>
            <p class="text-blue-200 text-xs mt-1">Elite English Course</p>
        </div>

        <div class="p-8 text-center">
            @if($status_valid)
                <!-- Tampilan Jika Sertifikat Asli -->
                <div class="w-24 h-24 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner animate-bounce">
                    <i class="fa-solid fa-shield-check text-5xl"></i>
                </div>

                <h2 class="text-2xl font-black text-slate-800 mb-2">Sertifikat Valid</h2>
                <p class="text-slate-500 text-sm mb-6">
                    Dokumen ini resmi diterbitkan oleh sistem dan terdaftar dalam pangkalan data kami.
                </p>

                <div class="bg-slate-50 rounded-2xl p-5 text-left border border-slate-100 space-y-3">
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Diberikan Kepada</div>
                        <div class="text-base font-bold text-slate-900">{{ $siswa->nama_lengkap }}</div>
                    </div>
                    <div class="w-full h-px bg-slate-200"></div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tingkat Program</div>
                        <div class="text-base font-bold text-amber-600">Level {{ $level->nama_level }}</div>
                    </div>
                    <div class="w-full h-px bg-slate-200"></div>
                    <div>
                        <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Status Dokumen</div>
                        <div class="text-sm font-bold text-emerald-600"><i class="fa-solid fa-check-circle mr-1"></i> Terotentikasi Digital</div>
                    </div>
                </div>
            @else
                <!-- Tampilan Jika Gagal / Palsu -->
                <div class="w-24 h-24 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <i class="fa-solid fa-triangle-exclamation text-5xl"></i>
                </div>

                <h2 class="text-2xl font-black text-slate-800 mb-2">Sertifikat Tidak Valid</h2>
                <p class="text-slate-500 text-sm mb-6">
                    Dokumen ini tidak dapat diverifikasi atau telah dicabut dari sistem pangkalan data kami.
                </p>
            @endif
        </div>

        <!-- Footer -->
        <div class="bg-slate-100 p-4 text-center border-t border-slate-200">
            <p class="text-[10px] text-slate-400 font-medium">
                &copy; {{ date('Y') }} Sistem Manajemen Akademi - Elite English Course. <br> Dokumen ini digenerate secara otomatis.
            </p>
        </div>

    </div>

</body>
</html>
