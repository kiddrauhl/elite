@extends('layouts.main_admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h3 class="text-2xl font-bold text-slate-900 tracking-tight">Penempatan Registrasi Ulang Siswa</h3>
            <p class="text-sm text-slate-500 mt-1">Daftar riwayat dan antrean siswa yang telah melunasi tagihan registrasi ulang.</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ url('/admin/siswa/penempatan-lanjutan/export-excel') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all flex items-center gap-2">
                <i class="fa-solid fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ url('/admin/siswa/penempatan-lanjutan/export-pdf') }}" target="_blank" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all flex items-center gap-2">
                <i class="fa-solid fa-file-pdf"></i> Cetak PDF
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500 font-semibold">
                        <th class="px-6 py-4 whitespace-nowrap">No</th>
                        <th class="px-6 py-4 whitespace-nowrap">Nama Siswa</th>
                        <th class="px-6 py-4 whitespace-nowrap text-center">Rekomendasi Level Baru</th>
                        <th class="px-6 py-4 whitespace-nowrap">Status Bayar</th>
                        <th class="px-6 py-4 text-center whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-700">

                    @forelse($dataPenempatan as $index => $data)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-500">{{ $index + 1 }}</td>

                        <td class="px-6 py-4">
                            <p class="font-bold text-slate-800">{{ $data->nama_lengkap }}</p>
                            <p class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $data->order_id }}</p>
                        </td>

                        <td class="px-6 py-4">
                            @if($data->id_level_lanjutan)
                                <div class="flex items-center justify-center gap-3">
                                    <span class="text-xs font-medium text-slate-400 line-through">{{ $data->level_sekarang ?? 'Level Lama' }}</span>
                                    <i class="fa-solid fa-arrow-right-long text-blue-400 text-xs"></i>
                                    <span class="px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 font-bold text-xs border border-blue-100">
                                        {{ $data->level_tujuan ?? 'Level Baru' }}
                                    </span>
                                </div>
                            @else
                                <div class="text-center">
                                    <span class="block text-xs font-bold text-emerald-600">Aktif di {{ $data->level_sekarang }}</span>
                                    <span class="block text-[10px] font-medium text-slate-400 mt-0.5">
                                        <i class="fa-solid fa-chalkboard-user mr-1"></i> {{ $data->nama_kelas ?? 'Tanpa Kelas' }}
                                    </span>
                                </div>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-700 border border-emerald-200">
                                Lunas
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($data->id_level_lanjutan)
                                <a href="{{ url('/admin/siswa/penempatan-lanjutan/pilih-kelas/' . $data->id_siswa) }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg shadow-sm transition-all transform hover:-translate-y-0.5">
                                    <i class="fa-solid fa-check mr-1.5"></i> Tempatkan
                                </a>
                            @else
                                <span class="inline-flex items-center justify-center px-4 py-2 bg-slate-50 text-slate-500 text-xs font-bold rounded-lg border border-slate-200 cursor-not-allowed">
                                    <i class="fa-solid fa-check-double mr-1.5 text-emerald-500"></i> Selesai
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-clipboard-check text-2xl text-slate-300"></i>
                                </div>
                                <h4 class="text-slate-800 font-bold">Semua siswa sudah dialokasikan</h4>
                                <p class="text-slate-500 text-sm mt-1">Belum ada antrean penempatan kelas lanjutan saat ini.</p>
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
