<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Sandi Akun Elite</title>
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
            <img src="https://images.unsplash.com/photo-1456406644174-8ddd4cd52a06?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
                 class="absolute inset-0 w-full h-full object-cover opacity-40 mix-blend-overlay" 
                 alt="Smart Academy Security">
            
            <div class="absolute inset-0 bg-gradient-to-t from-blue-950 via-blue-950/80 to-transparent mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-blue-900/30"></div>

            <div class="relative z-10 flex flex-col justify-between p-16 text-white w-full h-full">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-yellow-400 text-blue-950 rounded-xl flex items-center justify-center text-2xl shadow-lg shadow-yellow-400/20">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <span class="text-2xl font-black tracking-widest uppercase">Elite English</span>
                </div>

                <div>
                    <h2 class="text-5xl font-black leading-tight mb-6">Amankan Kembali<br><span class="text-yellow-400">Akun Anda.</span></h2>
                    <p class="text-blue-100 text-lg max-w-md font-medium leading-relaxed">
                        Silakan buat kata sandi baru yang kuat dan mudah diingat. Pastikan untuk tidak membagikan kredensial Anda kepada siapa pun.
                    </p>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 sm:p-12 bg-white relative">
            
            <div class="w-full max-w-md">
                
                <div class="lg:hidden flex items-center gap-3 mb-10">
                    <div class="w-10 h-10 bg-yellow-400 text-blue-950 rounded-lg flex items-center justify-center text-xl shadow-md">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <span class="text-xl font-black text-slate-900 tracking-widest uppercase">Elite English</span>
                </div>

                <div class="mb-10">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl mb-6">
                        <i class="fa-solid fa-shield-check"></i>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 mb-3 tracking-tight">Buat Sandi Baru Akun Anda</h1>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed">
                        Tautan ini valid dan aman. Silakan ketikkan kata sandi baru Anda di bawah ini untuk memulihkan akses ke dashboard.
                    </p>
                </div>

                <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email Anda</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-at"></i>
                            </div>
                            <input type="email" name="email" value="{{ $email ?? old('email') }}" required readonly
                                   class="w-full pl-11 pr-4 py-3.5 bg-slate-100 border border-slate-200 rounded-xl text-sm font-semibold text-slate-500 outline-none cursor-not-allowed">
                        </div>
                        @error('email') 
                            <p class="text-xs text-rose-500 font-bold mt-2 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> 
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Password Baru</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input type="password" name="password" required autofocus placeholder="Minimal 8 karakter"
                                   class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none @error('password') border-rose-500 bg-rose-50 @enderror">
                        </div>
                        @error('password') 
                            <p class="text-xs text-rose-500 font-bold mt-2 flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p> 
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Ulangi Password Baru</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                                <i class="fa-solid fa-lock-open"></i>
                            </div>
                            <input type="password" name="password_confirmation" required placeholder="••••••••"
                                   class="w-full pl-11 pr-4 py-3.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all outline-none">
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-2 py-4 bg-yellow-400 hover:bg-yellow-500 text-blue-950 font-black text-sm uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-yellow-400/30 hover:shadow-yellow-400/50 hover:-translate-y-0.5 focus:ring-4 focus:ring-yellow-400/50 outline-none">
                        <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Password Baru
                    </button>
                </form>
                </div>
        </div>
        
    </div>

</body>
</html>