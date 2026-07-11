@extends('layouts.main_siswa')

@section('content')
<div class="p-6 space-y-6 max-w-5xl mx-auto">

    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Profil Akun Saya</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola informasi data diri dan kredensial akun Elite kamu di sini.</p>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-4 rounded-xl text-sm font-semibold flex items-center gap-3 shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-4 rounded-xl text-sm font-semibold shadow-sm space-y-1">
            <div class="flex items-center gap-3 mb-1">
                <i class="fa-solid fa-circle-exclamation text-rose-500 text-lg"></i>
                <span class="font-bold">Gagal memperbarui profil:</span>
            </div>
            <ul class="list-disc pl-8 space-y-0.5 text-xs font-medium text-rose-700">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('siswa.profil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="space-y-6">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 text-center relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-b from-blue-50 to-white h-24"></div>

                    <label for="foto_profil" class="relative z-10 w-28 h-28 mx-auto bg-blue-600 text-white rounded-full flex items-center justify-center text-4xl font-black shadow-lg border-4 border-white mb-4 cursor-pointer overflow-hidden group-hover:border-blue-100 transition-all">

                        @if(isset($profil->foto_profil) && $profil->foto_profil)
                            <img src="{{ asset('storage/' . $profil->foto_profil) }}" alt="Foto Profil" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($profil->nama_lengkap ?? 'S', 0, 1)) }}
                        @endif

                        <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <i class="fa-solid fa-camera text-white text-xl mb-1"></i>
                            <span class="text-[10px] text-white font-semibold">Ubah Foto</span>
                        </div>

                    </label>
                    <input type="file" id="foto_profil" name="foto_profil" accept="image/*" class="hidden">

                    <div class="absolute top-[108px] right-[100px] z-20 w-5 h-5 bg-emerald-500 border-2 border-white rounded-full shadow-sm" title="Online"></div>

                    <h2 class="text-xl font-bold text-slate-900 leading-tight z-10 relative mt-2">{{ $profil->nama_lengkap ?? 'Nama Siswa' }}</h2>
                    <p class="text-sm text-slate-500 z-10 relative mb-6">Siswa Elite</p>

                    <div class="flex justify-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-700 font-bold text-xs rounded-xl border border-amber-200 shadow-sm">
                            <i class="fa-solid fa-star text-amber-500"></i> {{ $profil->total_point ?? 0 }} Stars
                        </span>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i class="fa-solid fa-shield-halved text-blue-600"></i> Keamanan Akun
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Email Utama</p>
                            <input type="email" value="{{ $profil->email ?? '-' }}" readonly
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm font-semibold text-slate-500 outline-none cursor-not-allowed">
                        </div>

                        <hr class="border-slate-100">

                        <div>
                            <h4 class="text-xs font-bold text-slate-800 mb-1">Ubah Password</h4>
                            <p class="text-[10px] text-slate-400 mb-4">Kosongkan jika Anda tidak ingin mengubah password.</p>

                            <div class="space-y-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Password Baru</label>
                                    <input type="password" name="password" placeholder="Masukkan password baru"
                                           class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru"
                                           class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden h-full flex flex-col">

                    <div class="p-6 sm:p-8 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-800">Informasi Pribadi</h2>
                            <p class="text-xs text-slate-500 mt-1">Perbarui data diri Anda di bawah ini agar selalu valid.</p>
                        </div>
                    </div>

                    <div class="p-6 sm:p-8 flex-grow">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="col-span-full">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" value="{{ $profil->nama_lengkap ?? '' }}" required
                                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ $profil->tanggal_lahir ?? '' }}"
                                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor HP (WhatsApp)</label>
                                <input type="tel" name="no_hp" value="{{ $profil->no_hp ?? '' }}"
                                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Asal Sekolah</label>
                                <input type="text" name="asal_sekolah" value="{{ $profil->asal_sekolah ?? '' }}"
                                       class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all">
                            </div>

                            <div class="col-span-full">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Lengkap</label>
                                <textarea name="alamat" rows="3"
                                          class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-800 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all resize-none">{{ $profil->alamat ?? '' }}</textarea>
                            </div>

                        </div>
                    </div>

                    <div class="p-6 border-t border-slate-100 bg-slate-50/50 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition-all shadow-lg shadow-blue-600/30 hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-blue-600/30 flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                        </button>
                    </div>

                </div>
            </div>

        </div>
    </form>

</div>

<script>
    document.getElementById('foto_profil').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            let reader = new FileReader();
            reader.onload = function(ev) {
                // Mencari elemen gambar jika sudah ada, atau mengubah background jika masih inisial
                let label = document.querySelector('label[for="foto_profil"]');
                let img = label.querySelector('img');

                if(img) {
                    img.src = ev.target.result;
                } else {
                    label.innerHTML = `<img src="${ev.target.result}" class="w-full h-full object-cover">
                                       <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <i class="fa-solid fa-camera text-white text-xl mb-1"></i>
                                            <span class="text-[10px] text-white font-semibold">Ubah Foto</span>
                                       </div>`;
                }
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endsection
