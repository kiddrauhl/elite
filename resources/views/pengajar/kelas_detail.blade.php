@extends('layouts.main_pengajar')
@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0 transform translate-y-4"
    class="fixed bottom-10 right-10 z-50 bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3 rounded-2xl text-sm font-bold flex items-center gap-3 shadow-lg shadow-emerald-500/10">
    <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
    <span>{{ session('success') }}</span>
</div>
@endif
@section('content')

<div class="p-6 space-y-6 max-w-6xl mx-auto">

    <a href="/pengajar/kelas" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Kelas
    </a>

    <div class="bg-gradient-to-r from-blue-900 to-indigo-950 rounded-3xl p-8 text-white shadow-lg relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="z-10">
            <span class="px-3 py-1 bg-yellow-400 text-blue-950 text-xs font-black uppercase rounded-lg mb-3 inline-block shadow-sm">
                {{ $kelas->nama_level ?? 'Level' }}
            </span>
            <h1 class="text-3xl font-black">{{ $kelas->nama_kelas }}</h1>

            <p class="text-blue-200 text-sm mt-2 flex flex-wrap items-center gap-4">
                @if(isset($jadwalAktif) && $jadwalAktif)
                    <span>
                        <i class="fa-regular fa-calendar-check mr-1.5"></i>
                        {{ $jadwalAktif->hari }}, {{ date('d M Y', strtotime($jadwalAktif->tanggal)) }}
                    </span>
                    <span>
                        <i class="fa-regular fa-clock mr-1.5"></i>
                        {{ date('H:i', strtotime($jadwalAktif->jam_mulai)) }} - {{ date('H:i', strtotime($jadwalAktif->jam_selesai)) }} WITA
                    </span>
                @else
                    <span class="opacity-80 italic">
                        <i class="fa-solid fa-calendar-xmark mr-1.5"></i> Jadwal belum ditentukan
                    </span>
                @endif
            </p>
        </div>
        <div class="z-10 bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/20 text-center">
            <p class="text-xs font-bold text-blue-200 uppercase tracking-wider mb-1">Total Siswa</p>
            <p class="text-3xl font-black text-yellow-400">{{ $siswa->count() }} <span class="text-sm font-medium text-white">Anak</span></p>
        </div>
        <i class="fa-solid fa-users-rectangle absolute -right-10 -bottom-10 text-[150px] text-white/5 rotate-12"></i>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-list-ol text-blue-600"></i> Daftar Peserta Didik
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Lengkap</th>
                        <th class="px-6 py-4">Nomor HP / WA</th>
                        <th class="px-6 py-4 text-center">Total Point Stars</th>
                        <th class="px-6 py-4 text-center">Presensi Hari Ini</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($siswa as $index => $s)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-400">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $s->nama_lengkap }}</div>
                                <div class="text-[10px] text-slate-400 font-medium">ID: {{ str_pad($s->id_siswa, 4, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-600">
                                <a href="https://wa.me/{{ $s->no_hp }}" target="_blank" class="hover:text-emerald-600 inline-flex items-center gap-1">
                                    <i class="fa-brands fa-whatsapp text-emerald-500"></i> {{ $s->no_hp ?? '-' }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-amber-50 text-amber-600 font-bold text-xs rounded-lg border border-amber-100">
                                    <i class="fa-solid fa-star text-[10px]"></i> {{ $s->total_point ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('pengajar.absensi.simpan') }}" method="POST" class="flex items-center justify-center gap-2">
                                    @csrf
                                    <input type="hidden" name="id_siswa" value="{{ $s->id_siswa }}">
                                    <input type="hidden" name="id_kelas" value="{{ $kelas->id_kelas }}">

                                    @php

                                        $status = $s->status_absensi ?? null;
                                    @endphp

                                    <button type="submit" name="status" value="hadir" title="Hadir"
                                        class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300
                                        {{ $status == 'hadir' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/40 scale-110 ring-2 ring-emerald-200' : 'bg-slate-100 text-slate-400 hover:bg-emerald-100 hover:text-emerald-600' }}">
                                        H
                                    </button>

                                    <button type="submit" name="status" value="izin" title="Izin"
                                        class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300
                                        {{ $status == 'izin' ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/40 scale-110 ring-2 ring-blue-200' : 'bg-slate-100 text-slate-400 hover:bg-blue-100 hover:text-blue-600' }}">
                                        I
                                    </button>

                                    <button type="submit" name="status" value="sakit" title="Sakit"
                                        class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300
                                        {{ $status == 'sakit' ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/40 scale-110 ring-2 ring-amber-200' : 'bg-slate-100 text-slate-400 hover:bg-amber-100 hover:text-amber-600' }}">
                                        S
                                    </button>

                                    <button type="submit" name="status" value="alfa" title="Alfa"
                                        class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs transition-all duration-300
                                        {{ $status == 'alfa' ? 'bg-rose-500 text-white shadow-lg shadow-rose-500/40 scale-110 ring-2 ring-rose-200' : 'bg-slate-100 text-slate-400 hover:bg-rose-100 hover:text-rose-600' }}">
                                        A
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                                <i class="fa-solid fa-user-xmark text-3xl mb-2 text-slate-300 block"></i>
                                Belum ada siswa yang dimasukkan ke dalam kelas ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
