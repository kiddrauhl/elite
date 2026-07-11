@extends('layouts.main_admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Lihat Daftar Pembayaran</h3>
            <p class="text-sm text-slate-500 mt-1">Monitor status pelunasan tagihan pendaftar secara real-time.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                        <th class="px-6 py-4 whitespace-nowrap">No</th>
                        <th class="px-6 py-4 whitespace-nowrap">Order ID</th>
                        <th class="px-6 py-4 whitespace-nowrap">Nama Pendaftar</th>
                        <th class="px-6 py-4 whitespace-nowrap">Program Level</th>
                        <th class="px-6 py-4 whitespace-nowrap">Nominal</th>
                        <th class="px-6 py-4 text-center whitespace-nowrap">Status Midtrans</th>
                        <th class="px-6 py-4 text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">

                    @forelse($dataPembayaran as $index => $bayar)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-500">{{ $index + 1 }}</td>

                        <td class="px-6 py-4">
                            <span class="font-mono text-xs text-slate-500 bg-slate-100 px-2 py-1 rounded-md border border-slate-200">
                                {{ $bayar->order_id }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800">{{ $bayar->nama_lengkap }}</p>
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 font-medium text-xs border border-blue-100">
                                {{ $bayar->nama_level ?? 'Belum Ditentukan' }}
                            </span>
                        </td>

                        <td class="px-6 py-4 font-bold text-slate-800">
                            Rp {{ number_format($bayar->jumlah_bayar, 0, ',', '.') }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($bayar->status_verifikasi == 'settlement')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 shadow-[0_0_5px_rgba(16,185,129,0.5)]"></div> Lunas
                                </span>
                            @elseif($bayar->status_verifikasi == 'pending')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200">
                                    <div class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 shadow-[0_0_5px_rgba(245,158,11,0.5)]"></div> Menunggu
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200">
                                    <div class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5 shadow-[0_0_5px_rgba(244,63,94,0.5)]"></div> Gagal/Expired
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($bayar->status_verifikasi == 'settlement' && $bayar->status_pendaftar != 'diterima')
                                <a href="{{ url('/admin/penempatan') }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm shadow-blue-600/20 transition-all transform hover:-translate-y-0.5">
                                    Tempatkan Kelas
                                </a>
                            @elseif($bayar->status_pendaftar == 'diterima')
                                <span class="inline-flex items-center text-xs font-bold text-slate-400 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200">
                                    <i class="fa-solid fa-check-circle mr-1.5 text-emerald-500"></i> Selesai
                                </span>
                            @else
                                <span class="text-slate-300 font-bold">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-receipt text-2xl text-slate-300"></i>
                                </div>
                                <h4 class="text-slate-800 font-bold">Belum ada transaksi</h4>
                                <p class="text-slate-500 text-sm mt-1">Data pembayaran pendaftar akan muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
