@extends('layouts.main')

@section('content')
<div class="mt-8 mb-24">
    <div class="text-center mb-16">
        <h2 class="text-3xl lg:text-4xl font-bold text-blue-900 mb-4">Daftar Program & Level Kelas</h2>
        <p class="text-blue-800 text-lg max-w-2xl mx-auto">
            Temukan kelas yang paling sesuai dengan tingkatan kemampuan Anda. Kami menyediakan program terstruktur dari dasar hingga mahir.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

        @php
            $levels = [
                ['name' => 'Tingkat Dasar', 'desc' => 'Cocok untuk siswa yang baru mulai mempelajari bahasa Inggris dasar.'],
                ['name' => 'Menengah', 'desc' => 'Fokus pada tata bahasa (Grammar) dan percakapan sehari-hari.'],
                ['name' => 'Lanjutan', 'desc' => 'Pendalaman materi akademis untuk diskusi dan literatur bahasa.'],
                ['name' => 'Persiapan Tes', 'desc' => 'Materi khusus untuk persiapan ujian seperti TOEFL dan IELTS.'],
            ];
        @endphp

        @foreach ($levels as $index => $level)
        <div class="bg-blue-100 rounded-2xl border border-blue-200 shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden flex flex-col">

            <div class="bg-blue-900 p-6 text-center border-b-4 border-yellow-400">
                <span class="text-yellow-400 font-bold tracking-widest text-sm uppercase">Level {{ $index + 1 }}</span>
                <h3 class="text-blue-50 font-bold text-2xl mt-1">{{ $level['name'] }}</h3>
            </div>

            <div class="p-6 flex-grow flex flex-col justify-between">
                <div>
                    <h4 class="font-bold text-blue-900 mb-2">Elite English Course</h4>
                    <p class="text-blue-800 mb-6 leading-relaxed text-sm">
                        {{ $level['desc'] }}
                    </p>
                </div>

                <a href="#" class="block text-center w-full bg-yellow-400 text-blue-900 font-bold py-3 rounded-lg hover:bg-yellow-500 transition duration-200">
                    Pilih Kelas Ini
                </a>
            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection
