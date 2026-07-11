<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite English Learning Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 min-h-screen flex flex-col justify-between">

    <nav class="sticky top-0 z-50 bg-blue-950/95 backdrop-blur-md border-b border-blue-900/50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">

                <div class="flex-shrink-0">
                    <a href="/" class="text-xl font-extrabold text-white tracking-wide flex items-center gap-2">
                        <span class="bg-yellow-400 text-blue-950 p-1.5 rounded-lg text-sm"><i class="fa-solid fa-graduation-cap"></i></span>
                        SMART<span class="text-yellow-400">ACADEMY</span>
                    </a>
                </div>

                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-6">
                        <a href="/#alur" class="text-blue-100 hover:text-yellow-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">Alur Belajar</a>
                        <a href="/#level" class="text-blue-100 hover:text-yellow-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">4 Level Kelas</a>
                        <a href="/#prestasi" class="text-blue-100 hover:text-yellow-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">Siswa Berprestasi</a>
                        <a href="/#fasilitas" class="text-blue-100 hover:text-yellow-400 px-3 py-2 rounded-md text-sm font-medium transition-colors">Fasilitas</a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    @guest
                        <a href="{{ route('login') }}" class="text-blue-100 hover:text-yellow-400 text-sm font-semibold transition-colors">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-yellow-400 hover:bg-yellow-500 text-blue-950 px-5 py-2.5 rounded-full text-sm font-bold shadow-md shadow-yellow-400/10 transition-all duration-200 transform hover:-translate-y-0.5">
                                Daftar Akun
                            </a>
                        @endif
                    @else
                        <div class="relative inline-block text-left">
                            <div>
                                <button type="button" onclick="toggleUserDropdown()" id="dropdownBtn" class="inline-flex justify-center items-center w-full rounded-xl border border-blue-800 bg-blue-900/40 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-900/60 transition-all gap-2">
                                    <i class="fa-solid fa-circle-user text-yellow-400 text-lg"></i>
                                    {{ Auth::user()->name }}
                                    <i class="fa-solid fa-chevron-down text-xs text-blue-300 ml-1"></i>
                                </button>
                            </div>

                            <div id="userDropdown" class="hidden origin-top-right absolute right-0 mt-2 w-52 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 divide-y divide-slate-100 focus:outline-none z-50">
                                <div class="py-1.5">
                                    <a href="logout"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                       class="flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 font-bold gap-2">
                                        <i class="fa-solid fa-power-off w-5"></i> Keluar Aplikasi
                                    </a>
                                    <form id="logout-form" action="logout" method="POST" class="d-none">@csrf</form>
                                </div>
                            </div>
                        </div>
                    @guest
                    @endguest @endguest
                </div>

            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <script>
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Menutup dropdown otomatis jika user mengklik area di luar tombol
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('userDropdown');
            const btn = document.getElementById('dropdownBtn');

            if (dropdown && btn && !btn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    </script>

</body>
</html>
