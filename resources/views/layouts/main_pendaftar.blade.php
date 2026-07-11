<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Pendaftar - Elite English</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* slate-50 */
        }

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
    </style>
</head>
<body class="text-slate-800 antialiased overflow-hidden flex h-screen">

    <div class="min-h-screen flex flex-col md:flex-row w-full relative">

        <aside id="sidebarPortal" class="w-64 bg-[#0f172a] text-slate-100 flex-shrink-0 flex flex-col justify-between fixed md:sticky top-0 bottom-0 left-0 z-40 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out h-screen shadow-2xl border-r border-slate-800">

            <div>
                <div class="h-16 px-6 flex items-center justify-between border-b border-slate-800/60 bg-[#0f172a]">
                    <div class="flex items-center gap-3">
                        <div class="flex flex-col gap-1">
                            <img src="{{ asset('images/elite.png') }}" alt="Elite English Logo" class="h-8 w-auto object-contain">
                        </div>
                    </div>
                    <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white focus:outline-none transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <nav class="p-4 space-y-1.5 mt-2 overflow-y-auto custom-scrollbar">
                    <div class="px-3 mb-3 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Menu Utama</div>

                    <a href="/pendaftar/dashboard" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-sm {{ Request::is('pendaftar/dashboard*') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">
                        <i class="fa-solid fa-gauge-high w-5 text-center mr-2.5 {{ Request::is('pendaftar/dashboard*') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                        Dashboard Utama
                    </a>

                    <a href="/pendaftar/biodata" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-sm {{ Request::is('pendaftar/biodata*') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">
                        <i class="fa-solid fa-user-gear w-5 text-center mr-2.5 {{ Request::is('pendaftar/biodata*') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                        Biodata Saya
                    </a>

                    <a href="/pendaftar/pembayaran" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-sm {{ Request::is('pendaftar/pembayaran*') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">
                        <i class="fa-solid fa-wallet w-5 text-center mr-2.5 {{ Request::is('pendaftar/pembayaran*') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                        Pembayaran Registrasi
                    </a>
                </nav>
            </div>

            <div class="p-4 border-t border-slate-800/60 bg-[#0B1120]">
                <form action="/logout" method="POST" id="logoutForm">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 hover:text-rose-300 rounded-lg transition-all duration-200 border border-rose-500/20">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        Keluar Sistem
                    </button>
                </form>
            </div>

        </aside>

        <div id="sidebarOverlay" onclick="toggleSidebar()" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-30 md:hidden"></div>

        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">

            <header class="bg-white border-b border-slate-200/80 px-6 py-4 flex items-center justify-between sticky top-0 z-20 shadow-sm shadow-slate-100/40">

                <div class="flex items-center gap-4">
                    <button onclick="toggleSidebar()" class="md:hidden text-slate-500 hover:text-slate-900 focus:outline-none p-1.5 rounded-lg hover:bg-slate-100 transition-colors">
                        <i class="fa-solid fa-bars-staggered text-lg"></i>
                    </button>

                    <div class="hidden md:block">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sistem Informasi Bimbingan Belajar</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <span class="text-sm font-bold text-slate-800 block leading-tight">{{ Auth::user()->nama ?? 'Calon Siswa' }}</span>
                        <span class="text-[11px] font-semibold text-slate-400 block mt-0.5">ID Akun: #{{ Auth::id() }}</span>
                    </div>
                    <div class="w-10 h-10 bg-slate-100 text-slate-600 font-bold rounded-full flex items-center justify-center border border-slate-200 shadow-sm">
                        <i class="fa-solid fa-user-astronaut text-lg"></i>
                    </div>
                </div>

            </header>

            <main class="flex-1 py-6 px-4 md:px-8 overflow-y-auto bg-slate-50">
                @yield('content')
            </main>

            <footer class="bg-white border-t border-slate-200/80 px-6 py-4 text-center text-[11px] text-slate-400 font-semibold tracking-wide">
                &copy; {{ date('Y') }} Elite - Hak Cipta Dilindungi Undang-Undang.
            </footer>

        </div>

    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebarPortal');
            const overlay = document.getElementById('sidebarOverlay');

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
    </script>
</body>
</html>
