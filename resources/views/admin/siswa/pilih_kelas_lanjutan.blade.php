@extends('layouts.main_admin')

@section('content')
<div class="p-6 max-w-2xl mx-auto space-y-6">

    <a href="/admin/penempatan-lanjutan" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Antrean
    </a>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/40 overflow-hidden">

        <div class="bg-slate-900 p-6 text-white border-b border-slate-800">
            <p class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Alokasi Kelas Lanjutan</p>
            <h3 class="text-xl font-bold text-white mt-1">{{ $siswa->nama_lengkap }}</h3>
        </div>

        <form action="{{ url('/admin/siswa/penempatan-lanjutan/simpan/' . $siswa->id_siswa) }}" method="POST" class="p-6 space-y-6">
            @csrf

            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 flex items-center justify-between">
                <div>
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Level Sebelumnya</span>
                    <span class="text-sm font-semibold text-slate-600">{{ $siswa->level_sekarang ?? 'Belum Ada' }}</span>
                </div>

                <div class="w-10 h-10 rounded-full bg-blue-50 border border-blue-100 flex items-center justify-center text-blue-600">
                    <i class="fa-solid fa-angles-right animate-pulse"></i>
                </div>

                <div class="text-right">
                    <span class="block text-[10px] font-bold text-slate-400 uppercase">Level Baru (Tujuan)</span>
                    <span class="px-2.5 py-1 rounded-md bg-emerald-50 text-emerald-700 font-bold text-xs border border-emerald-100 inline-block mt-0.5">
                        {{ $siswa->level_tujuan }}
                    </span>
                </div>
            </div>

            <div class="space-y-2">
                <label for="id_kelas" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Pilih Kelas Baru Untuk Siswa <span class="text-rose-500">*</span></label>
                <div class="relative">
                    <select name="id_kelas" id="id_kelas" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 transition-all font-semibold text-sm text-slate-700 appearance-none">
                        <option value=""> Pilih Kelas Aktif (Level {{ $siswa->level_tujuan }})</option>
                        @foreach($daftarKelas as $kelas)
                            <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                        <i class="fa-solid fa-chevron-down text-xs"></i>
                    </div>
                </div>

                @if($daftarKelas->isEmpty())
                    <p class="text-xs text-rose-600 font-medium mt-1">
                        <i class="fa-solid fa-circle-exhibition mr-1"></i> Peringatan: Belum ada kelas yang dibuat untuk level <strong>{{ $siswa->level_tujuan }}</strong>. Silakan buat kelasnya terlebih dahulu di menu Data Kelas.
                    </p>
                @else
                    <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">
                        Sistem hanya menampilkan daftar kelas yang terdaftar aktif di bawah naungan tingkat level <strong>{{ $siswa->level_tujuan }}</strong> sesuai rekomendasi instruktur.
                    </p>
                @endif
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="/admin/siswa/penempatan-lanjutan" class="px-5 py-3 text-sm font-bold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Batal
                </a>
                <button type="submit" {{ $daftarKelas->isEmpty() ? 'disabled' : '' }} class="px-6 py-3 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 disabled:bg-slate-300 disabled:cursor-not-allowed rounded-xl shadow-lg shadow-blue-600/10 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> Konfirmasi Perpindahan Kelas
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
