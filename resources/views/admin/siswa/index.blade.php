@extends('layouts.main_admin')

@section('content')
<div class="p-6 space-y-6">

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm">
        <i class="fa-solid fa-circle-check text-emerald-500"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Manajemen Data Siswa</h2>
            <p class="text-sm text-slate-500">Halaman kelola biodata siswa</p>
        </div>

        <div class="flex gap-2">
            <a href="{{ url('/admin/siswa/export-excel') }}?{{ http_build_query(request()->all()) }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all flex items-center gap-2">
                <i class="fa-solid fa-file-excel"></i> Export Excel
            </a>
            <a href="{{ url('/admin/siswa/export-pdf') }}?{{ http_build_query(request()->all()) }}" target="_blank" class="px-4 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-xl shadow-sm transition-all flex items-center gap-2">
                <i class="fa-solid fa-file-pdf"></i> Cetak PDF
            </a>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 mb-6">
        <form action="/admin/siswa" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">

            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Filter Kelas</label>
                <select name="filter_kelas" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 bg-slate-50 focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all cursor-pointer">
                    <option value="">Semua Kelas</option>
                    @foreach($dataKelas as $k)
                        <option value="{{ $k->id_kelas }}" {{ request('filter_kelas') == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Filter Level</label>
                <select name="filter_level" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 bg-slate-50 focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all cursor-pointer">
                    <option value="">Semua Level</option>
                    @foreach($dataLevel as $l)
                        <option value="{{ $l->id_level }}" {{ request('filter_level') == $l->id_level ? 'selected' : '' }}>
                            {{ $l->nama_level }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2 text-sm">
                    <i class="fa-solid fa-filter"></i> Terapkan
                </button>

                @if(request('filter_kelas') || request('filter_level'))
                    <a href="/admin/siswa" class="w-full sm:w-auto bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2.5 px-4 rounded-xl transition-colors flex items-center justify-center gap-2 text-sm">
                        <i class="fa-solid fa-xmark"></i> Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Siswa</th>
                        <th class="px-6 py-4">Kontak & Alamat</th>
                        <th class="px-6 py-4">Asal Sekolah</th>
                        <th class="px-6 py-4">Tingkat Level</th>
                        <th class="px-6 py-4">Point Stars</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">

                    @forelse($siswa as $item)
                    <tr class="hover:bg-slate-50/40 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-900">{{ $item->nama_lengkap }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $item->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-medium text-slate-700">{{ $item->no_hp }}</div>
                            <div class="text-[11px] text-slate-400 truncate max-w-[150px]" title="{{ $item->alamat }}">{{ $item->alamat }}</div>
                        </td>
                        <td class="px-6 py-4 text-xs font-medium">
                            <div>{{ $item->asal_sekolah }}</div>
                            <div class="text-slate-400 mt-0.5">Jenjang {{ $item->tingkat_sekolah }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-purple-50 text-purple-700 border border-purple-100">
                                Level {{ $item->id_level }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-amber-500 flex items-center gap-1">
                                {{ $item->total_point }} <span class="text-xs text-slate-400 font-normal">Stars</span>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                            <button onclick="prepareEditModal('{{ $item->id_siswa }}', '{{ $item->nama_lengkap }}', '{{ $item->no_hp }}', '{{ $item->id_level }}', '{{ $item->id_kelas }}', '{{ $item->total_point }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white border border-blue-200 rounded-xl text-xs font-bold transition-all duration-200 transform hover:-translate-y-0.5 shadow-sm">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Edit</span>
                            </button>

                            <button onclick="prepareDeleteModal('{{ $item->id_siswa }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-2 bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white border border-rose-200 rounded-xl text-xs font-bold transition-all duration-200 transform hover:-translate-y-0.5 shadow-sm">
                                <i class="fa-solid fa-trash"></i>
                                <span>Hapus</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                            <i class="fa-solid fa-folder-open text-3xl block mb-2 text-slate-300"></i>
                            <p class="text-xs font-medium">Belum ada siswa aktif terdaftar di sistem.</p>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
            <div class="p-4 border-t border-slate-200 bg-slate-50 rounded-b-2xl">
            {{ $siswa->appends(request()->query())->links() }}
            </div>
    </div>
</div>

<div id="modalEditSiswa" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full border border-slate-100 overflow-hidden">
        <div class="bg-blue-950 px-6 py-5 text-white flex justify-between items-center">
            <div>
                <h3 class="font-bold text-lg">Ubah Data Profil Siswa</h3>
                <p class="text-xs text-blue-200 mt-0.5">Perbarui biodata pribadi atau ubah penempatan tingkatan level kelas.</p>
            </div>
            <button onclick="closeModal('modalEditSiswa')" class="text-blue-300 hover:text-white transition-colors">✕</button>
        </div>
        <form action="#" method="POST" id="formEditSiswa" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="edit_name" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">No. HP / WhatsApp</label>
                    <input type="tel" name="no_hp" id="edit_no_hp" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Tingkatan Level Kelas</label>
                    <select name="id_level" id="edit_level_kelas" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm bg-white focus:outline-none focus:border-blue-500">
                        <option value="">-- Pilih Level --</option>
                        @foreach($dataLevel as $lvl)
                            <option value="{{ $lvl->id_level }}">{{ $lvl->nama_level }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kelas Penempatan</label>
                    <select name="id_kelas" id="edit_id_kelas" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">-- Belum Ditempatkan / Cabut --</option>
                        @foreach($dataKelas as $kls)
                            <option value="{{ $kls->id_kelas }}">{{ $kls->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jumlah Point Stars</label>
                    <input type="number" id="edit_poin_bintang" readonly class="w-full px-4 py-2.5 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 cursor-not-allowed text-sm focus:outline-none">
                    <p class="text-[11px] text-slate-400 mt-1"><i class="fa-solid fa-circle-info mr-1"></i> Point Stars tidak dapat diedit secara manual.</p>
                </div>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeModal('modalEditSiswa')" class="w-1/3 py-3 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="w-2/3 py-3 bg-blue-600 text-white text-xs font-bold rounded-xl shadow-md hover:bg-blue-700 transition-colors">Perbarui Data</button>
            </div>
        </form>
    </div>
</div>

<div id="modalHapusSiswa" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-6 text-center space-y-4">
        <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center text-2xl mx-auto">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h4 class="text-lg font-bold text-slate-900">Hapus Data Siswa?</h4>
            <p class="text-xs text-slate-400 mt-1">Tindakan ini akan menghapus riwayat nilai siswa secara permanen.</p>
        </div>
        <form action="#" method="POST" id="formHapusSiswa" class="flex items-center gap-3 w-full">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeModal('modalHapusSiswa')" class="w-1/2 py-2.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl">Batal</button>
            <button type="submit" class="w-1/2 py-2.5 bg-rose-600 text-white text-xs font-bold rounded-xl shadow-md">Ya, Hapus</button>
        </form>
    </div>
</div>

<script>
    function openModal(idModal) {
        document.getElementById(idModal).classList.remove('hidden');
    }

    // Fungsi penutup modal serbaguna
    function closeModal(idModal) {
        document.getElementById(idModal).classList.add('hidden');
    }

    // Menjepret data baris tabel untuk dimasukkan ke kotak input modal edit
    function prepareEditModal(id_siswa, name, no_hp, level, kelas, stars) {
        document.getElementById('formEditSiswa').action = '/admin/siswa/update/' + id_siswa;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_no_hp').value = no_hp;
        document.getElementById('edit_level_kelas').value = level;
        document.getElementById('edit_id_kelas').value = kelas; // 🌟 Otomatis memilih dropdown kelas
        document.getElementById('edit_poin_bintang').value = stars;
        openModal('modalEditSiswa');
    }

    // Mengarahkan URL form hapus secara dinamis sesuai ID siswa yang diklik
    function prepareDeleteModal(id_siswa) {
        document.getElementById('formHapusSiswa').action = '/admin/siswa/delete/' + id_siswa;
        openModal('modalHapusSiswa');
    }

    // Mengamankan penutupan modal saat mengklik area luar kotak putih
    window.addEventListener('click', function(e) {
        const modalEdit = document.getElementById('modalEditSiswa');
        const modalHapus = document.getElementById('modalHapusSiswa');
        if (e.target === modalEdit) closeModal('modalEditSiswa');
        if (e.target === modalHapus) closeModal('modalHapusSiswa');
    });
</script>
@endsection
