@extends('layouts.main_siswa')

@section('content')
<div class="p-6 space-y-6 max-w-6xl mx-auto">

    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Ruang Kelas Belajar</h1>
        <p class="text-sm text-slate-500 mt-1">Informasi lengkap seputar rombongan belajar, instruktur, dan teman sekelasmu.</p>
    </div>

    @if($kelas)
    <!-- KONDISI 1: SISWA SUDAH MEMILIKI KELAS -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- KOLOM KIRI: INFO KELAS, JADWAL & PENGAJAR -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Banner Kelas -->
            <div class="bg-gradient-to-br from-blue-900 to-blue-950 rounded-3xl p-6 md:p-8 text-white shadow-xl relative overflow-hidden flex flex-col justify-between min-h-[220px]">

                <div class="z-10">
                    <div class="inline-flex items-center px-3 py-1 bg-yellow-400 text-blue-950 font-bold text-xs rounded-lg mb-3 shadow-sm">
                        <i class="fa-solid fa-layer-group mr-1"></i> Level: {{ $kelas->nama_level ?? 'Reguler' }}
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black tracking-tight">{{ $kelas->nama_kelas }}</h2>
                </div>

                <div class="z-10 mt-8 flex flex-col lg:flex-row lg:items-end justify-between gap-6">


                    <div class="flex flex-col md:flex-row md:items-center gap-4 md:gap-6 bg-white/5 px-5 py-4 rounded-2xl backdrop-blur-md border border-white/10 shadow-lg w-full lg:w-auto shrink-0">

                        <div class="flex items-center gap-3 w-full md:w-auto">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white border border-white/20 shrink-0">
                                <i class="fa-solid fa-user-tie text-sm"></i>
                            </div>
                            <div class="whitespace-nowrap">
                                <h4 class="text-[10px] font-bold text-blue-200/80 uppercase tracking-widest mb-0.5">Instruktur</h4>
                                <p class="text-sm font-bold text-white leading-tight">
                                    {{ $pengajar->nama_pengajar ?? 'Belum Ditentukan' }}
                                </p>
                            </div>
                        </div>

                        <div class="hidden md:block w-px h-8 bg-white/10"></div>

                        <div class="flex items-center gap-3 w-full md:w-auto">
                            <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-amber-300 border border-white/20 shrink-0">
                                <i class="fa-solid fa-clock text-sm"></i>
                            </div>
                            <div class="whitespace-nowrap">
                                <h4 class="text-[10px] font-bold text-blue-200/80 uppercase tracking-widest mb-0.5">Jadwal Kelas</h4>
                                <p class="text-sm font-bold text-white leading-tight flex items-center gap-1.5">
                                    @if(isset($jadwalTerdekat))
                                        <span>{{ $jadwalTerdekat->hari }}, {{ date('d M Y', strtotime($jadwalTerdekat->tanggal)) }}</span>
                                        <span class="w-1 h-1 rounded-full bg-amber-400"></span>
                                        <span class="text-amber-300">{{ date('H:i', strtotime($jadwalTerdekat->jam_mulai)) }} WITA</span>
                                    @else
                                        <span class="text-white/60 font-medium">Belum ditentukan</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                    </div>
                </div>

                <i class="fa-solid fa-chalkboard-user absolute -right-6 -bottom-10 text-[180px] text-white/5 rotate-12 pointer-events-none"></i>
            </div>

            <div class="mt-8 space-y-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">Informasi Kehadiran</h2>
                    <p class="text-sm text-slate-500 mt-1">Pantau kedisiplinan dan riwayat presensimu di kelas ini.</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white p-4 rounded-2xl border border-emerald-100 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-slate-800">{{ $hadir }}</div>
                            <div class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Hadir</div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-blue-100 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-envelope-open-text"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-slate-800">{{ $izin }}</div>
                            <div class="text-xs font-bold text-blue-600 uppercase tracking-wider">Izin</div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-amber-100 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-briefcase-medical"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-slate-800">{{ $sakit }}</div>
                            <div class="text-xs font-bold text-amber-600 uppercase tracking-wider">Sakit</div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded-2xl border border-rose-100 shadow-sm flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center text-xl">
                            <i class="fa-solid fa-user-xmark"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-slate-800">{{ $alfa }}</div>
                            <div class="text-xs font-bold text-rose-600 uppercase tracking-wider">Alfa</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden mt-4">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-sm font-bold text-slate-700">10 Pertemuan Terakhir</h3>
                    </div>
                    <div class="p-0">
                        <ul class="divide-y divide-slate-100">
                            @forelse($riwayatAbsensi as $absen)
                            <li class="flex items-center justify-between px-5 py-3 hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="text-slate-400"><i class="fa-regular fa-calendar-days"></i></div>
                                    <span class="text-sm font-medium text-slate-700">{{ date('d F Y', strtotime($absen->tanggal)) }}</span>
                                </div>

                                @if($absen->status == 'hadir')
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-lg text-xs font-bold uppercase">Hadir</span>
                                @elseif($absen->status == 'izin')
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 border border-blue-100 rounded-lg text-xs font-bold uppercase">Izin</span>
                                @elseif($absen->status == 'sakit')
                                    <span class="px-2.5 py-1 bg-amber-50 text-amber-700 border border-amber-100 rounded-lg text-xs font-bold uppercase">Sakit</span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-50 text-rose-700 border border-rose-100 rounded-lg text-xs font-bold uppercase">Alfa</span>
                                @endif
                            </li>
                            @empty
                            <li class="px-5 py-8 text-center text-sm text-slate-400">
                                Belum ada data kehadiran yang dicatat oleh instruktur.
                            </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>



        </div>

        <!-- KOLOM KANAN: DAFTAR TEMAN KELAS -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm flex flex-col h-full">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 rounded-t-2xl">
                <h3 class="font-bold text-slate-800 text-base flex items-center gap-2">
                    <i class="fa-solid fa-user-group text-blue-600"></i> Teman Sekelas
                </h3>
                <span class="text-xs bg-slate-200 text-slate-600 px-2 py-0.5 rounded-full font-bold">{{ $temanKelas->count() }} Teman</span>
            </div>

            <div class="p-2 flex-1 overflow-y-auto max-h-[400px]">
                <ul class="divide-y divide-slate-100">
                    <!-- Data Diri Sendiri (Siswa Login) -->
                    <li class="flex items-center justify-between p-3 bg-blue-50/50 rounded-xl mb-1">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold shadow-sm">
                                {{ strtoupper(substr(Auth::user()->nama ?? 'Y', 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900 leading-tight">Kamu (Saya)</p>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-amber-500 bg-white px-2 py-1 rounded-lg shadow-sm border border-amber-100">
                            <i class="fa-solid fa-star text-[10px]"></i> {{ $siswa->total_point ?? 0 }}
                        </div>
                    </li>

                    <!-- Looping Data Teman Lain -->
                    @forelse($temanKelas as $teman)
                    <li class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold">
                                {{ strtoupper(substr($teman->nama_lengkap, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-slate-700 leading-tight">{{ $teman->nama_lengkap }}</p>
                            </div>
                        </div>
                        <div class="text-xs font-bold text-amber-500">
                            <i class="fa-solid fa-star text-[10px]"></i> {{ $teman->total_point ?? 0 }}
                        </div>
                    </li>
                    @empty
                    <li class="p-6 text-center text-slate-400 text-xs">
                        Belum ada teman lain di kelas ini.
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    @else
    <!-- KONDISI 2: SISWA BELUM PUNYA KELAS -->
    <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-2xl mx-auto shadow-sm mt-8">
        <div class="w-24 h-24 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
            <i class="fa-solid fa-chalkboard"></i>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 mb-2">Belum Memiliki Kelas</h2>
        <p class="text-slate-500 text-sm mb-6 leading-relaxed">
            Kamu saat ini belum tergabung dalam rombongan belajar (kelas) manapun. Hal ini biasanya terjadi jika kamu masih menunggu hasil <span class="font-bold text-slate-700">Level Test</span> atau sedang dalam proses penyusunan jadwal oleh tim Admin SIBIJAR.
        </p>
        <a href="/siswa/level-test" class="inline-flex items-center gap-2 bg-blue-600 text-white font-bold px-6 py-2.5 rounded-xl hover:bg-blue-700 transition-all shadow-md">
            Cek Status Level Test <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
    @endif

</div>
@endsection
