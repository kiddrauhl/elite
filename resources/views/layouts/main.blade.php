<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite English Learning Platform</title>
    
    <!-- Tailwind & Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Konfigurasi Font Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], }
                }
            }
        }
    </script>

    <style>
        /* Custom Scrollbar bergaya Mac/Modern */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>

<!-- Menambahkan selection color agar teks yang diblok berwarna kuning-biru (ciri khas Elite) -->
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col selection:bg-amber-400 selection:text-blue-950">

    <!-- Navigation Bar (Menggunakan Fixed agar transisi blur lebih halus) -->
    <nav class="fixed w-full z-50 bg-blue-950/90 backdrop-blur-lg border-b border-white/10 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">

                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center group">
                        <img src="{{ asset('images/elite.png') }}" alt="Elite English Logo" class="h-10 w-auto object-contain transition-transform duration-300 group-hover:scale-105">
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/#alur" class="text-blue-100 hover:text-amber-400 text-sm font-semibold transition-colors">Alur Belajar</a>
                    <a href="/#level" class="text-blue-100 hover:text-amber-400 text-sm font-semibold transition-colors">4 Level Kelas</a>
                    <a href="/#prestasi" class="text-blue-100 hover:text-amber-400 text-sm font-semibold transition-colors">Siswa Berprestasi</a>
                    <a href="/#fasilitas" class="text-blue-100 hover:text-amber-400 text-sm font-semibold transition-colors">Fasilitas</a>
                </div>

                <!-- Desktop Auth/Action Buttons -->
                <div class="hidden md:flex items-center gap-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-white hover:text-amber-400 text-sm font-bold transition-colors px-3 py-2">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-gradient-to-r from-amber-400 to-amber-500 hover:from-amber-500 hover:to-amber-600 text-blue-950 px-6 py-2.5 rounded-full text-sm font-bold shadow-lg shadow-amber-500/30 transition-all transform hover:-translate-y-0.5">
                                Daftar Akun
                            </a>
                        @endif
                    @else
                        <!-- Dropdown User Desktop -->
                        <div class="relative inline-block text-left">
                            <div>
                                <button type="button" onclick="toggleUserDropdown()" id="dropdownBtn" class="inline-flex justify-center items-center w-full rounded-full border border-blue-800/50 bg-blue-900/50 px-5 py-2.5 text-sm font-bold text-white hover:bg-blue-800/60 transition-all gap-2 shadow-sm">
                                    <i class="fa-solid fa-circle-user text-amber-400 text-lg"></i>
                                    {{ Auth::user()->name ?? 'Pengguna' }}
                                    <i class="fa-solid fa-chevron-down text-xs text-blue-300 ml-1"></i>
                                </button>
                            </div>

                            <div id="userDropdown" class="hidden origin-top-right absolute right-0 mt-3 w-56 rounded-2xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 divide-y divide-slate-100 focus:outline-none z-50 overflow-hidden transition-all">
                                <div class="px-4 py-3 bg-slate-50">
                                    <p class="text-xs font-medium text-slate-500">Masuk sebagai</p>
                                    <p class="text-sm font-bold text-slate-900 truncate">{{ Auth::user()->email ?? '' }}</p>
                                </div>
                                <div class="py-1">
                                    <!-- Link ke Dashboard -->
                                    <a href="/dashboard" class="flex items-center px-4 py-2.5 text-sm text-slate-700 hover:bg-amber-50 hover:text-amber-700 font-semibold gap-3 transition-colors">
                                        <i class="fa-solid fa-table-columns w-4 text-center"></i> Dashboard Saya
                                    </a>
                                    <!-- Tombol Logout yang aman -->
                                    <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                       class="flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-semibold gap-3 transition-colors">
                                        <i class="fa-solid fa-power-off w-4 text-center"></i> Keluar Aplikasi
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                                </div>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile Menu Button (Hamburger) -->
                <div class="md:hidden flex items-center">
                    <button type="button" onclick="toggleMobileMenu()" class="text-blue-100 hover:text-white focus:outline-none p-2 transition-colors">
                        <i class="fa-solid fa-bars text-2xl" id="mobileMenuIcon"></i>
                    </button>
                </div>

            </div>
        </div>

        <!-- Mobile Menu Dropdown -->
        <div id="mobileMenu" class="hidden md:hidden bg-blue-950 border-t border-white/10 shadow-xl overflow-hidden transition-all">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="/#alur" class="block px-3 py-3 rounded-xl text-base font-medium text-blue-100 hover:text-amber-400 hover:bg-blue-900/50 transition-colors">Alur Belajar</a>
                <a href="/#level" class="block px-3 py-3 rounded-xl text-base font-medium text-blue-100 hover:text-amber-400 hover:bg-blue-900/50 transition-colors">4 Level Kelas</a>
                <a href="/#prestasi" class="block px-3 py-3 rounded-xl text-base font-medium text-blue-100 hover:text-amber-400 hover:bg-blue-900/50 transition-colors">Siswa Berprestasi</a>
                <a href="/#fasilitas" class="block px-3 py-3 rounded-xl text-base font-medium text-blue-100 hover:text-amber-400 hover:bg-blue-900/50 transition-colors">Fasilitas</a>
                
                <div class="border-t border-blue-800/50 mt-4 pt-4 pb-2">
                    @guest
                        <div class="grid grid-cols-2 gap-3 mt-2">
                            <a href="{{ route('login') }}" class="flex items-center justify-center px-4 py-3 rounded-xl text-sm font-bold text-white bg-blue-900/50 border border-blue-800 hover:bg-blue-800 transition-colors">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="flex items-center justify-center px-4 py-3 rounded-xl text-sm font-bold text-blue-950 bg-amber-400 hover:bg-amber-500 shadow-md transition-colors">Daftar Akun</a>
                            @endif
                        </div>
                    @else
                        <!-- Info User di HP -->
                        <div class="px-3 mb-4 flex items-center gap-3 bg-blue-900/30 p-3 rounded-xl border border-blue-800/50">
                            <div class="bg-amber-100 text-amber-600 rounded-full w-10 h-10 flex items-center justify-center font-bold text-lg shadow-sm">
                                {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-white">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-blue-300">{{ Auth::user()->email ?? '' }}</div>
                            </div>
                        </div>
                        <a href="/dashboard" class="flex items-center px-3 py-3 rounded-xl text-base font-medium text-blue-100 hover:bg-blue-900/50 transition-colors"><i class="fa-solid fa-table-columns mr-3 w-5"></i> Dashboard Saya</a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();" class="flex items-center px-3 py-3 rounded-xl text-base font-medium text-red-400 hover:bg-red-900/30 hover:text-red-300 mt-1 transition-colors"><i class="fa-solid fa-power-off mr-3 w-5"></i> Keluar Aplikasi</a>
                        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <!-- Menambahkan padding-top (pt-24) agar konten tidak tertutup oleh Fixed Navbar -->
    <main class="flex-grow pt-24 pb-12">
        @yield('content')
    </main>

    <!-- Footer Minimalis yang Elegan -->
    <footer class="bg-blue-950 border-t border-blue-900 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="md:flex md:items-center md:justify-between">
                <div class="flex justify-center md:justify-start mb-4 md:mb-0">
                    <img src="{{ asset('images/elite.png') }}" alt="Elite English Logo" class="h-6 opacity-50 grayscale hover:grayscale-0 hover:opacity-100 transition-all duration-300">
                </div>
                <div class="flex justify-center space-x-6">
                    <p class="text-center text-sm text-blue-200/50 font-medium">
                        &copy; {{ date('Y') }} SIBIJAR - Elite English Course. Hak cipta dilindungi.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Logika Dropdown Akun Desktop
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Logika Menu Mobile (Hamburger)
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const icon = document.getElementById('mobileMenuIcon');
            
            menu.classList.toggle('hidden');
            
            // Animasi ganti icon bars ke silang
            if (menu.classList.contains('hidden')) {
                icon.classList.remove('fa-xmark');
                icon.classList.add('fa-bars');
            } else {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-xmark');
            }
        }

        // Menutup dropdown otomatis jika klik di luar area
        window.addEventListener('click', function(e) {
            const userDropdown = document.getElementById('userDropdown');
            const btn = document.getElementById('dropdownBtn');
            
            if (userDropdown && btn && !btn.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.add('hidden');
            }
        });

        // Efek bayangan pada Navbar saat di-scroll
        window.addEventListener('scroll', function() {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 10) {
                nav.classList.add('shadow-lg');
                nav.classList.add('bg-blue-950');
                nav.classList.remove('bg-blue-950/90');
            } else {
                nav.classList.remove('shadow-lg');
                nav.classList.remove('bg-blue-950');
                nav.classList.add('bg-blue-950/90');
            }
        });
    </script>

</body>
</html>