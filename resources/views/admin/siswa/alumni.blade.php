@extends('layouts.main_admin')

@section('content')
<div class="p-6 space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Data Alumni (Lulusan)</h1>
        <p class="text-sm text-slate-500 mt-1">Daftar siswa yang telah menyelesaikan level Expert. Anda dapat menerbitkan E-Sertifikat kelulusan di sini.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Alumni</th>
                        <th class="px-6 py-4">Kontak</th>
                        <th class="px-6 py-4">Kelas Terakhir</th>
                        <th class="px-6 py-4 text-center">Status Sertifikat</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    @forelse($alumni as $data)
                    <tr class="hover:bg-slate-50/40 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $data->nama_lengkap }}</td>
                        <td class="px-6 py-4">
                            {{ $data->email }} <br>
                            <span class="text-xs text-slate-400">{{ $data->no_hp }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $data->nama_kelas ?? 'Tidak Diketahui' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(is_null($data->file_sertifikat))
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                    Belum Diterbitkan
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    <i class="fa-solid fa-check mr-1.5"></i> Diterbitkan
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(is_null($data->file_sertifikat))
                                <!-- Cari bagian ini di dalam tag <td> Aksi -->
                                <a href="{{ url('/admin/alumni/terbitkan/' . $data->id_siswa) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors shadow-sm">
                                    <i class="fa-solid fa-award mr-1.5"></i> Terbitkan
                                </a>
                            @else
                                <!-- Tombol ini nantinya akan diarahkan untuk melihat file PDF yang sudah jadi -->
                                <!-- Ganti bagian href="#" pada tombol Lihat PDF menjadi seperti ini: -->
                                <a href="{{ asset('sertifikat/' . $data->file_sertifikat) }}" target="_blank" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-300 hover:bg-slate-50 rounded-lg transition-colors shadow-sm">
                                    <i class="fa-solid fa-file-pdf text-red-500 mr-1.5"></i> Lihat PDF
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-user-graduate text-4xl block mb-3 text-slate-300"></i>
                            <p class="text-xs font-medium">Belum ada data alumni yang tercatat di sistem.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $alumni->links() }}
        </div>
    </div>
</div>
@endsection
