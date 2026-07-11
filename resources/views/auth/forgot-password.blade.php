<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Sandi Akun Elite</title>
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
            <img src="https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80"
                 class="absolute inset-0 w-full h-full object-cover opacity-50 mix-blend-overlay"
                 alt="Smart Academy Support">

            <div class="absolute inset-0 bg-gradient-to-t from-blue-950 via-blue-950/80 to-transparent mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-blue-900/30"></div>

            <div class="relative z-10 flex flex-col justify-between p-16 text-white w-full h-full">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/elite.png') }}" alt="Elite English Logo" class="h-12 w-auto object-contain">
                </div>

                <div>
                    <h2 class="text-5xl font-black leading-tight mb-6">Lupa Kata Sandi Anda?<br><span class="text-yellow-400">Jangan Khawatir.</span></h2>
                    <p class="text-blue-100 text-lg max-w-md font-medium leading-relaxed">
                        Masukkan email Anda dan kami akan membantu Anda mendapatkan kembali akses ke akun Elite English dengan aman.
                    </p>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 bg-white relative">

            <a href="{{ route('login') }}" class="absolute top-8 right-8 text-sm font-bold text-slate-400 hover:text-blue-600 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Login
            </a>

            <div class="w-full max-w-md">

                <div class="lg:hidden flex items-center gap-3 mb-10">
                    <div class="w-10 h-10 bg-yellow-400 text-blue-950 rounded-lg flex items-center justify-center text-xl shadow-md">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <span class="text-xl font-black text-slate-900 tracking-widest uppercase">Elite English</span>
                </div>

                <div class="mb-10">
                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-6">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 mb-3 tracking-tight">Pulihkan Akun Anda</h1>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed">
                        Masukkan alamat email yang Anda gunakan saat mendaftar. Kami akan mengirimkan tautan khusus untuk mengatur ulang kata sandi Anda.
                    </p>
                </div>

                @if (session('status'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-start gap-3 shadow-sm">
                        <i class="fa-solid fa-circle-check mt-0.5 text-emerald-500"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Alamat Email Terdaftar</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                <i class="fa-regular fa-envelope"></i>
                            </div>
                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all outline-none placeholder:text-slate-400 @error('email') border-rose-500 focus:ring-rose-500/20 focus:border-rose-500 @enderror"
                                   placeholder="nama@email.com">
                        </div>
                        @error('email')
                            <p class="text-xs text-rose-500 font-bold mt-2 flex items-center gap-1">
                                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-4 bg-yellow-400 hover:bg-yellow-500 text-blue-950 font-black text-sm uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-yellow-400/30 hover:shadow-yellow-400/50 hover:-translate-y-0.5 focus:ring-4 focus:ring-yellow-400/50 outline-none">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Kirim Tautan Pemulihan
                    </button>
                </form>

                <div class="mt-8 text-center text-sm text-slate-500 font-medium">
                    Tiba-tiba ingat kata sandi Anda?
                    <a href="/login" class="font-bold text-blue-600 hover:text-blue-700 transition-colors">Masuk di sini</a>
                </div>

            </div>
        </div>

    </div>

</body>
</html>
