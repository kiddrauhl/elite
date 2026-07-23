<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Elite English</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 antialiased selection:bg-yellow-400 selection:text-blue-950">

    <div class="flex h-screen overflow-hidden bg-slate-50">

        <!-- KIRI: Banner & Informasi -->
        <div class="hidden lg:flex lg:w-5/12 bg-blue-950 relative flex-col justify-between p-12 text-white h-full">

            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                 class="absolute inset-0 w-full h-full object-cover opacity-20 mix-blend-overlay" alt="Belajar SIBIJAR">

            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-16">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/elite.png') }}" alt="Elite English Logo" class="h-12 w-auto object-contain">
                    </div>
                </div>

                <h2 class="text-4xl font-black leading-tight mb-4">
                    Langkah Pertama<br>Menuju <span class="text-yellow-400">Prestasi.</span>
                </h2>
                <p class="text-blue-200 text-sm leading-relaxed mb-10 max-w-sm">
                    Bergabunglah bersama siswa lainnya dan nikmati fasilitas bimbingan belajar terbaik.
                </p>

                <ul class="space-y-6">
                    <li class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-blue-800/50 flex items-center justify-center shrink-0 mt-0.5 border border-blue-700/50">
                            <i class="fa-solid fa-check text-yellow-400 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-sm">Kurikulum Terstruktur</h4>
                            <p class="text-xs text-blue-300 mt-1">Sistem 4 level kelas yang disesuaikan dengan kemampuanmu.</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-blue-800/50 flex items-center justify-center shrink-0 mt-0.5 border border-blue-700/50">
                            <i class="fa-solid fa-star text-yellow-400 text-sm"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-sm">Sistem Point Stars</h4>
                            <p class="text-xs text-blue-300 mt-1">Kumpulkan bintang dari setiap prestasimu dan tukarkan dengan hadiah menarik.</p>
                        </div>
                    </li>
                </ul>
            </div>

        </div>

        <!-- KANAN: Form Registrasi Akun -->
        <div class="w-full lg:w-7/12 flex flex-col items-center justify-center px-6 py-10 sm:p-12 relative h-full overflow-y-auto">

            <div class="w-full max-w-xl flex justify-end mb-8 lg:mb-0 lg:absolute lg:top-8 lg:right-10">
                <a href="/" class="text-sm font-bold text-slate-400 hover:text-blue-600 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>

            <div class="w-full max-w-xl mt-4 lg:mt-0">

                <div class="lg:hidden flex flex-col items-center text-center mb-8">
                    <div class="w-12 h-12 bg-yellow-400 text-blue-950 rounded-xl flex items-center justify-center text-2xl shadow-md mb-4">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900">Buat Akun Elite Course</h1>
                    <p class="text-sm text-slate-500 mt-2">Daftarkan email Anda untuk mulai belajar.</p>
                </div>

                <div class="hidden lg:block mb-8 text-center">
                    <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-3xl shadow-sm mb-6 mx-auto">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Buat Akun Pendaftar</h1>
                    <p class="text-sm text-slate-500 mt-2">Daftarkan diri Anda untuk masuk ke dashboard siswa.</p>
                </div>

                <form method="POST" action="/register" class="space-y-6">
                    @csrf

                    <div class="bg-white p-6 md:p-8 rounded-2xl border border-slate-200 shadow-sm">
                        
                        <div class="grid grid-cols-1 gap-5">
                            
                            <!-- Nama Lengkap -->
                            <div>
                                <label for="nama" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                                <input id="nama" type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Muhammad Fajar"
                                    class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('nama') border-red-500 ring-2 ring-red-500/20 @enderror">
                                @error('nama') <p class="text-xs text-red-500 font-semibold mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Alamat Email Aktif</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                                    class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('email') border-red-500 ring-2 ring-red-500/20 @enderror">
                                @error('email') <p class="text-xs text-red-500 font-semibold mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <!-- Password -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Buat Password</label>
                                    <input id="password" type="password" name="password" required placeholder="Minimal 8 karakter"
                                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('password') border-red-500 ring-2 ring-red-500/20 @enderror">
                                    @error('password') <p class="text-xs text-red-500 font-semibold mt-1.5">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="password-confirm" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Ulangi Password</label>
                                    <input id="password-confirm" type="password" name="password_confirmation" required placeholder="••••••••"
                                        class="w-full px-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-black text-sm uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-blue-600/30 hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-blue-500/30 flex justify-center items-center gap-2">
                        Buat Akun <i class="fa-solid fa-arrow-right"></i>
                    </button>

                    <div class="text-center pb-6">
                        <p class="text-sm text-slate-500 font-medium">
                            Sudah punya akun?
                            <a href="/login" class="font-bold text-blue-600 hover:text-blue-800 transition-colors">Masuk di sini</a>
                        </p>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>
</html>