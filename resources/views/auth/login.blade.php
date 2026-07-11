<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page Elite English</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 antialiased selection:bg-yellow-400 selection:text-blue-950">

    <div class="min-h-screen flex">

        <div class="hidden lg:flex lg:w-1/2 relative bg-blue-950 overflow-hidden">
            <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                 class="absolute inset-0 w-full h-full object-cover opacity-60"
                 alt="Smart Academy Students">

            <div class="absolute inset-0 bg-gradient-to-t from-blue-950 via-blue-950/80 to-transparent mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-blue-900/30"></div>

            <div class="relative z-10 flex flex-col justify-between p-16 text-white w-full h-full">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/elite.png') }}" alt="Elite English Logo" class="h-12 w-auto object-contain">
                </div>

                <div>
                    <h2 class="text-5xl font-black leading-tight mb-6">Masa Depanmu,<br><span class="text-yellow-400">Dimulai Dari Sini.</span></h2>
                    <p class="text-blue-100 text-lg max-w-md font-medium leading-relaxed">
                        Akses Sistem Manajemen Akademi Terpadu untuk pengalaman belajar dan mengajar yang lebih interaktif.
                    </p>
                </div>
            </div>
        </div>


        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 bg-white relative">

            <a href="/" class="absolute top-8 right-8 text-sm font-bold text-slate-400 hover:text-blue-600 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Beranda
            </a>

            <div class="w-full max-w-md">

                <div class="lg:hidden flex items-center gap-3 mb-10">
                    <div class="w-10 h-10 bg-yellow-400 text-blue-950 rounded-lg flex items-center justify-center text-xl shadow-md">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <span class="text-xl font-black text-slate-900 tracking-widest uppercase">Elite English</span>
                </div>

                <div class="mb-10">
                    <h1 class="text-3xl font-black text-slate-900 mb-2">Selamat Datang!</h1>
                    <p class="text-slate-500 font-medium">Masukkan akun Anda untuk mengakses dashboard.</p>
                </div>

                <form action="/login" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                <i class="fa-regular fa-envelope"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none placeholder:text-slate-400 @error('email') border-rose-500 focus:ring-rose-500/20 focus:border-rose-500 bg-rose-50 @enderror"
                                   placeholder="nama@email.com">
                        </div>
                        @error('email')
                            <p class="text-xs text-rose-500 font-bold mt-2 flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input type="password" name="password" required
                                   class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none placeholder:text-slate-400 @error('password') border-rose-500 focus:ring-rose-500/20 focus:border-rose-500 bg-rose-50 @enderror"
                                   placeholder="••••••••">
                        </div>
                        @error('password')
                            <p class="text-xs text-rose-500 font-bold mt-2 flex items-center gap-1.5">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-2 focus:ring-blue-500/20 cursor-pointer transition-all">
                            <span class="text-sm font-medium text-slate-500 group-hover:text-slate-800 transition-colors">Biarkan saya tetap masuk</span>
                        </label>

                        <a href="{{ route('password.request') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700 transition-colors">Lupa Sandi?</a>
                    </div>

                    <button type="submit" class="w-full mt-2 py-4 bg-yellow-400 hover:bg-yellow-500 text-blue-950 font-black text-sm uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-yellow-400/30 hover:shadow-yellow-400/50 hover:-translate-y-0.5 focus:ring-4 focus:ring-yellow-400/50 outline-none">
                        Masuk
                    </button>
                </form>

                <div class="mt-10 text-center text-sm text-slate-500 font-medium">
                    Belum terdaftar sebagai pendaftar?
                    <a href="/register" class="font-bold text-blue-600 hover:text-blue-700 transition-colors">Daftar Di Sini</a>
                </div>

            </div>
        </div>

    </div>

</body>
</html>
