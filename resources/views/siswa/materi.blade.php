@extends('layouts.main_siswa')

@section('content')
<div class="p-6 space-y-6 max-w-7xl mx-auto">

    <!-- Header Halaman -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Materi Belajar</h1>
            <p class="text-sm text-slate-500 mt-1">Unduh modul materi dari instrukturmu.</p>
        </div>

        @if($kelas)
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-xl border border-blue-100 font-bold text-sm shadow-sm">
            <i class="fa-solid fa-chalkboard-user"></i> Kelas: {{ $kelas->nama_kelas }}
        </div>
        @endif
    </div>

    @if(!$kelas)
    <!-- KONDISI 1: SISWA BELUM PUNYA KELAS -->
    <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center max-w-2xl mx-auto shadow-sm mt-8">
        <div class="w-24 h-24 bg-slate-50 text-slate-400 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
            <i class="fa-solid fa-folder-open"></i>
        </div>
        <h2 class="text-2xl font-bold text-slate-900 mb-2">Akses Materi Dikunci</h2>
        <p class="text-slate-500 text-sm mb-6 leading-relaxed">
            Kamu belum tergabung dalam rombongan kelas manapun, sehingga akses modul belajar belum terbuka.
        </p>
    </div>

    @elseif($materi->isEmpty())
    <!-- KONDISI 2: KELAS SUDAH ADA, TAPI BELUM ADA MATERI DARI PENGAJAR -->
    <div class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-sm">
        <div class="w-24 h-24 bg-blue-50 text-blue-300 rounded-full flex items-center justify-center text-4xl mx-auto mb-4">
            <i class="fa-regular fa-folder-closed"></i>
        </div>
        <h3 class="text-lg font-bold text-slate-800">Materi Masih Kosong</h3>
        <p class="text-sm text-slate-500 mt-1">Instruktur kelasmu belum mengunggah materi apapun.</p>
    </div>

    @else
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($materi as $item)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-blue-300 transition-all group flex flex-col h-full">

            <div class="p-5 flex-1 space-y-4">
                <div class="flex justify-between items-start">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl group-hover:bg-blue-600 group-hover:text-white transition-colors shadow-sm">

                        @if(Str::endsWith($item->file_materi ?? '', ['.pdf']))
                            <i class="fa-solid fa-file-pdf"></i>
                        @elseif(Str::endsWith($item->file_materi ?? '', ['.doc', '.docx']))
                            <i class="fa-solid fa-file-word"></i>
                        @else
                            <i class="fa-solid fa-file-lines"></i>
                        @endif

                    </div>
                    <span class="text-[10px] font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                        <i class="fa-solid fa-cloud-arrow-up me-1 text-blue-500"></i>
                        Diunggah: {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                    </span>
                </div>

                <div>
                    <h3 class="text-lg font-bold text-slate-900 leading-tight group-hover:text-blue-700 transition-colors">
                        {{ $item->judul_materi }}
                    </h3>
                    <p class="text-sm text-slate-500 mt-2 line-clamp-3 leading-relaxed">
                        {{ $item->deskripsi ?? 'Tidak ada deskripsi tambahan untuk materi ini.' }}
                    </p>
                </div>
            </div>

            <div class="p-5 pt-0 mt-auto">
                <a href="{{ route('siswa.materi.download', $item->id_materi) }}" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-yellow-400 text-slate-700 hover:text-blue-950 font-bold text-sm rounded-xl transition-colors">
                    <i class="fa-solid fa-cloud-arrow-down"></i> Unduh File
                </a>
            </div>

        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
