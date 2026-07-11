@extends('layouts.main_pengajar')

@section('content')
<div class="p-6 space-y-6 max-w-7xl mx-auto">

    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">E-Raport Siswa</h1>
        <p class="text-sm text-slate-500 mt-1">Pilih kelas di bawah ini untuk mulai mengisi nilai akhir dan raport siswa.</p>
    </div>

    @if(session('error'))
        <div class="bg-rose-50 text-rose-700 p-4 rounded-xl font-bold text-sm border border-rose-200">
            <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($kelasAjar as $k)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-300 transition-all overflow-hidden flex flex-col">
                <div class="p-5 border-b border-slate-100 flex justify-between items-start bg-slate-50/50">
                    <span class="px-2.5 py-1 bg-blue-100 text-blue-800 text-[10px] font-bold uppercase rounded-md">
                        {{ $k->nama_level ?? 'Level' }}
                    </span>
                    <i class="fa-solid fa-award text-blue-300 text-xl"></i>
                </div>

                <div class="p-5 flex-1 space-y-3">
                    <h3 class="text-xl font-black text-slate-900 leading-tight">{{ $k->nama_kelas }}</h3>
                    <p class="text-sm text-slate-500 line-clamp-2">Masukkan nilai kemampuan bahasa Inggris untuk siswa di kelas ini.</p>
                </div>

                <div class="px-5 pb-5 mt-auto">
                    <a href="{{ route('pengajar.raport.siswa', [$k->id_kelas, $k->id_level]) }}" class="w-full inline-flex items-center justify-center gap-2 py-3 bg-yellow-400 hover:bg-yellow-500 text-slate-900 font-bold text-sm rounded-xl transition-all shadow-sm hover:shadow-md">
                        Buka Form Nilai <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                <i class="fa-solid fa-chalkboard text-4xl text-slate-300 mb-3"></i>
                <h3 class="font-bold text-slate-700">Belum Ada Kelas</h3>
                <p class="text-sm text-slate-500">Anda belum memiliki kelas untuk dievaluasi.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection
