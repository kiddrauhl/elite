@extends('layouts.main_admin')

@section('content')
<div class="p-6 space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Riwayat Penukaran Hadiah</h1>
        <p class="text-sm text-slate-500 mt-1">Rekam jejak seluruh permohonan klaim merchandise yang telah disetujui atau ditolak.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-4">Siswa Pemohon</th>
                    <th class="px-6 py-4">Hadiah Diklaim</th>
                    <th class="px-6 py-4">Tanggal Diproses</th>
                    <th class="px-6 py-4 text-right">Status Akhir</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                @forelse($riwayat as $item)
                <tr class="hover:bg-slate-50/40 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-900">{{ $item->nama_lengkap }}</div>
                        <div class="text-xs text-slate-400 mt-0.5">
                            Tgl Ajuan: {{ date('d M Y', strtotime($item->tanggal_penukaran)) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-slate-700">
                        <div>{{ $item->nama_gift }}</div>
                        <div class="text-xs text-amber-500 mt-0.5 font-bold"><i class="fa-solid fa-star"></i> {{ $item->poin_dibutuhkan }} Point</div>
                    </td>
                    <td class="px-6 py-4 text-slate-500">
                        {{ date('d M Y, H:i', strtotime($item->updated_at)) }}
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap">
                        @if(strtolower($item->status) == 'selesai' || strtolower($item->status) == 'proses')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <i class="fa-solid fa-check-circle"></i> Disetujui
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                <i class="fa-solid fa-xmark-circle"></i> Ditolak
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 bg-white">
                        <div class="text-4xl mb-3 text-slate-200"><i class="fa-solid fa-clock-rotate-left"></i></div>
                        Belum ada riwayat penukaran hadiah yang diproses.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
