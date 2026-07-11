@extends('layouts.main_pengajar')

@section('content')
<div class="p-6 space-y-8 max-w-7xl mx-auto">

    <!-- Banner Salam -->
    <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-blue-950 text-white p-8 rounded-3xl shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
        <div class="space-y-2 z-10">
            <h1 class="text-3xl font-extrabold tracking-tight">Halo, {{ $pengajar->nama_pengajar ?? Auth::user()->nama ?? 'Instruktur' }}!</h1>
            <p class="text-blue-200 text-sm max-w-md">Selamat datang di Panel Pengajar. Mari bantu para siswa mencapai potensi maksimal mereka hari ini.</p>
        </div>
        <i class="fa-solid fa-book-open-reader absolute -right-4 -bottom-8 text-[120px] text-white/10 rotate-12"></i>
    </div>

    @if(isset($jadwalHariIni) && $jadwalHariIni->count() > 0)
        <div class="mb-8 bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-5 md:p-6 text-white shadow-lg shadow-blue-900/20 relative overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-5 border border-blue-500">

            <i class="fa-solid fa-bell absolute -right-4 -top-6 text-9xl text-white/10 -rotate-12 pointer-events-none"></i>

            <div class="flex items-center gap-4 relative z-10 w-full">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-yellow-300 shrink-0 border border-white/20 backdrop-blur-sm animate-pulse shadow-inner">
                    <i class="fa-solid fa-bell text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white mb-0.5 tracking-tight">Pengingat Jadwal Hari Ini!</h3>
                    <p class="text-sm text-blue-100 leading-relaxed">
                        Anda memiliki <span class="font-bold text-yellow-300 px-1">{{ $jadwalHariIni->count() }} kelas</span> yang harus diajar hari ini.
                        Persiapkan diri Anda, kelas pertama akan dimulai pukul <span class="font-bold text-white bg-white/20 px-1.5 py-0.5 rounded ml-0.5">{{ date('H:i', strtotime($jadwalHariIni->first()->jam_mulai)) }} WITA</span>.
                    </p>
                </div>
            </div>

            <div class="relative z-10 shrink-0 w-full sm:w-auto mt-2 sm:mt-0">
                <a href="{{ route('pengajar.jadwal') ?? '#' }}" class="block text-center px-6 py-3 bg-yellow-400 hover:bg-yellow-500 text-blue-950 font-black text-sm rounded-xl transition-all hover:scale-105 shadow-md shadow-yellow-400/20 whitespace-nowrap">
                    Buka Jadwal Saya <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>

        </div>
    @endif

    <!-- Statistik Singkat -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between group hover:border-blue-300 transition-colors">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Kelas Diajar</p>
                <h3 class="text-4xl font-black text-blue-600 mt-1">{{ $kelasAjar->count() }} <span class="text-sm font-medium text-slate-500">Rombel</span></h3>
            </div>
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-3xl shadow-inner group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <i class="fa-solid fa-chalkboard"></i>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-between group hover:border-amber-300 transition-colors">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Siswa Didik</p>
                <h3 class="text-4xl font-black text-amber-500 mt-1">{{ $totalSiswa }} <span class="text-sm font-medium text-slate-500">Siswa</span></h3>
            </div>
            <div class="w-16 h-16 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center text-3xl shadow-inner group-hover:bg-amber-500 group-hover:text-white transition-colors">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
    </div>

    <!-- KALENDER JADWAL MINGGUAN -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-regular fa-calendar-days text-blue-600"></i> Jadwal Mengajar
            </h3>

            <form method="GET" action="{{ route('pengajar.dashboard') }}" class="flex items-center gap-2">
                <select name="month" onchange="this.form.submit()" class="text-sm border-slate-200 rounded-xl px-3 py-1.5 focus:ring-blue-500 focus:border-blue-500 bg-white font-medium text-slate-700 shadow-sm cursor-pointer">
                    @php
                        $bulanIndo = ['01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'];
                    @endphp
                    @foreach($bulanIndo as $num => $name)
                        <option value="{{ $num }}" {{ $selectedMonth == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>

                <select name="year" onchange="this.form.submit()" class="text-sm border-slate-200 rounded-xl px-3 py-1.5 focus:ring-blue-500 focus:border-blue-500 bg-white font-medium text-slate-700 shadow-sm cursor-pointer">
                    @for($y = date('Y') - 1; $y <= date('Y') + 2; $y++)
                        <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>
        </div>

        <div class="w-full overflow-x-auto">
            <div class="min-w-[800px] border-t border-l border-slate-200 bg-slate-200 grid grid-cols-7 gap-px">

                @foreach(['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $namaHari)
                    <div class="bg-slate-100 py-3 text-center text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                        {{ $namaHari }}
                    </div>
                @endforeach

                @for($i = 0; $i < $startDayOfWeek; $i++)
                    <div class="bg-slate-50/50 min-h-[120px]"></div>
                @endfor

                @for($day = 1; $day <= $daysInMonth; $day++)
                    @php
                        // Format tanggal untuk pencocokan (YYYY-MM-DD)
                        $currentDateStr = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $day);
                        // Filter jadwal khusus tanggal di kotak ini
                        $jadwalHariIni = $jadwalMengajar->where('tanggal', $currentDateStr);
                        // Cek apakah ini hari ini
                        $isToday = $currentDateStr == date('Y-m-d');
                    @endphp

                    <div class="bg-white min-h-[120px] p-2 hover:bg-slate-50 transition-colors relative group {{ $isToday ? 'bg-yellow-50/30' : '' }}">

                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-bold {{ $isToday ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-400' }}">
                            {{ $day }}
                        </span>

                        <div class="mt-2 space-y-1.5 h-[85px] overflow-y-auto pr-1 custom-scrollbar">
                            @foreach($jadwalHariIni as $jadwal)
                                <div class="bg-blue-50 hover:bg-blue-100 border border-blue-100 rounded-lg p-1.5 cursor-default transition-colors relative group/item">
                                    <div class="text-[9px] font-bold text-blue-700 mb-0.5 flex items-center gap-1">
                                        <i class="fa-regular fa-clock"></i> {{ date('H:i', strtotime($jadwal->jam_mulai)) }}
                                    </div>
                                    <div class="font-bold text-slate-800 text-[11px] leading-tight line-clamp-1">
                                        {{ $jadwal->nama_kelas }}
                                    </div>

                                    <div class="absolute z-20 left-1/2 -translate-x-1/2 bottom-full mb-1 hidden group-hover/item:block bg-slate-800 text-white text-[10px] p-2 rounded-lg shadow-lg w-32 whitespace-normal text-center">
                                        <strong>{{ $jadwal->nama_kelas }}</strong><br>
                                        {{ date('H:i', strtotime($jadwal->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam_selesai)) }}<br>
                                        <span class="text-yellow-400">{{ $jadwal->nama_level }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>
                @endfor

                @php
                    $sisaKotak = (7 - (($startDayOfWeek + $daysInMonth) % 7)) % 7;
                @endphp
                @for($i = 0; $i < $sisaKotak; $i++)
                    <div class="bg-slate-50/50 min-h-[120px]"></div>
                @endfor

            </div>
        </div>
    </div>

</div>
@endsection
