<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Elite English</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
    </style>
</head>
<body class="bg-slate-50 text-slate-900 flex h-screen overflow-hidden">

    <aside class="w-64 bg-[#0f172a] flex flex-col z-20 shadow-2xl border-r border-slate-800 transition-all duration-300">

        <div class="h-16 px-6 flex items-center border-b border-slate-800/60 bg-[#0f172a]">
            <div class="flex items-center gap-3">
                <div class="flex flex-col gap-1">
                    <img src="{{ asset('images/elite.png') }}" alt="Elite English Logo" class="h-8 w-auto object-contain">
                </div>
            </div>
        </div>

        <nav class="flex-1 px-3 py-6 space-y-1.5 overflow-y-auto custom-scrollbar">

            <div class="px-3 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Main Menu</div>

            <a href="/admin/dashboard" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-sm {{ request()->is('admin/dashboard') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">
                <i class="fa-solid fa-chart-pie w-5 text-center mr-2.5 {{ request()->is('admin/dashboard') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                Dashboard
            </a>

            <div class="space-y-1">
                <button onclick="toggleSubMenu('dropdownVerifikasi')" class="w-full flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-sm focus:outline-none {{ request()->is('admin/verifikasi*') || request()->is('admin/jadwal') || request()->is('admin/penempatan') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">
                    <i class="fa-solid fa-clipboard-check w-5 text-center mr-2.5 {{ request()->is('admin/verifikasi*') || request()->is('admin/jadwal') || request()->is('admin/penempatan') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                    <span>Verifikasi Pendaftar</span>
                    <i id="panahVerifikasi" class="fa-solid fa-chevron-down w-4 h-4 ml-auto text-[10px] transition-transform duration-300 flex items-center justify-center {{ request()->is('admin/verifikasi*') || request()->is('admin/jadwal') || request()->is('admin/penempatan') ? 'rotate-180' : '' }}"></i>
                </button>

                <div id="dropdownVerifikasi" class="{{ request()->is('admin/verifikasi*') || request()->is('admin/jadwal') || request()->is('admin/penempatan') ? '' : 'hidden' }} pl-10 space-y-1.5 mt-1 pb-2">
                    <a href="/admin/verifikasi" class="flex items-center py-1.5 transition-all duration-200 text-xs {{ request()->is('admin/verifikasi') ? 'text-white font-medium' : 'text-slate-500 hover:text-slate-300' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-2.5 {{ request()->is('admin/verifikasi') ? 'bg-yellow-400 shadow-[0_0_8px_rgba(250,204,21,0.6)]' : 'bg-slate-600' }}"></span>
                        Antrean Verifikasi
                    </a>
                    <a href="/admin/jadwal" class="flex items-center py-1.5 transition-all duration-200 text-xs {{ request()->is('admin/jadwal') ? 'text-white font-medium' : 'text-slate-500 hover:text-slate-300' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-2.5 {{ request()->is('admin/jadwal') ? 'bg-yellow-400 shadow-[0_0_8px_rgba(250,204,21,0.6)]' : 'bg-slate-600' }}"></span>
                        Daftar Jadwal Tes
                    </a>
                    <a href="/admin/pantau-pembayaran" class="flex items-center py-1.5 transition-all duration-200 text-xs {{ request()->is('admin/pantau-pembayaran') ? 'text-white font-medium' : 'text-slate-500 hover:text-slate-300' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-2.5 {{ request()->is('admin/pantau-pembayaran') ? 'bg-yellow-400 shadow-[0_0_8px_rgba(250,204,21,0.6)]' : 'bg-slate-600' }}"></span>
                        Lihat Pembayaran
                    </a>
                    <a href="/admin/penempatan" class="flex items-center py-1.5 transition-all duration-200 text-xs {{ request()->is('admin/penempatan') ? 'text-white font-medium' : 'text-slate-500 hover:text-slate-300' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-2.5 {{ request()->is('admin/penempatan') ? 'bg-yellow-400 shadow-[0_0_8px_rgba(250,204,21,0.6)]' : 'bg-slate-600' }}"></span>
                        Penempatan Kelas
                    </a>
                </div>
            </div>

            <a href="/admin/gelombang" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-sm {{ request()->is('admin/gelombang*') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">
                <i class="fa-regular fa-calendar-alt w-5 text-center mr-2.5 {{ request()->is('admin/gelombang*') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                Jadwal Pendaftaran
            </a>

            <div class="px-3 mt-6 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Akademik</div>

            <div class="flex flex-col mb-1">
                <a href="#" onclick="event.preventDefault(); document.getElementById('submenu-siswa').classList.toggle('hidden'); document.getElementById('chevron-siswa').classList.toggle('rotate-180');"
                   class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-all duration-200 text-sm {{ request()->is('admin/siswa') || request()->is('admin/penempatan-lanjutan') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">

                    <div class="flex items-center">
                        <i class="fa-solid fa-users w-5 text-center mr-2.5 {{ request()->is('admin/siswa') || request()->is('admin/penempatan-lanjutan') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                        Data Siswa
                    </div>

                    <i id="chevron-siswa" class="fa-solid fa-chevron-down text-[10px] transition-transform duration-300 {{ request()->is('admin/siswa') || request()->is('admin/penempatan-lanjutan') ? 'rotate-180 text-yellow-400' : 'text-slate-500' }}"></i>
                </a>

                <div id="submenu-siswa" class="flex flex-col pl-9 pr-3 mt-1.5 space-y-1 transition-all duration-300 {{ request()->is('admin/siswa') || request()->is('admin/penempatan-lanjutan') ? '' : 'hidden' }}">

                    <a href="/admin/siswa" class="flex items-center py-1.5 transition-all duration-200 text-xs {{ request()->is('admin/siswa') ? 'text-white font-medium' : 'text-slate-500 hover:text-slate-300' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-2.5 {{ request()->is('admin/siswa') ? 'bg-yellow-400 shadow-[0_0_8px_rgba(250,204,21,0.6)]' : 'bg-slate-600' }}"></span>
                        Daftar Siswa Aktif
                    </a>

                    <a href="/admin/siswa/penempatan-lanjutan" class="flex items-center py-1.5 transition-all duration-200 text-xs {{ request()->is('admin/penempatan-lanjutan') ? 'text-white font-medium' : 'text-slate-500 hover:text-slate-300' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-2.5 {{ request()->is('admin/penempatan-lanjutan') ? 'bg-yellow-400 shadow-[0_0_8px_rgba(250,204,21,0.6)]' : 'bg-slate-600' }}"></span>
                        Registrasi Ulang Siswa
                    </a>

                    <a href="/admin/alumni" class="flex items-center py-1.5 transition-all duration-200 text-xs {{ request()->is('admin/alumni') ? 'text-white font-medium' : 'text-slate-500 hover:text-slate-300' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-2.5 {{ request()->is('admin/alumni') ? 'bg-yellow-400 shadow-[0_0_8px_rgba(250,204,21,0.6)]' : 'bg-slate-600' }}"></span>
                        Data Alumni (Lulus)
                    </a>

                </div>
            </div>

            <a href="{{ route('admin.kelas.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-sm {{ request()->is('admin/kelas*') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">
                <i class="fa-solid fa-layer-group w-5 text-center mr-2.5 {{ request()->is('admin/kelas*') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                Data Kelas
            </a>

            <a href="{{ route('admin.pengajar.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-sm {{ request()->is('admin/pengajar*') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">
                <i class="fa-solid fa-chalkboard-user w-5 text-center mr-2.5 {{ request()->is('admin/pengajar*') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                Data Pengajar
            </a>

            <a href="{{ route('admin.jadwalbelajar.index') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-sm {{ request()->routeIs('admin.jadwalbelajar.*') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">
                <i class="fa-solid fa-calendar-check w-5 text-center mr-2.5 {{ request()->routeIs('admin.jadwalbelajar.*') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                Jadwal Kelas
            </a>

            <a href="{{ route('laporan.perkembangan') }}" class="flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-sm {{ request()->is('admin/laporan-perkembangan*') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">
                <i class="fa-solid fa-chart-line w-5 text-center mr-2.5 {{ request()->is('admin/laporan-perkembangan*') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                Laporan Perkembangan
            </a>

            <div class="px-3 mt-6 mb-2 text-[10px] font-bold text-slate-500 uppercase tracking-wider">Loyalty Program</div>

            <div class="space-y-1">
                <button onclick="toggleSubMenu('dropdownGift')" class="w-full flex items-center px-3 py-2.5 rounded-lg transition-all duration-200 text-sm focus:outline-none {{ request()->is('admin/gift*') || request()->is('admin/pengajuan-gift*') ? 'bg-white/10 text-yellow-400 font-semibold border-l-4 border-yellow-400' : 'text-slate-400 hover:bg-white/5 hover:text-white font-medium border-l-4 border-transparent' }}">
                    <i class="fa-solid fa-gift w-5 text-center mr-2.5 {{ request()->is('admin/gift*') || request()->is('admin/pengajuan-gift*') ? 'text-yellow-400' : 'text-slate-500' }}"></i>
                    <span>Kelola Hadiah</span>
                    <i id="panahGift" class="fa-solid fa-chevron-down w-4 h-4 ml-auto text-[10px] transition-transform duration-300 flex items-center justify-center {{ request()->is('admin/gift*') || request()->is('admin/pengajuan-gift*') ? 'rotate-180' : '' }}"></i>
                </button>

                <div id="dropdownGift" class="{{ request()->is('admin/gift*') || request()->is('admin/pengajuan-gift*') ? '' : 'hidden' }} pl-10 space-y-1.5 mt-1 pb-2">
                    <a href="{{ route('admin.gift.index') }}" class="flex items-center py-1.5 transition-all duration-200 text-xs {{ request()->is('admin/gift') ? 'text-white font-medium' : 'text-slate-500 hover:text-slate-300' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-2.5 {{ request()->is('admin/gift') ? 'bg-yellow-400 shadow-[0_0_8px_rgba(250,204,21,0.6)]' : 'bg-slate-600' }}"></span>
                        Master Data Hadiah
                    </a>
                    <a href="{{ route('admin.pengajuan_gift.index') }}" class="flex items-center py-1.5 transition-all duration-200 text-xs {{ request()->is('admin/pengajuan-gift*') ? 'text-white font-medium' : 'text-slate-500 hover:text-slate-300' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-2.5 {{ request()->is('admin/pengajuan-gift*') ? 'bg-yellow-400 shadow-[0_0_8px_rgba(250,204,21,0.6)]' : 'bg-slate-600' }}"></span>
                        Pengajuan Tukar Poin
                    </a>
                    <a href="{{ route('admin.gift.riwayat') }}" class="flex items-center py-1.5 transition-all duration-200 text-xs {{ request()->is('admin/gift/riwayat') ? 'text-white font-medium' : 'text-slate-500 hover:text-slate-300' }}">
                        <span class="w-1.5 h-1.5 rounded-full mr-2.5 {{ request()->is('admin/gift/riwayat') ? 'bg-yellow-400 shadow-[0_0_8px_rgba(250,204,21,0.6)]' : 'bg-slate-600' }}"></span>
                        Riwayat Penukaran
                    </a>
                </div>
            </div>

        </nav>

        <div class="p-4 border-t border-slate-800/60 bg-[#0B1120]">
            <a href="{{ route('admin.profil.index') }}"  class="flex items-center px-3 py-2 rounded-lg transition-all duration-200 text-sm mb-2 {{ request()->is('admin/profil*') ? 'bg-white/10 text-white font-medium' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                <i class="fa-solid fa-user-gear w-5 text-center mr-2.5"></i>
                Pengaturan Profil
            </a>

            <form action="/logout" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-xs font-bold text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 hover:text-rose-300 rounded-lg transition-all duration-200 border border-rose-500/20">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    Keluar Akun
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 flex flex-col h-screen overflow-hidden relative">

        <header class="bg-white px-8 py-4 flex justify-between items-center border-b border-slate-200 z-10">
            <div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight leading-tight">Sistem Manajemen Akademi</h2>
                <p class="text-[13px] text-slate-500 font-medium">Elite Core Admin Workspace</p>
            </div>

            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-800 leading-tight">
                            {{ $navbarProfil->nama ?? \Auth::user()->nama ?? 'Admin' }}
                        </p>
                        <p class="text-[11px] font-semibold text-blue-600 uppercase tracking-wide">Admin Aktif</p>
                    </div>

                    <div class="relative w-10 h-10 rounded-full flex-shrink-0 bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 font-bold overflow-hidden shadow-sm">
                        @if(isset($navbarProfil) && $navbarProfil->foto_profil)
                            <img src="{{ asset('storage/' . $navbarProfil->foto_profil) }}" alt="Foto Admin" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($navbarProfil->nama ?? \Auth::user()->nama ?? 'I', 0, 1)) }}
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto bg-slate-50">
            @yield('content')
        </div>
    </main>

    <script>
        function toggleSubMenu(idDropdown) {
            const dropdown = document.getElementById(idDropdown);
            // Menentukan panah mana yang harus diputar
            let panahId = idDropdown === 'dropdownVerifikasi' ? 'panahVerifikasi' : 'panahGift';
            const panah = document.getElementById(panahId);

            if (dropdown.classList.contains('hidden')) {
                // Buka menu
                dropdown.classList.remove('hidden');
                if(panah) panah.classList.add('rotate-180');
            } else {
                // Tutup menu
                dropdown.classList.add('hidden');
                if(panah) panah.classList.remove('rotate-180');
            }
        }
    </script>
</body>
</html>
