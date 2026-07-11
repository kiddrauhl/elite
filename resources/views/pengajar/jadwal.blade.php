@extends('layouts.main_pengajar')

@section('content')
<div class="p-6 space-y-8 max-w-7xl mx-auto">

    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Jadwal Mengajar</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola agenda tatap muka bimbingan belajar dan pantau waktu mengajar Anda.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-8">

        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-regular fa-calendar-days text-blue-600"></i> Kalender Mengajar
            </h3>

            <form method="GET" action="{{ url()->current() }}" class="flex items-center gap-2">
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

                        // 🌟 PERBAIKAN: Ubah $jadwalAjar menjadi $semuaJadwal sesuai dengan Controller
                        $jadwalHariIni = $semuaJadwal->where('tanggal', $currentDateStr);

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


    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-list-check text-slate-400"></i> Semua Log & Agenda Mengajar
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-3.5">Tanggal / Hari</th>
                        <th class="px-6 py-3.5">Jam Mengajar</th>
                        <th class="px-6 py-3.5">Rombel Kelas</th>
                        <th class="px-6 py-3.5">Level</th>
                        <th class="px-6 py-3.5">Keterangan / Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($semuaJadwal as $jadwal)
                        @php
                            $isPast = \Carbon\Carbon::parse($jadwal->tanggal)->isPast() && !(\Carbon\Carbon::parse($jadwal->tanggal)->isToday());
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors {{ $isPast ? 'opacity-60' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ date('d M Y', strtotime($jadwal->tanggal)) }}</div>
                                <div class="text-xs text-slate-400 font-medium">Hari {{ $jadwal->hari }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center gap-1 font-semibold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-lg text-xs border border-blue-100">
                                    <i class="fa-regular fa-clock text-[10px]"></i>
                                    {{ date('H:i', strtotime($jadwal->jam_mulai)) }} - {{ date('H:i', strtotime($jadwal->jam_selesai)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-slate-800">
                                {{ $jadwal->nama_kelas }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 font-bold text-[11px] rounded uppercase border">
                                    {{ $jadwal->nama_level }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 font-medium max-w-xs truncate">
                                {{ $jadwal->keterangan ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-400 text-sm">
                                <i class="fa-solid fa-inbox text-3xl block mb-2 text-slate-300"></i>
                                Belum ada rekam data jadwal belajar yang dimasukkan ke sistem.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>



@endsection
