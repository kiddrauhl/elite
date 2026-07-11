@extends('layouts.main_admin')

@section('content')
<div class="p-6 space-y-6 max-w-7xl mx-auto">

    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Penempatan Kelas dan Level Pendaftar</h1>
        <p class="text-sm text-slate-500 mt-1">Proses hasil Level Test pendaftar yang telah dinilai pengajar untuk ditempatkan ke kelas resmi.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl font-bold text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl font-bold text-sm flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation text-rose-500"></i> {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Pendaftar</th>
                        <th class="px-6 py-4">Pengajar & Nilai</th>
                        <th class="px-6 py-4">Status & Level</th>
                        <th class="px-6 py-4 text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">

                    @forelse($siapTempatkan as $row)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $row->nama_lengkap }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">Jenjang Sekolah: {{ $row->tingkat_sekolah }}</div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="text-slate-700 font-medium"><i class="fa-solid fa-user-tie text-xs text-slate-400 mr-1"></i>{{ $row->nama_pengajar }}</div>
                            <div class="mt-1"><span class="bg-slate-100 text-slate-800 font-bold px-2 py-0.5 rounded text-xs">Skor: {{ $row->nilai_test ?? '0' }}</span></div>
                        </td>

                        <td class="px-6 py-4">
                            @if($row->id_siswa)
                                <span class="block text-xs font-bold text-emerald-600">Ditempatkan di {{ $row->nama_kelas ?? 'Tanpa Kelas' }}</span>
                                <span class="block text-[10px] text-slate-400 mt-0.5">Rekomendasi: {{ $row->rekomendasi_level }}</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200/50 uppercase tracking-wide">
                                    <i class="fa-solid fa-award"></i> {{ $row->rekomendasi_level }}
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            @if($row->id_siswa)
                                <span class="inline-flex items-center justify-center px-4 py-2 bg-slate-50 text-slate-500 text-xs font-bold rounded-lg border border-slate-200 cursor-not-allowed">
                                    <i class="fa-solid fa-check-double mr-1.5 text-emerald-500"></i> Selesai
                                </span>
                            @else
                                <button onclick="bukaModalPenempatan('{{ $row->id_pendaftar }}', '{{ $row->nama_lengkap }}', '{{ $row->id_level_rekomendasi }}')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5">
                                    <i class="fa-solid fa-right-to-bracket mr-1"></i> Tentukan Kelas
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-clipboard-check text-4xl block mb-2 text-slate-200"></i>
                            <p class="text-xs font-medium">Kosong. Belum ada pendaftar baru yang selesai dinilai oleh pengajar.</p>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200 bg-slate-50 rounded-b-2xl">
            {{ $siapTempatkan->links() }}
        </div>
    </div>
</div>

<div id="modalPenempatan" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-xl max-w-md w-full overflow-hidden border border-slate-100">
        <div class="bg-blue-950 px-6 py-4 text-white flex justify-between items-center">
            <div>
                <h3 class="font-bold text-base">Tentukan Kelas & Level Final</h3>
                <p class="text-[11px] text-blue-200 mt-0.5">Siswa: <span id="namaSiswaLabel" class="text-yellow-400 font-bold"></span></p>
            </div>
            <button onclick="tutupModalPenempatan()" class="text-blue-200 hover:text-white"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form action="#" method="POST" id="formPenempatan" class="p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Pilih Level Final</label>
                <select name="id_level" id="id_level_select" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-sm text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
                    <option value="" disabled>Pilih Level</option>
                    @foreach($levelList as $lvl)
                        <option value="{{ $lvl->id_level }}">{{ $lvl->nama_level }}</option>
                    @endforeach
                </select>
                <p class="text-[10px] text-slate-400 mt-1">*Admin dapat mengikuti rekomendasi pengajar atau merubahnya secara manual.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Tempatkan di Kelas</label>
                <select name="id_kelas" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl font-semibold text-sm text-slate-700 focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
                    <option value="" disabled selected>Pilih Ruang Kelas</option>
                    @foreach($kelasList as $kls)
                        <option value="{{ $kls->id_kelas }}">{{ $kls->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3.5 bg-yellow-400 hover:bg-yellow-500 text-blue-950 font-bold text-xs uppercase tracking-wider rounded-xl shadow-md transition-colors">
                    Meresmikan Menjadi Siswa <i class="fa-solid fa-circle-arrow-right ml-1"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function bukaModalPenempatan(idPendaftar, namaSiswa, idRekomendasi) {
        document.getElementById('namaSiswaLabel').innerText = namaSiswa;
        document.getElementById('formPenempatan').action = '/admin/penempatan/proses/' + idPendaftar;

        if(idRekomendasi) {
            document.getElementById('id_level_select').value = idRekomendasi;
        }

        document.getElementById('modalPenempatan').classList.remove('hidden');
    }

    function tutupModalPenempatan() {
        document.getElementById('modalPenempatan').classList.add('hidden');
    }
</script>
@endsection
