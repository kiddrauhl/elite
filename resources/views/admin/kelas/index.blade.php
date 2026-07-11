@extends('layouts.main_admin')

@section('content')
<div class="p-8 space-y-6 bg-slate-50 min-h-screen">

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm mb-6">
        <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-2">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Kelas</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola pembagian kelompok kelas dan distribusi pengajar.</p>
        </div>
        <button onclick="openModal('modalTambahKelas')" class="bg-blue-600 text-white font-bold px-5 py-2.5 rounded-xl hover:bg-blue-700 shadow-sm shadow-blue-600/20 transition-all text-sm flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Kelas Baru
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
    <form action="{{ route('admin.kelas.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">

        <div class="flex-1 w-full">
            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Filter Kelas</label>
            <select name="filter_kelas" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Kelas</option>
                @foreach($dataKelasUtama as $kls)
                    <option value="{{ $kls->id_kelas }}" {{ request('filter_kelas') == $kls->id_kelas ? 'selected' : '' }}>
                        {{ $kls->nama_kelas }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex-1 w-full">
            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Filter Level</label>
            <select name="filter_level" class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">Semua Level</option>
                @foreach($levels as $lvl)
                    <option value="{{ $lvl->id_level }}" {{ request('filter_level') == $lvl->id_level ? 'selected' : '' }}>
                        {{ $lvl->nama_level }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="w-full md:w-auto">
            <button type="submit" class="w-full md:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-filter"></i> Terapkan
            </button>
        </div>

        @if(request('filter_kelas') || request('filter_level'))
            <div class="w-full md:w-auto mt-2 md:mt-0">
                <a href="{{ route('admin.kelas.index') }}" class="w-full md:w-auto px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-bold rounded-xl transition-all flex items-center justify-center">
                    <i class="fa-solid fa-rotate-right"></i>
                </a>
            </div>
        @endif

    </form>
</div>

    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-600 text-[11px] font-bold uppercase tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Nama Kelas</th>
                        <th class="px-6 py-4">Level Tingkatan</th>
                        <th class="px-6 py-4">Instructor / Pengajar</th>
                        <th class="px-6 py-4 text-center">Jumlah Siswa</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-slate-700 divide-y divide-slate-100 text-sm">
                    @forelse($kelas as $k)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $k->nama_kelas }}</div>
                            <div class="text-[11px] text-slate-400 mt-0.5">ID: #{{ $k->id_kelas }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-1.5"></span>
                                {{ $k->level->nama_level ?? 'Belum Ada Level' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-medium text-slate-700">{{ $k->pengajar->nama_pengajar ?? 'Belum Ditentukan' }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center min-w-[2.5rem] px-2 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold">
                                {{ $k->siswa_count ?? 0 }} Siswa
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="prepareEditModal('{{ $k->id_kelas }}', '{{ $k->nama_kelas }}', '{{ $k->id_level }}', '{{ $k->id_pengajar }}')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-100 hover:bg-blue-600 text-blue-700 hover:text-white border border-blue-200 rounded-lg text-xs font-bold transition-colors shadow-sm">
                                    <i class="fa-solid fa-pen"></i>
                                    <span>Edit</span>
                                </button>

                                <button onclick="prepareDeleteModal('{{ $k->id_kelas }}')"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-100 hover:bg-rose-600 text-rose-700 hover:text-white border border-rose-200 rounded-lg text-xs font-bold transition-colors shadow-sm">
                                    <i class="fa-solid fa-trash-can"></i>
                                    <span>Hapus</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center bg-white">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 mb-3">
                                <i class="fa-solid fa-folder-open text-2xl text-slate-400"></i>
                            </div>
                            <h4 class="text-slate-900 font-bold mb-1">Data Kelas Kosong</h4>
                            <p class="text-xs text-slate-500">Silakan tambahkan kelas baru terlebih dahulu.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalTambahKelas" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-xl transform transition-all">
        <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Tambah Kelas Baru</h3>
            <button onclick="closeModal('modalTambahKelas')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form action="/admin/kelas" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-slate-700 font-semibold text-xs mb-1.5">Nama Kelas <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_kelas" class="w-full p-2.5 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-slate-50" placeholder="Misal: Nama Kelas - (Level)" required>
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold text-xs mb-1.5">Pilih Level <span class="text-rose-500">*</span></label>
                    <select name="id_level" required class="w-full p-2.5 rounded-lg border border-slate-300 text-sm bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 cursor-pointer">
                        <option value="" disabled selected>Pilih Tingkatan Level...</option>
                        @foreach($levels as $l)
                            <option value="{{ $l->id_level }}">{{ $l->nama_level }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold text-xs mb-1.5">Pilih Pengajar <span class="text-rose-500">*</span></label>
                    <select name="id_pengajar" required class="w-full p-2.5 rounded-lg border border-slate-300 text-sm bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 cursor-pointer">
                        <option value="" disabled selected>Pilih Instructor...</option>
                        @foreach($pengajar as $p)
                            <option value="{{ $p->id_pengajar }}">{{ $p->nama_pengajar }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-6 flex gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('modalTambahKelas')" class="flex-1 bg-slate-100 text-slate-600 hover:bg-slate-200 font-bold py-2.5 rounded-lg text-sm transition-colors">Batal</button>
                <button type="submit" class="flex-1 bg-blue-600 text-white hover:bg-blue-700 font-bold py-2.5 rounded-lg text-sm transition-colors shadow-sm">Simpan Kelas</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEditKelas" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-xl transform transition-all border-t-4 border-blue-500">
        <div class="flex justify-between items-center mb-5 pb-4 border-b border-slate-100">
            <h3 class="text-lg font-bold text-slate-900">Ubah Data Kelas</h3>
            <button onclick="closeModal('modalEditKelas')" class="text-slate-400 hover:text-slate-600 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        <form action="#" method="POST" id="formEditKelas">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-slate-700 font-semibold text-xs mb-1.5">Nama Kelas</label>
                    <input type="text" name="nama_kelas" id="edit_nama_kelas" required class="w-full p-2.5 rounded-lg border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-slate-50">
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold text-xs mb-1.5">Pilih Level</label>
                    <select name="id_level" id="edit_id_level" required class="w-full p-2.5 rounded-lg border border-slate-300 text-sm bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 cursor-pointer">
                        @foreach($levels as $l)
                            <option value="{{ $l->id_level }}">{{ $l->nama_level }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-slate-700 font-semibold text-xs mb-1.5">Pilih Pengajar</label>
                    <select name="id_pengajar" id="edit_id_pengajar" required class="w-full p-2.5 rounded-lg border border-slate-300 text-sm bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 cursor-pointer">
                        @foreach($pengajar as $p)
                            <option value="{{ $p->id_pengajar }}">{{ $p->nama_pengajar }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-6 flex gap-3 pt-4 border-t border-slate-100">
                <button type="button" onclick="closeModal('modalEditKelas')" class="flex-1 bg-slate-100 text-slate-600 hover:bg-slate-200 font-bold py-2.5 rounded-lg text-sm transition-colors">Batal</button>
                <button type="submit" class="flex-1 bg-blue-600 text-white hover:bg-blue-700 font-bold py-2.5 rounded-lg text-sm transition-colors shadow-sm">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<div id="modalHapusKelas" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6 text-center space-y-4">
        <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-2">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h4 class="text-lg font-bold text-slate-900">Hapus Kelas Ini?</h4>
            <p class="text-sm text-slate-500 mt-1.5 leading-relaxed">Siswa di dalam kelas ini akan kehilangan pemetaan ruang belajarnya. Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <form action="#" method="POST" id="formHapusKelas" class="flex items-center gap-3 w-full mt-6">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeModal('modalHapusKelas')" class="w-1/2 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-bold rounded-lg transition-colors">Batal</button>
            <button type="submit" class="w-1/2 py-2.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-lg shadow-sm transition-colors">Ya, Hapus</button>
        </form>
    </div>
</div>

<script>
    function openModal(idModal) {
        document.getElementById(idModal).classList.remove('hidden');
    }

    function closeModal(idModal) {
        document.getElementById(idModal).classList.add('hidden');
    }

    function prepareEditModal(id_kelas, nama_kelas, id_level, id_pengajar) {
        document.getElementById('formEditKelas').action = '/admin/kelas/update/' + id_kelas;
        document.getElementById('edit_nama_kelas').value = nama_kelas;
        document.getElementById('edit_id_level').value = id_level;
        document.getElementById('edit_id_pengajar').value = id_pengajar;
        openModal('modalEditKelas');
    }

    function prepareDeleteModal(id_kelas) {
        document.getElementById('formHapusKelas').action = '/admin/kelas/delete/' + id_kelas;
        openModal('modalHapusKelas');
    }

    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('backdrop-blur-sm')) {
            e.target.classList.add('hidden');
        }
    });
</script>
@endsection
