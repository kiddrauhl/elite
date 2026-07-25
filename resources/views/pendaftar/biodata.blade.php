@extends('layouts.main_pendaftar')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-6">

    <div class="flex items-center gap-4 mb-6">
        <a href="/pendaftar/dashboard" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-blue-600 transition-colors shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-800">Edit Biodata Pendaftaran</h1>
            <!-- Menggunakan Null Safe agar aman jika gelombang kosong, dan teks informatif bahwa ini tidak bisa diedit -->
            <p class="text-sm text-slate-500">Gelombang pendaftaran Anda: <strong class="text-blue-600">{{ $gelombang?->nama_gelombang ?? 'Belum ada jadwal' }}</strong></p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-blue-50 border-b border-blue-100 p-6 flex gap-4 items-start">
            <div class="text-blue-500 mt-1"><i class="fa-solid fa-circle-info text-xl"></i></div>
            <p class="text-sm text-blue-800 leading-relaxed">
                Anda sudah terdaftar. Silakan perbarui data diri Anda di bawah ini jika terdapat kesalahan. Gelombang pendaftaran sudah ditetapkan dan tidak dapat diubah.
            </p>
        </div>

        <!-- PERHATIKAN: Route action diubah ke biodata.update -->
        <form action="{{ route('pendaftar.biodata.update') }}" method="POST" class="p-6 md:p-8 space-y-6">
            @csrf

            <input type="hidden" name="id_jadwal_daftar" value="{{ $gelombang?->id_jadwal_daftar ?? '' }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- 🌟 TAMBAHAN: Input Nama Lengkap (Wajib karena ada di validasi Controller Anda!) -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pendaftar->nama_lengkap ?? $user->nama ?? '') }}" required placeholder="Contoh: Mahalini Asmara Poetri" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        <option value="" disabled {{ empty($pendaftar->jenis_kelamin) ? 'selected' : '' }}>Pilih Jenis Kelamin</option>
                        <option value="L" {{ (old('jenis_kelamin', $pendaftar->jenis_kelamin ?? '')) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ (old('jenis_kelamin', $pendaftar->jenis_kelamin ?? '')) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <!-- No HP -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">No. HP / WhatsApp Aktif</label>
                    <input type="tel" name="no_hp" value="{{ old('no_hp', $pendaftar->no_hp ?? '') }}" required placeholder="Contoh: 081234567890" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>

                <!-- Asal Sekolah -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Asal Sekolah / Kampus</label>
                    <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah', $pendaftar->asal_sekolah ?? '') }}" required placeholder="Contoh: SMAN 1 Banjarmasin" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>

                <!-- Tingkat Sekolah -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Tingkat Jenjang</label>
                    <select name="tingkat_sekolah" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        <option value="" disabled {{ empty($pendaftar->tingkat_sekolah) ? 'selected' : '' }}>Pilih Tingkat</option>
                        <option value="SD" {{ (old('tingkat_sekolah', $pendaftar->tingkat_sekolah ?? '')) == 'SD' ? 'selected' : '' }}>SD</option>
                        <option value="SMP" {{ (old('tingkat_sekolah', $pendaftar->tingkat_sekolah ?? '')) == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ (old('tingkat_sekolah', $pendaftar->tingkat_sekolah ?? '')) == 'SMA' ? 'selected' : '' }}>SMA/SMK</option>
                        <option value="Kuliah" {{ (old('tingkat_sekolah', $pendaftar->tingkat_sekolah ?? '')) == 'Kuliah' ? 'selected' : '' }}>Kuliah / Umum</option>
                    </select>
                </div>

                <!-- Alamat (Full Width) -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Alamat Lengkap Domisili</label>
                    <textarea name="alamat" rows="3" required placeholder="Sertakan nama jalan, RT/RW, dan kota..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all resize-none">{{ old('alamat', $pendaftar->alamat ?? '') }}</textarea>
                </div>
            </div>

            <hr class="border-slate-100 my-6">

            <div class="flex justify-end gap-3">
                <a href="/pendaftar/dashboard" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold text-sm rounded-xl hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
