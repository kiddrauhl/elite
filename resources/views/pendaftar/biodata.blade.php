@extends('layouts.main_pendaftar')

@section('content')
<div class="p-6 max-w-5xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Pengaturan Profil & Akun</h1>
        <p class="text-sm text-slate-500 mt-1">Perbarui informasi data diri atau amankan kata sandi akun portal Anda.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl font-bold text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl font-bold text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-500"></i> {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <h2 class="text-lg font-bold text-slate-800 border-b pb-3 flex items-center gap-2">
                <i class="fa-solid fa-id-card text-blue-600"></i> Informasi Biodata
            </h2>

            <form action="{{ route('pendaftar.biodata.update') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ $pendaftar->nama_lengkap }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Nomor WhatsApp / HP</label>
                        <input type="text" name="no_hp" value="{{ $pendaftar->no_hp }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Asal Sekolah / Kampus</label>
                        <input type="text" name="asal_sekolah" value="{{ $pendaftar->asal_sekolah }}" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tingkat Jenjang</label>
                        <select name="tingkat_sekolah" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-sm text-slate-700 focus:ring-2 focus:ring-blue-500 focus:outline-none cursor-pointer">
                            <option value="SD" {{ $pendaftar->tingkat_sekolah == 'SD' ? 'selected' : '' }}>SD / Sederajat</option>
                            <option value="SMP" {{ $pendaftar->tingkat_sekolah == 'SMP' ? 'selected' : '' }}>SMP / Sederajat</option>
                            <option value="SMA" {{ $pendaftar->tingkat_sekolah == 'SMA' ? 'selected' : '' }}>SMA / Sederajat</option>
                            <option value="Kuliah" {{ $pendaftar->tingkat_sekolah == 'Kuliah' ? 'selected' : '' }}>Kuliah / Umum</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Alamat Rumah Lengkap</label>
                    <textarea name="alamat" rows="3" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ $pendaftar->alamat }}</textarea>
                </div>

                <div class="pt-2 text-right">
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wide rounded-xl shadow-md transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
            <h2 class="text-lg font-bold text-slate-800 border-b pb-3 flex items-center gap-2">
                <i class="fa-solid fa-lock text-yellow-500"></i> Keamanan Akun
            </h2>

            <form action="{{ route('pendaftar.password.update') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password Saat Ini</label>
                    <input type="password" name="password_lama" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Password Baru (Min 8 Karakter)</label>
                    <input type="password" name="password_baru" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Ulangi Password Baru</label>
                    <input type="password" name="password_baru_confirmation" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-400 focus:outline-none">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-yellow-400 hover:bg-yellow-500 text-blue-950 font-bold text-xs uppercase tracking-wide rounded-xl shadow-md transition-colors">
                        Ganti Password
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection
