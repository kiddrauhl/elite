@php
    // Kita petakan kolom database ke nama level masing-masing
    $list_sertifikat = [
        1 => ['nama' => 'Beginner', 'file' => $siswa->sertifikat_level_1 ?? null],
        2 => ['nama' => 'Intermediate',  'file' => $siswa->sertifikat_level_2 ?? null],
        3 => ['nama' => 'Regular', 'file' => $siswa->sertifikat_level_3 ?? null],
        4 => ['nama' => 'Expert',   'file' => $siswa->sertifikat_level_4 ?? null],
    ];

    // Mengecek apakah siswa setidaknya punya 1 sertifikat
    $sertifikat_tersedia = false;
    foreach($list_sertifikat as $s) {
        if(!is_null($s['file'])) {
            $sertifikat_tersedia = true;
            break;
        }
    }
@endphp

@extends('layouts.main_siswa')

@section('content')
<div class="py-6 px-4 sm:px-6 max-w-5xl mx-auto space-y-8">

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Koleksi E-Sertifikat</h1>
        <p class="text-sm text-slate-500 mt-2">Dokumen resmi pencapaian belajar Anda di Elite English Course.</p>
    </div>

    @if(!$sertifikat_tersedia)
        <!-- Jika Belum Ada Sertifikat Sama Sekali -->
        <div class="bg-white rounded-3xl border border-slate-200 p-10 text-center shadow-sm">
            <div class="w-20 h-20 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-5">
                <i class="fa-solid fa-file-circle-xmark text-4xl"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Sertifikat</h3>
            <p class="text-slate-500 text-sm max-w-md mx-auto">
                E-Sertifikat Anda akan diterbitkan setelah Admin selesai mengurus dan memverifikasi berkas kelulusan pada level pembelajaran ini. Mohon menunggu proses penerbitan, dan terus semangat belajar!
            </p>
        </div>
    @endif

    <!-- Tampilan Grid untuk 4 Level -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($list_sertifikat as $level => $data)

            @if(!is_null($data['file']))
                <!-- KARTU AKTIF: Jika Sertifikat Sudah Diterbitkan -->
                <div class="bg-gradient-to-br from-slate-900 to-blue-950 rounded-3xl p-1 relative overflow-hidden shadow-lg group">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-32 h-32 bg-amber-500 rounded-full blur-3xl opacity-20 group-hover:opacity-40 transition-opacity pointer-events-none"></div>

                    <div class="bg-white rounded-[23px] p-8 text-center relative z-10 h-full flex flex-col justify-between">
                        <div>
                            <div class="w-16 h-16 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                                <i class="fa-solid fa-award text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-black text-slate-900 mb-1">Level {{ $data['nama'] }}</h3>
                            <p class="text-slate-500 text-xs mb-8">
                                Sertifikat kelulusan tingkat <b>{{ $data['nama'] }}</b> Anda telah resmi diterbitkan.
                            </p>
                        </div>

                        <a href="{{ asset('sertifikat/' . $data['file']) }}" target="_blank" class="w-full inline-flex items-center justify-center px-4 py-3.5 text-sm font-bold text-white bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 rounded-xl transition-all shadow-md shadow-amber-500/20 transform hover:-translate-y-0.5">
                            <i class="fa-solid fa-download mr-2"></i> Unduh Sertifikat
                        </a>
                    </div>
                </div>
            @else
                <!-- KARTU TERKUNCI: Jika Sertifikat Belum Dimiliki -->
                <div class="bg-slate-50 rounded-3xl border border-slate-200 border-dashed p-8 text-center flex flex-col justify-center items-center h-full min-h-[260px]">
                    <div class="w-16 h-16 bg-slate-200 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-lock text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-400 mb-1">Level {{ $data['nama'] }}</h3>
                    <p class="text-slate-400 text-xs max-w-[200px] mx-auto">
                        Sertifikat terkunci. Selesaikan level ini terlebih dahulu.
                    </p>
                </div>
            @endif

        @endforeach
    </div>

</div>
@endsection
