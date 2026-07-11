@extends('layouts.main_pengajar')

@section('content')
<div class="p-6 space-y-6 max-w-7xl mx-auto">

    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Data Kelas Saya</h1>
        <p class="text-sm text-slate-500 mt-1">Pilih kelas di bawah ini untuk melihat daftar siswa dan mengelola administrasi kelas.</p>
    </div>

    @if(session('error'))
        <div class="bg-rose-50 text-rose-700 p-4 rounded-xl font-bold text-sm border border-rose-200">
            <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($kelasList as $k)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-300 transition-all overflow-hidden flex flex-col">
                <div class="p-5 border-b border-slate-100 flex justify-between items-start bg-slate-50/50">
                    <span class="px-2.5 py-1 bg-yellow-100 text-amber-800 text-[10px] font-bold uppercase rounded-md">
                        {{ $k->nama_level ?? 'Level' }}
                    </span>
                </div>

                <div class="p-5 flex-1 space-y-3">
                    <h3 class="text-xl font-black text-slate-900 leading-tight">{{ $k->nama_kelas }}</h3>

                    <div class="space-y-1 mt-2">
                        @if(isset($k->jadwal_terdekat) && $k->jadwal_terdekat)
                            <p class="text-sm text-slate-600 flex items-center gap-2">
                                <i class="fa-solid fa-calendar-day text-slate-400 w-4"></i>
                                <span class="font-medium">{{ $k->jadwal_terdekat->hari }}, {{ date('d M Y', strtotime($k->jadwal_terdekat->tanggal)) }}</span>
                            </p>
                            <p class="text-sm text-slate-600 flex items-center gap-2">
                                <i class="fa-regular fa-clock text-slate-400 w-4"></i>
                                <span class="font-medium">{{ date('H:i', strtotime($k->jadwal_terdekat->jam_mulai)) }} - {{ date('H:i', strtotime($k->jadwal_terdekat->jam_selesai)) }} WITA</span>
                            </p>
                        @else
                            <p class="text-sm text-slate-400 flex items-center gap-2 italic">
                                <i class="fa-solid fa-calendar-xmark w-4"></i> Jadwal belum diatur admin
                            </p>
                            <p class="text-sm text-transparent select-none flex items-center gap-2">
                                <i class="fa-regular fa-clock w-4"></i> -
                            </p>
                        @endif
                    </div>
                </div> <div class="p-5 pt-0">
                    <a href="{{ route('pengajar.kelas.detail', [$k->id_kelas, $k->id_level]) }}" class="w-full inline-flex items-center justify-center gap-2 py-2.5 bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white font-bold text-sm rounded-xl transition-colors">
                        Buka Kelas <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                <i class="fa-solid fa-chalkboard text-4xl text-slate-300 mb-3"></i>
                <h3 class="font-bold text-slate-700">Belum Ada Kelas</h3>
                <p class="text-sm text-slate-500">Anda belum ditugaskan untuk mengajar di kelas manapun.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
