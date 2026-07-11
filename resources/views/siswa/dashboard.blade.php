@extends('layouts.main_siswa')

@section('content')
<div class="p-6 space-y-8 max-w-7xl mx-auto">

    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-blue-950 text-white p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
        <div class="space-y-2 z-10">
            <h1 class="text-3xl font-extrabold tracking-tight">Selamat Datang Kembali, {{ Auth::user()->nama ?? 'Siswa Elite' }}!</h1>
            <p class="text-blue-200 text-sm max-w-md">Terus tingkatkan prestasimu, kumpulkan poin bintang di setiap sesi, dan tukarkan dengan berbagai merchandise menarik.</p>
        </div>
        <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl flex items-center gap-4 z-10 w-full md:w-auto">
            <div class="w-12 h-12 bg-yellow-400 text-blue-950 rounded-xl flex items-center justify-center text-xl font-black shadow-lg">
                {{ $siswa->level->nama_level[0] ?? 'L' }}
            </div>
            <div>
                <div class="text-xs text-blue-300 font-semibold uppercase tracking-wider">Level Kamu</div>
                <div class="text-lg font-bold text-yellow-400">{{ $siswa->level->nama_level ?? 'Belum Ujian' }}</div>
            </div>
        </div>
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-yellow-400/10 rounded-full blur-2xl"></div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between group hover:border-amber-300 transition-all">
            <div class="space-y-2">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Poin Bintang Milikmu</p>
                <h3 class="text-3xl font-black text-slate-900 flex items-baseline gap-1">
                    {{ $siswa->total_point ?? 0 }}
                    <span class="text-xs font-medium text-slate-400">Stars</span>
                </h3>
                <a href="/siswa/gift" class="text-xs text-amber-600 font-bold hover:underline inline-flex items-center gap-1 mt-1">
                    Tukarkan Hadiah <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            <div class="w-14 h-14 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-amber-500 group-hover:text-white transition-all shadow-inner">
                <i class="fa-solid fa-star animate-pulse"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between group hover:border-blue-300 transition-all">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Placement Test</p>
                <div class="pt-1">
                    @if(isset($siswa->id_level))
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <i class="fa-solid fa-circle-check mr-1"></i> Terverifikasi
                        </span>
                        <p class="text-[11px] text-slate-400 mt-1">Kamu sudah ditempatkan di kelas.</p>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            <i class="fa-solid fa-clock mr-1"></i> Menunggu Jadwal
                        </span>
                        <p class="text-[11px] text-slate-400 mt-1">Silakan cek berkala jadwal tes kamu.</p>
                    @endif
                </div>
            </div>
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-blue-600 group-hover:text-white transition-all shadow-inner">
                <i class="fa-solid fa-file-signature"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between group hover:border-indigo-300 transition-all">
            <div class="space-y-1">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status Pembayaran</p>
                <div class="pt-1">
                    {{-- Kondisi penentu status tagihan pembayaran --}}
                    @if(($siswa->status_bayar ?? 'Lunas') == 'Lunas')
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <i class="fa-solid fa-shield-check mr-1"></i> Lunas Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 border border-rose-200">
                            <i class="fa-solid fa-circle-exclamation mr-1"></i> Menunggu Pembayaran
                        </span>
                    @endif
                </div>
                <a href="/siswa/pembayaran" class="text-xs text-indigo-600 font-bold hover:underline inline-flex items-center gap-1 mt-2 block">
                    Lihat Invoice Pembayaran <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
            <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-inner">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="font-bold text-slate-900 text-lg flex items-center gap-2">
                    <i class="fa-solid fa-chalkboard-user text-blue-900"></i> Informasi Kelas Belajar
                </h3>
                <span class="text-xs text-slate-400 font-medium">Tahun Ajaran {{ date('Y') }}</span>
            </div>

            @if(isset($siswa->id_kelas))
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <div class="p-4 bg-slate-50 rounded-xl space-y-1">
                    <div class="text-xs text-slate-400 font-bold uppercase">Nama Rombel</div>
                    <div class="text-base font-bold text-slate-800">{{ $siswa->kelas->nama_kelas ?? '-' }}</div>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl space-y-1">
                    <div class="text-xs text-slate-400 font-bold uppercase">Instructor / Pengajar</div>
                    <div class="text-base font-bold text-slate-800">{{ $siswa->kelas->pengajar->nama_pengajar ?? 'Belum Ditentukan' }}</div>
                </div>
            </div>
            @else
            <div class="py-8 text-center text-slate-400 space-y-2">
                <i class="fa-solid fa-graduation-cap text-4xl text-slate-300"></i>
                <p class="text-sm font-medium">Kamu belum masuk ke pengelompokan kelas manapun.</p>
                <p class="text-xs text-slate-400">Hubungi administrasi jika kamu sudah menyelesaikan semua administrasi.</p>
            </div>
            @endif
            @if($jadwalTerdekat)
                <div class="mt-5 flex items-start gap-4 bg-blue-50/60 p-4 rounded-xl border border-blue-100">
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                        <i class="fa-regular fa-calendar-check text-lg"></i>
                    </div>
                    <div>
                        <div class="text-[11px] font-bold text-blue-500 uppercase tracking-wider mb-0.5">Jadwal Kelas Terdekat</div>
                        <div class="text-sm font-bold text-slate-800">
                            {{ $jadwalTerdekat->hari }}, {{ date('d F Y', strtotime($jadwalTerdekat->tanggal)) }}
                        </div>
                        <div class="text-xs font-semibold text-slate-600 mt-1 flex items-center gap-1.5">
                            <i class="fa-regular fa-clock text-slate-400"></i>
                            {{ date('H:i', strtotime($jadwalTerdekat->jam_mulai)) }} - {{ date('H:i', strtotime($jadwalTerdekat->jam_selesai)) }} Wita
                        </div>
                        @if($jadwalTerdekat->keterangan)
                            <div class="text-[10px] mt-1.5 bg-white border border-blue-100 text-slate-500 px-2 py-0.5 rounded inline-block">
                                {{ $jadwalTerdekat->keterangan }}
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="mt-5 flex items-center gap-3 bg-slate-50 p-4 rounded-xl border border-slate-100 border-dashed">
                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center shrink-0">
                        <i class="fa-solid fa-mug-hot"></i>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-600">Belum Ada Jadwal</div>
                        <div class="text-[11px] text-slate-400 mt-0.5">Instruktur belum menentukan jadwal pertemuan berikutnya.</div>
                    </div>
                </div>
            @endif
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b pb-3">
                <h3 class="font-bold text-slate-900 text-base flex items-center gap-2">
                    <i class="fa-solid fa-gift text-amber-500"></i> Aktivitas Poin Terbaru
                </h3>
            </div>

            <div class="space-y-3 max-h-[180px] overflow-y-auto pr-1">
                @forelse($penukaran as $p)
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl text-xs">
                    <div>
                        <div class="font-bold text-slate-800">{{ $p->nama_gift }}</div>
                        <div class="text-[10px] text-slate-400 mt-0.5">{{ date('d M Y', strtotime($p->tanggal_penukaran)) }}</div>
                    </div>
                    <div>
                        @if($p->status == 'selesai')
                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 font-bold rounded-md">Berhasil</span>
                        @elseif($p->status == 'dibatalkan')
                            <span class="px-2 py-0.5 bg-rose-50 text-rose-700 font-bold rounded-md">Ditolak</span>
                        @else
                            <span class="px-2 py-0.5 bg-amber-50 text-amber-700 font-bold rounded-md">Proses</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="py-10 text-center text-slate-400 text-xs">
                    <i class="fa-solid fa-clock-rotate-left text-2xl block mb-2 text-slate-300"></i>
                    Belum ada riwayat penukaran hadiah.
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
