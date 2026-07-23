@extends('layouts.main_pendaftar')

@section('content')
<div class="p-6 max-w-5xl mx-auto space-y-6">

    <!-- BANNER UTAMA -->
    <div class="relative bg-[#0f172a] p-6 md:p-8 rounded-3xl border border-slate-800 shadow-xl overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="absolute -right-12 -top-12 w-48 h-48 bg-yellow-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white/10 text-yellow-400 text-[10px]">
                    <i class="fa-solid fa-sparkles"></i>
                </span>
                <span class="text-[10px] font-bold text-yellow-400 uppercase tracking-wider">Portal Calon Siswa</span>
            </div>
            <!-- Menggunakan Auth::user()->name agar aman saat data pendaftar belum ada -->
            <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">Halo, {{ Auth::user()->nama ?? Auth::user()->name }}!</h1>
            <p class="text-slate-300 mt-2 text-sm max-w-lg leading-relaxed">Selamat datang di website pendaftaran. Ini adalah ruang utamamu untuk memantau seluruh proses pendaftaran hingga kamu resmi menjadi siswa kami.</p>
        </div>

        @if($pendaftar)
            <!-- Status Badge (Hanya muncul jika sudah daftar) -->
            <div class="relative z-10 text-center bg-slate-800/90 backdrop-blur-md p-4 rounded-2xl border border-slate-600 min-w-[170px] shadow-inner">
                <p class="text-[10px] font-extrabold text-slate-200 uppercase tracking-widest mb-2">Status Saat Ini</p>
                <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold capitalize border shadow-sm bg-amber-500/20 text-amber-300 border-amber-500/40">
                    <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> {{ $pendaftar->status == 'pending' ? 'Menunggu Verifikasi' : $pendaftar->status }}
                </span>
            </div>
        @endif
    </div>

    <!-- LOGIKA UTAMA: TAMPILKAN KARTU GELOMBANG JIKA BELUM DAFTAR -->
    @if(!$pendaftar || is_null($pendaftar->id_jadwal_daftar))
        
        <div>
            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-800">Gelombang Pendaftaran Terbuka</h2>
                <p class="text-sm text-slate-500">Pilih dan daftar pada salah satu gelombang di bawah ini untuk memulai.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($gelombang as $item)
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm hover:shadow-lg transition-all duration-300 flex flex-col justify-between group">
                        <div class="mb-6">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-calendar-check text-xl"></i>
                            </div>
                            <h3 class="font-black text-slate-900 text-xl mb-3">{{ $item->nama_gelombang ?? 'Gelombang' }}</h3>
                            
                            <div class="space-y-2 text-sm text-slate-600 font-medium bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <p class="flex items-center gap-3"><i class="fa-regular fa-calendar-days text-slate-400 w-4"></i> {{ $item->tanggal_mulai ? date('d M Y', strtotime($item->tanggal_mulai)) : '-' }}</p>
                                <p class="flex items-center gap-3"><i class="fa-solid fa-users text-slate-400 w-4"></i> Kuota: {{ $item->kuota ?? '-' }}</p>
                            </div>
                        </div>

                        <!-- Tombol Pemicu Pendaftaran -->
                        <div class="mt-4">
                            <!-- Ubah nama di dalam fungsi route() -->
                            <a href="{{ route('pendaftar.isi_biodata', $item->id_jadwal_daftar) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-yellow-400 hover:bg-yellow-500 text-blue-950 font-bold text-sm rounded-xl transition-all shadow-sm">
                                Daftar Sekarang <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    <!-- JIKA SUDAH DAFTAR, TAMPILKAN STATUS PENDING/DITERIMA -->
    @else

        @if(strtolower($pendaftar->status) == 'pending' || strtolower($pendaftar->status) == 'menunggu jadwal')
            <!-- Tampilan dari Screenshot Anda -->
            <div class="bg-amber-50 rounded-3xl border border-amber-200/60 p-8 text-center shadow-sm">
                <div class="w-20 h-20 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-hourglass-half text-3xl animate-pulse"></i>
                </div>
                <h2 class="text-xl font-bold text-amber-900 mb-2">Berkas Sedang Ditinjau</h2>
                <p class="text-amber-700 text-sm max-w-md mx-auto">Admin kami sedang memverifikasi data pendaftaranmu. Jadwal Level Test akan segera muncul di sini setelah berkas disetujui. Silakan cek secara berkala.</p>
            </div>
            
        @elseif(strtolower($pendaftar->status) == 'proses' && $pendaftaran)
            <!-- Sisa kode jadwal level test Anda... -->
            
        @endif

    @endif

</div>
@endsection