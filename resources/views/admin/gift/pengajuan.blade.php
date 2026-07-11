@extends('layouts.main_admin')

@section('content')
<div class="p-6 space-y-6">

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm">
        <i class="fa-solid fa-circle-check text-emerald-500"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Antrean Pengajuan Tukar Hadiah</h1>
        <p class="text-sm text-slate-500 mt-1">Verifikasi kecukupan akumulasi poin prestasi siswa untuk ditukarkan dengan klaim merchandise.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-4">Siswa Pemohon</th>
                    <th class="px-6 py-4">Hadiah Diminta</th>
                    <th class="px-6 py-4 text-center">Syarat Poin (Poin Siswa / Syarat)</th>
                    <th class="px-6 py-4 text-right">Tindakan Verifikasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                @forelse($pengajuan as $item)
                <tr class="hover:bg-slate-50/40 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-900">{{ $item->nama_lengkap }}</div>
                        <div class="text-xs text-slate-400 mt-0.5">
                            Tgl Ajuan: {{ date('d M Y', strtotime($item->tanggal_penukaran)) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 font-medium text-slate-700">
                        <div>{{ $item->nama_gift }}</div>
                        <div class="text-xs text-slate-400 mt-0.5">Sisa Stok: {{ $item->stok }} Unit</div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <span class="px-2.5 py-1 rounded-xl text-xs font-bold {{ $item->total_point >= $item->poin_dibutuhkan ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100' }}">
                            {{ $item->total_point }} Stars / {{ $item->poin_dibutuhkan }} Stars
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">

                        @if($item->total_point >= $item->poin_dibutuhkan && $item->stok > 0)
                        <form action="/admin/pengajuan-gift/setujui/{{ $item->id_penukaran }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-sm flex items-center gap-1">
                                <i class="fa-solid fa-check"></i> Setujui
                            </button>
                        </form>
                        @else
                        <button type="button" class="px-3 py-1.5 bg-slate-100 text-slate-400 text-xs font-bold rounded-xl cursor-not-allowed" title="Poin Siswa Kurang atau Stok Habis" disabled>
                            <i class="fa-solid fa-ban"></i> Poin Kurang
                        </button>
                        @endif

                        <form action="/admin/pengajuan-gift/tolak/{{ $item->id_penukaran }}" method="POST" class="inline-block">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white border border-rose-200 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-1">
                                <i class="fa-solid fa-xmark"></i> Tolak
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 bg-white">
                        Bersih! Tidak ada antrean pengajuan penukaran hadiah hari ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
