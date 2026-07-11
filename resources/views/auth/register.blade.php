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


        <div class="w-full lg:w-7/12 flex flex-col items-center px-6 py-10 sm:p-12 relative h-full overflow-y-auto">

            <div class="w-full max-w-2xl flex justify-end mb-8 lg:mb-0 lg:absolute lg:top-8 lg:right-10">
                <a href="/" class="text-sm font-bold text-slate-400 hover:text-blue-600 transition-colors flex items-center gap-2">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>

            <div class="w-full max-w-2xl mt-4 lg:mt-8">

                <div class="lg:hidden flex flex-col items-center text-center mb-8">
                    <div class="w-12 h-12 bg-yellow-400 text-blue-950 rounded-xl flex items-center justify-center text-2xl shadow-md mb-4">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <h1 class="text-2xl font-black text-slate-900">Buat Akun Elite Course</h1>
                    <p class="text-sm text-slate-500 mt-2">Lengkapi biodata di bawah ini untuk memulai.</p>
                </div>

                <div class="hidden lg:block mb-10">
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Buat Akun Elite Course</h1>
                    <p class="text-sm text-slate-500 mt-2">Lengkapi data dan biodata pribadi Anda di bawah ini.</p>
                </div>

                <form method="POST" action="/register" class="space-y-8">
                    @csrf

                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                <i class="fa-solid fa-shield-halved text-sm"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Akun Pendaftar</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="md:col-span-2">
                                <label for="nama" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                                <input id="nama" type="text" name="nama" value="{{ old('nama') }}" required placeholder="Contoh: Muhammad Fajar"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('nama') border-red-500 ring-2 ring-red-500/20 @enderror">
                                @error('nama') <p class="text-xs text-red-500 font-semibold mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="email" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Alamat Email Aktif</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('email') border-red-500 ring-2 ring-red-500/20 @enderror">
                                @error('email') <p class="text-xs text-red-500 font-semibold mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Buat Password</label>
                                <input id="password" type="password" name="password" required placeholder="Minimal 8 karakter"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('password') border-red-500 ring-2 ring-red-500/20 @enderror">
                                @error('password') <p class="text-xs text-red-500 font-semibold mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="password-confirm" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Ulangi Password</label>
                                <input id="password-confirm" type="password" name="password_confirmation" required placeholder="••••••••"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                        <div class="flex items-center gap-2 mb-6 border-b border-slate-100 pb-4">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <i class="fa-solid fa-address-card text-sm"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Biodata Pribadi</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label for="jenis_kelamin" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Jenis Kelamin</label>
                                <select id="jenis_kelamin" name="jenis_kelamin" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                                    <option value="" disabled selected>Pilih</option>
                                    <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin') <p class="text-xs text-red-500 font-semibold mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-slate-700 text-sm">Pilih Gelombang Pendaftaran <span class="text-danger"></span></label>

                                @if(isset($gelombangForm) && $gelombangForm->count() > 0)
                                    <select name="jadwal_daftar" class="form-select rounded-xl p-2.5 text-sm bg-slate-50 border-slate-300 focus:ring-blue-500" required>
                                        <option value="" selected disabled>Pilih Gelombang Terbuka</option>
                                        @foreach($gelombangForm as $g)
                                            <option value="{{ $g->id_jadwal_daftar }}">{{ $g->nama_gelombang }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <select class="form-select rounded-xl p-2.5 text-sm bg-slate-100 text-slate-400 border-slate-300 cursor-not-allowed" disabled>
                                        <option value="" selected>Maaf, belum ada pendaftaran yang dibuka</option>
                                    </select>
                                    <span class="text-xs text-rose-500 mt-1 block font-medium">* Hubungi admin bimbingan belajar jika ini merupakan kesalahan sistem.</span>
                                @endif
                            </div>

                            <div>
                                <label for="no_hp" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">No. HP / WhatsApp</label>
                                <input id="no_hp" type="tel" name="no_hp" value="{{ old('no_hp') }}" required placeholder="08xxxxxxxxxx"
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('no_hp') border-red-500 @enderror">
                                @error('no_hp') <p class="text-xs text-red-500 font-semibold mt-1.5">{{ $message }}</p> @enderror
                            </div>

                            <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-12 gap-5">
                                <div class="md:col-span-7">
                                    <label for="asal_sekolah" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Asal Sekolah / Kampus</label>
                                    <input id="asal_sekolah" type="text" name="asal_sekolah" value="{{ old('asal_sekolah') }}" required placeholder="Contoh: SMAN 1 Banjarmasin"
                                        class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('asal_sekolah') border-red-500 @enderror">
                                    @error('asal_sekolah') <p class="text-xs text-red-500 font-semibold mt-1.5">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-5">
                                    <label for="tingkat_sekolah" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Tingkat Jenjang</label>
                                    <select id="tingkat_sekolah" name="tingkat_sekolah" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                                        <option value="" disabled selected>Pilih</option>
                                        <option value="SD" {{ old('tingkat_sekolah') == 'SD' ? 'selected' : '' }}>SD</option>
                                        <option value="SMP" {{ old('tingkat_sekolah') == 'SMP' ? 'selected' : '' }}>SMP</option>
                                        <option value="SMA" {{ old('tingkat_sekolah') == 'SMA' ? 'selected' : '' }}>SMA/SMK</option>
                                        <option value="Kuliah" {{ old('tingkat_sekolah') == 'Kuliah' ? 'selected' : '' }}>Kuliah</option>
                                    </select>
                                    @error('tingkat_sekolah') <p class="text-xs text-red-500 font-semibold mt-1.5">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <label for="alamat" class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Alamat Lengkap</label>
                                <textarea id="alamat" name="alamat" rows="3" required placeholder="Sertakan nama jalan, RT/RW, dan kota..."
                                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none @error('alamat') border-red-500 @enderror">{{ old('alamat') }}</textarea>
                                @error('alamat') <p class="text-xs text-red-500 font-semibold mt-1.5">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-yellow-400 hover:bg-yellow-500 text-blue-950 font-black text-sm uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-yellow-400/30 hover:shadow-yellow-400/50 hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-yellow-400/50">
                        <i class="fa-solid fa-paper-plane mr-2"></i> Daftar & Ikuti Test
                    </button>

                    <div class="text-center pb-6">
                        <p class="text-sm text-slate-500 font-medium">
                            Sudah punya akun?
                            <a href="/login" class="font-bold text-blue-600 hover:text-blue-700 transition-colors">Masuk di sini</a>
                        </p>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>
</html>
