@extends('layouts.main_pengajar')

@section('content')
<div class="p-6 space-y-6 max-w-7xl mx-auto">

    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Level Test Offline</h1>
        <p class="text-sm text-slate-500 mt-1">Lakukan evaluasi kepada pendaftar dan tentukan level belajar yang sesuai untuk mereka.</p>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-clipboard-list text-blue-600"></i> Antrean Ujian Pendaftar
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-4">Tanggal Jadwal</th>
                        <th class="px-6 py-4">Nama Calon Siswa</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi / Hasil</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($jadwalTest as $test)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-bold text-slate-900">{{ date('d M Y', strtotime($test->tanggal)) }}</div>
                            </td>

                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $test->nama_lengkap }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5"><i class="fa-brands fa-whatsapp text-emerald-500"></i> {{ $test->no_hp }}</div>
                            </td>

                            <td class="px-6 py-4">
                                @if($test->id_level_rekomendasi != null)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200 uppercase tracking-wider">
                                        <i class="fa-solid fa-check-double"></i> Selesai Dinilai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 uppercase tracking-wider">
                                        <i class="fa-solid fa-hourglass-half"></i> Belum Sesi Test
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-4">
                                @if($test->id_level_rekomendasi != null)
                                    <div class="text-xs font-bold text-slate-700 bg-slate-50 border border-slate-200 px-3 py-1.5 rounded-lg inline-block">
                                        Skor Test: <span class="text-blue-600 text-sm ml-1">{{ $test->nilai_test }}</span>
                                    </div>

                                    @else
                                    <button onclick="bukaModalNilai('{{ $test->id_pendaftar }}', '{{ $test->nama_lengkap }}')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition-colors shadow-sm">
                                        Mulai Test
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-400">
                                <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300 block"></i>
                                Belum ada data pendaftaran masuk dalam antrean test.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<div id="modalPenilaian" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="tutupModalNilai()"></div>

    <div class="bg-white rounded-3xl w-full max-w-md relative z-10 shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="modalContent">
        <div class="p-6 border-b border-slate-100 bg-blue-950 text-white flex justify-between items-center">
            <h3 class="font-bold text-lg"><i class="fa-solid fa-clipboard-check text-yellow-400 mr-2"></i> Input Hasil Ujian</h3>
            <button onclick="tutupModalNilai()" class="text-blue-200 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form id="formPenilaian" method="POST" action="" class="p-6 space-y-5">
            @csrf
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Pendaftar</p>
                <p id="namaPendaftarTeks" class="font-black text-slate-800 text-lg">-</p>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Nilai Ujian (0 - 100)</label>
                <input type="number" name="nilai" required min="0" max="100" class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-semibold" placeholder="Masukkan angka skor">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Rekomendasi Tingkatan Level</label>
                <select name="id_level" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all font-semibold text-slate-700 cursor-pointer">
                    <option value="" disabled selected>-- Pilih Tingkatan --</option>
                    @foreach($levels as $lvl)
                        <option value="{{ $lvl->id_level }}">{{ $lvl->nama_level }}</option>
                    @endforeach
                </select>
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <button type="button" onclick="tutupModalNilai()" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 rounded-xl">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md">Simpan & Kirim</button>
            </div>
        </form>
    </div>
</div>

<script>
    function bukaModalNilai(idPendaftar, namaPendaftar) {
        const modal = document.getElementById('modalPenilaian');
        const modalContent = document.getElementById('modalContent');

        document.getElementById('formPenilaian').action = '/pengajar/level-test/nilai/' + idPendaftar;
        document.getElementById('namaPendaftarTeks').innerText = namaPendaftar;

        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function tutupModalNilai() {
        const modal = document.getElementById('modalPenilaian');
        const modalContent = document.getElementById('modalContent');

        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('formPenilaian').reset();
        }, 300);
    }
</script>
@endsection
