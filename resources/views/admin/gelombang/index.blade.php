@extends('layouts.main_admin')

@section('content')
<div class="p-6 space-y-6">
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm">
        <i class="fa-solid fa-circle-check text-emerald-500"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Jadwal & Gelombang Pendaftaran</h1>
            <p class="text-sm text-slate-500 mt-1">Atur masa aktif pembukaan pendaftaran siswa baru bimbingan belajar SIBIJAR.</p>
        </div>
        <button onclick="openModal('modalTambahJadwal')" class="bg-blue-600 text-white font-bold px-6 py-2.5 rounded-xl hover:bg-blue-700 shadow-md text-sm flex items-center gap-2 transition-all transform hover:-translate-y-0.5">
            <i class="fa-solid fa-plus"></i> Tambah Gelombang
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-4">Nama Gelombang</th>
                    <th class="px-6 py-4">Tanggal Mulai</th>
                    <th class="px-6 py-4">Tanggal Selesai</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                @forelse($jadwal as $j)
                <tr class="hover:bg-slate-50/40 transition-colors">
                    <td class="px-6 py-4 font-bold text-slate-900 text-base">{{ $j->nama_gelombang }}</td>
                    <td class="px-6 py-4 font-medium text-slate-600">{{ date('d M Y', strtotime($j->tanggal_mulai)) }}</td>
                    <td class="px-6 py-4 font-medium text-slate-600">{{ date('d M Y', strtotime($j->tanggal_selesai)) }}</td>
                    <td class="px-6 py-4 text-center">
                        @if($j->status == 'buka')
                            <span class="px-2.5 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <i class="fa-solid fa-circle text-[8px] mr-1 text-emerald-500 animate-pulse"></i> Buka
                            </span>
                        @else
                            <span class="px-2.5 py-1 rounded-xl text-xs font-bold bg-slate-50 text-slate-500 border border-slate-200">
                                Tutup
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                        <button onclick="prepareEditModal('{{ $j->id_jadwal_daftar }}', '{{ $j->nama_gelombang }}', '{{ $j->tanggal_mulai }}', '{{ $j->tanggal_selesai }}', '{{ $j->status }}')" class="px-3 py-1.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-xl text-xs font-bold hover:bg-blue-600 hover:text-white transition-all shadow-sm"><i class="fa-solid fa-pen-to-square mr-1"></i>Edit</button>
                        <button onclick="prepareDeleteModal('{{ $j->id_jadwal_daftar }}')" class="px-3 py-1.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold hover:bg-rose-600 hover:text-white transition-all shadow-sm"><i class="fa-solid fa-trash mr-1"></i>Hapus</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada agenda jadwal pendaftaran terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modalTambahJadwal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md p-6 rounded-3xl shadow-2xl border border-slate-100 space-y-4">
        <h3 class="text-xl font-bold text-slate-900 border-b pb-2">Tambah Gelombang Pendaftaran</h3>
        <form action="{{ route('admin.gelombang.store') }}" method="POST" class="space-y-4">
            @csrf
            <div><label class="block text-slate-700 font-bold text-xs uppercase mb-1">Nama Gelombang</label><input type="text" name="nama_gelombang" class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:border-blue-500" placeholder="Contoh: Gelombang 1 - Reguler" required></div>
            <div><label class="block text-slate-700 font-bold text-xs uppercase mb-1">Tanggal Mulai Berjalan</label><input type="date" name="tanggal_mulai" class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:border-blue-500" required></div>
            <div><label class="block text-slate-700 font-bold text-xs uppercase mb-1">Tanggal Selesai (Penutupan)</label><input type="date" name="tanggal_selesai" class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:border-blue-500" required></div>
            <div>
                <label class="block text-slate-700 font-bold text-xs uppercase mb-1">Status Gelombang</label>
                <select name="status">
                    <option value="buka">Buka Gelombang</option>
                    <option value="tutup">Tutup Gelombang</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2"><button type="button" onclick="closeModal('modalTambahJadwal')" class="flex-1 bg-slate-100 font-bold py-3 rounded-xl text-xs uppercase">Batal</button><button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-xl text-xs uppercase">Simpan</button></div>
        </form>
    </div>
</div>

<div id="modalEditJadwal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md p-6 rounded-3xl shadow-2xl border border-slate-100 space-y-4">
        <h3 class="text-xl font-bold text-slate-900 border-b pb-2">Ubah Gelombang Pendaftaran</h3>
        <form action="#" method="POST" id="formEditJadwal" class="space-y-4">
            @csrf @method('PUT')
            <div><label class="block text-slate-700 font-bold text-xs uppercase mb-1">Nama Gelombang</label><input type="text" name="nama_gelombang" id="edit_nama_gelombang" class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:border-blue-500" required></div>
            <div><label class="block text-slate-700 font-bold text-xs uppercase mb-1">Tanggal Mulai Berjalan</label><input type="date" name="tanggal_mulai" id="edit_tanggal_mulai" class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:border-blue-500" required></div>
            <div><label class="block text-slate-700 font-bold text-xs uppercase mb-1">Tanggal Selesai (Penutupan)</label><input type="date" name="tanggal_selesai" id="edit_tanggal_selesai" class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:border-blue-500" required></div>
            <div>
                <label class="block text-slate-700 font-bold text-xs uppercase mb-1">Status Gelombang</label>
                <select name="status" id="edit_status" class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:border-blue-500" required>
                    <option value="tutup">Tutup</option>
                    <option value="buka">Buka</option>
                </select>
            </div>
            <div class="flex gap-3 pt-2"><button type="button" onclick="closeModal('modalEditJadwal')" class="flex-1 bg-slate-100 font-bold py-3 rounded-xl text-xs uppercase">Batal</button><button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-xl text-xs uppercase">Perbarui</button></div>
        </form>
    </div>
</div>

<div id="modalHapusJadwal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-6 text-center space-y-4">
        <h4 class="text-lg font-bold text-slate-900">Hapus Agenda Gelombang?</h4>
        <p class="text-xs text-slate-400">Data pendaftaran lama yang terikat mungkin akan terpengaruh.</p>
        <form action="#" method="POST" id="formHapusJadwal" class="flex gap-3"><button type="button" onclick="closeModal('modalHapusJadwal')" class="w-1/2 py-2.5 bg-slate-100 text-xs font-bold rounded-xl uppercase">Batal</button>@csrf @method('DELETE')<button type="submit" class="w-1/2 py-2.5 bg-rose-600 text-white text-xs font-bold rounded-xl uppercase">Ya, Hapus</button></form>
    </div>
</div>

<script>
    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function prepareEditModal(id, nama, tglMulai, tglSelesai, status) {
        document.getElementById('formEditJadwal').action = '/admin/gelombang/update/' + id;
        document.getElementById('edit_nama_gelombang').value = nama;
        document.getElementById('edit_tanggal_mulai').value = tglMulai;
        document.getElementById('edit_tanggal_selesai').value = tglSelesai;
        document.getElementById('edit_status').value = status;
        openModal('modalEditJadwal');
    }
    function prepareDeleteModal(id) {
        document.getElementById('formHapusJadwal').action = '/admin/gelombang/delete/' + id;
        openModal('modalHapusJadwal');
    }
</script>
@endsection
