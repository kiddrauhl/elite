@extends('layouts.main_siswa')

@section('content')
<div class="py-6 px-4 sm:px-6 max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">E-Sertifikat Kelulusan</h1>
        <p class="text-sm text-slate-500 mt-2">Dokumen resmi kelulusan dari Elite English Course.</p>
    </div>

    @if($siswa->id_level != 3)
        <!-- Jika Siswa Belum Expert -->
        <div class="bg-white rounded-3xl border border-slate-200 p-10 text-center shadow-sm">
            <i class="fa-solid fa-lock text-5xl text-slate-300 mb-4"></i>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Memenuhi Syarat</h3>
            <p class="text-slate-500 text-sm max-w-md mx-auto">
                E-Sertifikat hanya diberikan kepada siswa yang telah berhasil menyelesaikan program hingga level Expert. Terus semangat belajar!
            </p>
        </div>
    @elseif(is_null($siswa->file_sertifikat))
        <!-- Jika Sudah Expert Tapi Admin Belum Menerbitkan -->
        <div class="bg-white rounded-3xl border border-amber-200 p-10 text-center shadow-sm bg-amber-50/30">
            <i class="fa-solid fa-hourglass-half text-5xl text-amber-400 mb-4 animate-pulse"></i>
            <h3 class="text-xl font-bold text-amber-800 mb-2">Sertifikat Sedang Diproses</h3>
            <p class="text-amber-600/80 text-sm max-w-md mx-auto">
                Selamat atas kelulusan Anda! E-Sertifikat Anda saat ini sedang dalam antrean pencetakan oleh tim Akademik. Silakan cek kembali secara berkala.
            </p>
        </div>
    @else
        <!-- Jika Sertifikat Sudah Diterbitkan -->
        <div class="bg-gradient-to-br from-slate-900 to-blue-950 rounded-3xl p-1 relative overflow-hidden shadow-xl">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-amber-500 rounded-full blur-3xl opacity-20 pointer-events-none"></div>

            <div class="bg-white rounded-[23px] p-8 sm:p-12 text-center relative z-10">
                <div class="w-20 h-20 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                    <i class="fa-solid fa-medal text-4xl"></i>
                </div>

                <h3 class="text-2xl font-black text-slate-900 mb-2">E-Sertifikat Tersedia</h3>
                <p class="text-slate-500 text-sm max-w-md mx-auto mb-8">
                    Dokumen sertifikat atas nama <b>{{ $siswa->nama_lengkap }}</b> telah diterbitkan secara resmi. Anda dapat mengunduhnya untuk keperluan portofolio atau dicetak secara mandiri.
                </p>

                <a href="{{ asset('sertifikat/' . $siswa->file_sertifikat) }}" target="_blank" class="inline-flex items-center justify-center px-8 py-4 text-sm font-bold text-white bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 rounded-xl transition-all shadow-lg shadow-amber-500/30 transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-download mr-2"></i> Unduh Sertifikat (PDF)
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
