@extends('layouts.main_admin')

@section('content')
<div class="p-6 space-y-6">

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm">
        <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Daftar Jadwal Level Test</h1>
            <p class="text-sm text-slate-500 mt-1">Memantau seluruh agenda aktif ujian penempatan level kelas calon siswa SIBIJAR.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Pendaftar</th>
                        <th class="px-6 py-4">Kontak WhatsApp</th>
                        <th class="px-6 py-4">Agenda Uji</th>
                        <th class="px-6 py-4">Status Sistem</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">

                    @forelse($jadwal as $item)
                    <tr class="hover:bg-slate-50/40 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-900">{{ $item->nama_lengkap }}</div>
                            <div class="text-[11px] text-slate-400 mt-0.5">ID Pendaftar: #{{ $item->id_pendaftar }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-slate-700"><i class="fa-brands fa-whatsapp text-emerald-500"></i> {{ $item->no_hp }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-semibold text-blue-600 flex items-center gap-1">
                                <i class="fa-solid fa-calendar-day"></i> Terjadwal Mendatang
                            </div>
                            <div class="text-[11px] text-slate-400 mt-0.5">Silakan lakukan pengecekan</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                Sudah Dijadwalkan
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-calendar-xmark text-3xl block mb-2 text-slate-300"></i>
                            <p class="text-xs font-medium">Belum ada agenda jadwal test aktif saat ini.</p>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
