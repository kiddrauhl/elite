@extends('layouts.main_pendaftar')

@section('content')
<div class="p-6 max-w-4xl mx-auto space-y-6">

    <div class="flex items-center gap-4 mb-6">
        <a href="/pendaftar/dashboard" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-blue-600 transition-colors shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-800">Lengkapi Biodata Pendaftaran</h1>
            <p class="text-sm text-slate-500">Anda akan mendaftar pada: <strong class="text-blue-600">{{ $gelombang->nama_gelombang }}</strong></p>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="bg-blue-50 border-b border-blue-100 p-6 flex gap-4 items-start">
            <div class="text-blue-500 mt-1"><i class="fa-solid fa-circle-info text-xl"></i></div>
            <p class="text-sm text-blue-800 leading-relaxed">
                Silakan lengkapi data diri Anda di bawah ini dengan benar. Data ini akan digunakan oleh Admin untuk keperluan administrasi dan penjadwalan Level Test.
            </p>
        </div>

        <form action="{{ route('pendaftar.simpan_biodata') }}" method="POST" class="p-6 md:p-8 space-y-6">
            @csrf
            <!-- Hidden Input untuk mengirimkan ID Gelombang yang dipilih -->
            <input type="hidden" name="id_jadwal_daftar" value="{{ $gelombang->id_jadwal_daftar }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Jenis Kelamin -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Jenis Kelamin</label>
                    <select name="jenis_kelamin" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <!-- No HP -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">No. HP / WhatsApp Aktif</label>
                    <input type="tel" name="no_hp" required placeholder="Contoh: 081234567890" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>

                <!-- Asal Sekolah -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Asal Sekolah / Kampus</label>
                    <input type="text" name="asal_sekolah" required placeholder="Contoh: SMAN 1 Banjarmasin" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                </div>

                <!-- Tingkat Sekolah -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Tingkat Jenjang</label>
                    <select name="tingkat_sekolah" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all">
                        <option value="" disabled selected>Pilih Tingkat</option>
                        <option value="SD">SD</option>
                        <option value="SMP">SMP</option>
                        <option value="SMA">SMA/SMK</option>
                        <option value="Kuliah">Kuliah / Umum</option>
                    </select>
                </div>

                <!-- Alamat (Full Width) -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Alamat Lengkap Domisili</label>
                    <textarea name="alamat" rows="3" required placeholder="Sertakan nama jalan, RT/RW, dan kota..." class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 outline-none transition-all resize-none"></textarea>
                </div>
            </div>

            <hr class="border-slate-100 my-6">

            <div class="flex justify-end gap-3">
                <a href="/pendaftar/dashboard" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold text-sm rounded-xl hover:bg-slate-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i> Daftar Sekarang
                </button>
            </div>
        </form>
    </div>
</div>
@endsection