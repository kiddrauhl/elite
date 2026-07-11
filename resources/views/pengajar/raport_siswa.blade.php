@extends('layouts.main_pengajar')

@section('content')
<div class="p-6 space-y-6 max-w-7xl mx-auto">

    <a href="/pengajar/raport" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Kelas
    </a>

    <div class="bg-gradient-to-r from-blue-900 to-blue-950 rounded-3xl p-8 text-white shadow-lg relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="z-10">
            <h1 class="text-3xl font-black mb-1">Pengisian Raport: {{ $kelas->nama_kelas }}</h1>
            <p class="text-blue-200 text-sm">Level: {{ $kelas->nama_level }}</p>
        </div>
        <i class="fa-solid fa-graduation-cap absolute -right-4 -bottom-4 text-[120px] text-white/10 rotate-12"></i>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-bold flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">

            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-4">Nama Siswa</th>
                        <th class="px-6 py-4 text-center">Rata-Rata Akhir</th>
                        <th class="px-6 py-4">Catatan Pengajar</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($siswa as $s)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $s->nama_lengkap }}</td>

                            <td class="px-6 py-4 text-center">
                                @if($s->rata_rata)
                                    <span class="text-xl font-black {{ $s->rata_rata >= 75 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ number_format($s->rata_rata, 1) }}
                                    </span>
                                @else
                                    <span class="text-slate-400 font-bold">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-xs text-slate-500 max-w-xs truncate">
                                {{ $s->catatan_pengajar ?? 'Belum ada catatan' }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($s->rata_rata)
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 font-bold text-[10px] uppercase rounded-md border border-emerald-200">Selesai Dinilai</span>
                                @else
                                    <span class="px-2.5 py-1 bg-rose-50 text-rose-700 font-bold text-[10px] uppercase rounded-md border border-rose-200">Belum Dinilai</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                <button onclick="bukaModalRaport('{{ $s->id_siswa }}', '{{ $s->nama_lengkap }}', '{{ $s->nilai_speaking }}', '{{ $s->nilai_listening }}', '{{ $s->nilai_reading }}', '{{ $s->nilai_writing }}', '{{ addslashes($s->catatan_pengajar) }}', '{{ $s->id_level_lanjutan }}')"
                                        class="px-4 py-2 {{ $s->rata_rata ? 'bg-amber-100 text-amber-800 hover:bg-amber-200' : 'bg-blue-600 text-white hover:bg-blue-700' }} font-bold text-xs rounded-lg transition-colors shadow-sm">
                                    {{ $s->rata_rata ? 'Edit Nilai' : 'Input Nilai' }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400">Belum ada siswa di kelas ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<div id="modalRaport" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="tutupModalRaport()"></div>

    <div class="bg-white rounded-3xl w-full max-w-xl relative z-10 shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="modalContentRaport">
        <div class="p-6 border-b border-slate-100 bg-blue-950 text-white flex justify-between items-center">
            <h3 class="font-bold text-lg"><i class="fa-solid fa-award text-yellow-400 mr-2"></i> Input Nilai Raport Akhir</h3>
            <button onclick="tutupModalRaport()" class="text-blue-200 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form id="formRaport" method="POST" action="" class="p-6 space-y-5">
            @csrf

            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Siswa</p>
                <p id="namaSiswaTeks" class="font-black text-blue-900 text-lg">-</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Speaking (0-100)</label>
                    <input type="number" name="nilai_speaking" id="val_speaking" required min="0" max="100" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all font-semibold text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Listening (0-100)</label>
                    <input type="number" name="nilai_listening" id="val_listening" required min="0" max="100" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all font-semibold text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Reading (0-100)</label>
                    <input type="number" name="nilai_reading" id="val_reading" required min="0" max="100" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all font-semibold text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Writing (0-100)</label>
                    <input type="number" name="nilai_writing" id="val_writing" required min="0" max="100" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all font-semibold text-sm">
                </div>
            </div>

           <!-- Cek apakah siswa BUKAN di level Expert (Asumsi ID Expert = 3, silakan sesuaikan angka 3 jika ID-nya berbeda) -->
            @if($level->id_level != 4)

                <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                    <label class="block text-xs font-bold text-blue-900 mb-1">Rekomendasi Level Lanjutan <span class="text-rose-500">*</span></label>
                    <select name="id_level_lanjutan" id="val_level_lanjutan" required class="w-full px-3 py-2 bg-white border border-blue-200 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all font-semibold text-sm text-slate-700">
                        <option value="">Pilih Level Selanjutnya</option>
                        @foreach($semuaLevel as $lvl)
                            <option value="{{ $lvl->id_level }}">{{ $lvl->nama_level }}</option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-blue-600 mt-1.5 leading-relaxed">Pilih level lanjutan untuk siswa ini. Sistem akan otomatis membuka akses tagihan Midtrans di dashboard siswa setelah raport disimpan.</p>
                </div>

            @else

                <div class="bg-emerald-50/50 p-4 rounded-xl border border-emerald-200 text-center">
                    <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-2 shadow-sm">
                        <i class="fa-solid fa-graduation-cap text-lg"></i>
                    </div>
                    <label class="block text-xs font-bold text-emerald-900 mb-1">Status: Level Tertinggi (Expert)</label>
                    <p class="text-[10px] text-emerald-700 leading-relaxed px-2">
                        Siswa ini telah berada di level akhir. Menyimpan nilai raport ini akan otomatis menetapkan status siswa menjadi <b>Lulus (Alumni)</b>. Tidak ada penempatan kelas lanjutan.
                    </p>

                    <!-- Input tersembunyi agar form tetap mengirim data (kosong) tanpa menyebabkan error di javascript/backend -->
                    <input type="hidden" name="id_level_lanjutan" id="val_level_lanjutan" value="">
                </div>

            @endif

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Catatan Instruktur (Opsional)</label>
                <textarea name="catatan_pengajar" id="val_catatan" rows="3" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all font-medium text-sm text-slate-700" placeholder="Berikan pesan apresiasi atau saran untuk perkembangan siswa..."></textarea>
            </div>

            <div class="pt-4 flex justify-end gap-3 border-t border-slate-100">
                <button type="button" onclick="tutupModalRaport()" class="px-5 py-2.5 text-sm font-bold text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</button>
                <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md">Simpan Raport</button>
            </div>
        </form>
    </div>
</div>

<script>
    // 🌟 TAMBAHAN: Parameter idLevelLanjutan ditambahkan
    function bukaModalRaport(idSiswa, nama, speak, listen, read, write, catatan, idLevelLanjutan) {
        const modal = document.getElementById('modalRaport');
        const modalContent = document.getElementById('modalContentRaport');

        const idKelas = "{{ $kelas->id_kelas }}";
        const urlFinal = "/pengajar/raport/submit/" + idKelas + "/" + idSiswa;

        // Masukkan ke dalam action form
        document.getElementById('formRaport').action = urlFinal;

        // Isi form dengan data
        document.getElementById('namaSiswaTeks').innerText = nama;
        document.getElementById('val_speaking').value = speak || '';
        document.getElementById('val_listening').value = listen || '';
        document.getElementById('val_reading').value = read || '';
        document.getElementById('val_writing').value = write || '';
        document.getElementById('val_catatan').value = catatan || '';

        // 🌟 TAMBAHAN: Set value dropdown level lanjutan
        document.getElementById('val_level_lanjutan').value = idLevelLanjutan || '';

        // Animasi Buka
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function tutupModalRaport() {
        const modal = document.getElementById('modalRaport');
        const modalContent = document.getElementById('modalContentRaport');

        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.getElementById('formRaport').reset();
        }, 300);
    }
</script>
@endsection
