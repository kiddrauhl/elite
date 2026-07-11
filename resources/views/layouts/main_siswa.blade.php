<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Siswa - EliteEnglish</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Styling Scrollbar Profesional */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Scrollbar untuk konten utama */
        .content-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .content-scrollbar::-webkit-scrollbar-track { background: #f8fafc; }
        .content-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .content-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased overflow-hidden">

    <div class="flex h-screen w-full relative">

        <aside id="sidebar" class="w-64 bg-[#0f172a] text-slate-100 flex-shrink-0 flex flex-col fixed md:sticky top-0 bottom-0 left-0 z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out h-screen shadow-2xl border-r border-slate-800">

            <div class="h-16 px-6 flex items-center border-b border-slate-800/60 bg-[#0f172a]">
                <div class="flex items-center gap-3">
                    <div class="flex flex-col gap-1">
                        <img src="{{ asset('images/elite.png') }}" alt="Elite English Logo" class="h-8 w-auto object-contain">
                    </div>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-6 space-y-1.5 custom-scrollbar">

                <div class="px-3 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Main Menu</div>

                <a href="/siswa/dashboard" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-sm {{ request()->is('siswa/dashboard*') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">
                    <i class="fa-solid fa-house w-5 text-center mr-2.5 {{ request()->is('siswa/dashboard*') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                    Beranda
                </a>

                <div class="px-3 mt-6 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Akademik</div>

                <a href="/siswa/kelas" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-sm {{ request()->is('siswa/kelas*') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">
                    <i class="fa-solid fa-chalkboard-user w-5 text-center mr-2.5 {{ request()->is('siswa/kelas*') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                    Ruang Kelas
                </a>

                <a href="/siswa/materi" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-sm {{ request()->is('siswa/materi*') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">
                    <i class="fa-solid fa-book-open w-5 text-center mr-2.5 {{ request()->is('siswa/materi*') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                    Materi Belajar
                </a>

                <a href="/siswa/raport" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-sm {{ request()->is('siswa/raport*') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">
                    <i class="fa-solid fa-award w-5 text-center mr-2.5 {{ request()->is('siswa/raport*') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                    E-Raport
                </a>

                <div class="px-3 mt-6 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Layanan & Reward</div>

                <a href="/siswa/pembayaran" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-sm {{ request()->is('siswa/pembayaran*') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">
                    <i class="fa-solid fa-wallet w-5 text-center mr-2.5 {{ request()->is('siswa/pembayaran*') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                    Registrasi Ulang
                </a>

                <a href="/siswa/gift" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-sm {{ request()->is('siswa/gift*') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">
                    <i class="fa-solid fa-gift w-5 text-center mr-2.5 {{ request()->is('siswa/gift*') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                    Tukar Poin Stars
                </a>

                <a href="/siswa/sertifikat" class="flex items-center px-4 py-3 mb-2 transition-all duration-200 rounded-xl text-sm {{ request()->is('siswa/sertifikat') ? 'bg-amber-500/10 text-amber-500 font-bold border-l-4 border-amber-500' : 'text-slate-400 hover:bg-slate-800 hover:text-white border-l-4 border-transparent' }}">
                    <i class="fa-solid fa-award w-6 text-center mr-2 {{ request()->is('siswa/sertifikat') ? 'text-amber-500' : 'text-slate-500' }}"></i>
                    E-Sertifikat
                </a>

            </nav>

            <div class="p-4 border-t border-slate-800/60 bg-[#0B1120]">
                <a href="/siswa/profil" class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 text-sm mb-2 {{ request()->is('siswa/profil*') ? 'bg-white/10 text-white font-medium' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    <i class="fa-solid fa-user-gear w-5 text-center mr-2.5"></i>
                    Pengaturan Profil
                </a>

                <form action="/logout" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 hover:text-rose-300 rounded-lg transition-all duration-200 border border-rose-500/20">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        Keluar Akun
                    </button>
                </form>
            </div>
        </aside>

        <div id="overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden md:hidden transition-opacity duration-300"></div>

        <main class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50 relative">

            <header class="bg-white border-b border-slate-200/80 px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm shadow-slate-100/40">

                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="md:hidden text-slate-500 hover:text-slate-900 focus:outline-none p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                        <i class="fa-solid fa-bars-staggered text-lg"></i>
                    </button>

                    <div class="hidden md:block">
                        <h2 class="text-xl font-bold text-slate-800 tracking-tight leading-tight">Sistem Manajemen Akademi</h2>
                        <p class="text-[13px] text-slate-500 font-medium">Student Workspace • <span id="liveJam" class="font-semibold text-slate-600">00:00:00</span> WITA</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3">
                        <div class="text-right hidden sm:block">
                            <h4 class="text-sm font-bold text-slate-800 leading-tight tracking-tight">
                                {{ $navbarProfil->nama_lengkap ?? Auth::user()->nama ?? 'Siswa Elite' }}
                            </h4>
                            <p class="text-[11px] font-semibold text-emerald-600 flex items-center justify-end gap-1.5 mt-0.5">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Online Aktif
                            </p>
                        </div>

                        <div class="relative w-10 h-10 rounded-full flex-shrink-0 bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 font-bold shadow-sm">
                            @if(isset($navbarProfil) && $navbarProfil->foto_profil)
                                <img src="{{ asset('storage/' . $navbarProfil->foto_profil) }}" alt="Foto Profil" class="w-full h-full rounded-full object-cover">
                            @else
                                {{ strtoupper(substr($navbarProfil->nama_lengkap ?? Auth::user()->nama ?? 'S', 0, 1)) }}
                            @endif
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-white rounded-full"></span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto content-scrollbar bg-slate-50 relative pb-20">
                @yield('content')
            </div>

        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');

            if (sidebar.classList.contains('-translate-x-full')) {
                // Buka Sidebar
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                overlay.classList.remove('hidden');
            } else {
                // Tutup Sidebar
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        // Jam Digital Otomatis WITA
        function updateJam() {
            const sekarang = new Date();
            const jam = String(sekarang.getHours()).padStart(2, '0');
            const menit = String(sekarang.getMinutes()).padStart(2, '0');
            const detik = String(sekarang.getSeconds()).padStart(2, '0');
            const displayJam = document.getElementById('liveJam');
            if(displayJam) displayJam.innerHTML = `${jam}:${menit}:${detik}`;
        }
        setInterval(updateJam, 1000);
        updateJam();
    </script>
</body>
</html>
