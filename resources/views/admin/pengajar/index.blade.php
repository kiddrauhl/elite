@extends('layouts.main_admin')

@section('content')
<div class="p-6 space-y-6">

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm">
        <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Data Pengajar</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola biodata instruktur bimbingan, alamat, serta akun login akses masuk sistem.</p>
        </div>
        <button onclick="openModal('modalTambahPengajar')" class="bg-yellow-400 text-blue-900 font-bold px-6 py-2.5 rounded-xl hover:bg-yellow-500 shadow-md text-sm flex items-center gap-2 transition-all transform hover:-translate-y-0.5">
            <i class="fa-solid fa-plus"></i> Tambah Pengajar Baru
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Pengajar / Instructor</th>
                        <th class="px-6 py-4">Kontak WhatsApp</th>
                        <th class="px-6 py-4">Alamat Rumah</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">

                    @forelse($pengajar as $p)
                    <tr class="hover:bg-slate-50/40 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-900 text-base">{{ $p->nama_pengajar }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">Email: {{ $p->email }}</div>
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-700">
                            <i class="fa-brands fa-whatsapp text-emerald-500 mr-1.5 text-base"></i>{{ $p->no_hp }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-500 max-w-[200px] truncate" title="{{ $p->alamat }}">
                            {{ $p->alamat }}
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                            <button onclick="prepareEditModal('{{ $p->id_pengajar }}', '{{ $p->nama_pengajar }}', '{{ $p->no_hp }}', '{{ $p->alamat }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white border border-blue-200 rounded-xl text-xs font-bold transition-all shadow-sm">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <span>Edit</span>
                            </button>

                            <button onclick="prepareDeleteModal('{{ $p->id_pengajar }}')"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 hover:bg-rose-600 text-rose-700 hover:text-white border border-rose-200 rounded-xl text-xs font-bold transition-all shadow-sm">
                                <i class="fa-solid fa-trash"></i>
                                <span>Hapus</span>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-400 bg-white">
                            <i class="fa-solid fa-folder-open text-3xl block mb-2 text-slate-300"></i>
                            <p class="text-xs font-medium">Data instruktur belum diinput ke dalam sistem SIBIJAR.</p>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalTambahPengajar" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-lg p-6 rounded-3xl shadow-2xl border border-slate-100 transform transition-all space-y-4">
        <div class="flex justify-between items-center pb-2 border-b border-slate-100">
            <h3 class="text-xl font-bold text-slate-900">Tambah Pengajar & Akun</h3>
            <button onclick="closeModal('modalTambahPengajar')" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>
        <form action="/admin/pengajar" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-slate-700 font-bold text-xs uppercase tracking-wider mb-1.5">Nama Lengkap Pengajar</label>
                    <input type="text" name="nama_pengajar" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-500" placeholder="Nama lengkap & gelar" required>
                </div>
                <div>
                    <label class="block text-slate-700 font-bold text-xs uppercase tracking-wider mb-1.5">No. HP / WhatsApp</label>
                    <input type="tel" name="no_hp" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-500" placeholder="08xxxxxxxxxx" required>
                </div>
                <div>
                    <label class="block text-slate-700 font-bold text-xs uppercase tracking-wider mb-1.5">Alamat Email Login</label>
                    <input type="email" name="email" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-500" placeholder="nama@email.com" required>
                </div>
                <div>
                    <label class="block text-slate-700 font-bold text-xs uppercase tracking-wider mb-1.5">Buat Password Akun</label>
                    <input type="password" name="password" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-500" placeholder="••••••••" required>
                </div>
            </div>
            <div>
                <label class="block text-slate-700 font-bold text-xs uppercase tracking-wider mb-1.5">Alamat Tempat Tinggal</label>
                <textarea name="alamat" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-500 resize-none" placeholder="Nama jalan, nomor rumah, RT/RW, kota..." required></textarea>
            </div>
            <div class="mt-6 flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modalTambahPengajar')" class="flex-1 bg-slate-100 text-slate-600 font-bold py-3 rounded-xl text-xs uppercase">Batal</button>
                <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-xl text-xs uppercase shadow-md">Simpan Data & Akun</button>
            </div>
        </form>
    </div>
</div>

<div id="modalEditPengajar" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-md p-6 rounded-3xl shadow-2xl border border-slate-100 transform transition-all space-y-4">
        <div class="flex justify-between items-center pb-2 border-b border-slate-100">
            <h3 class="text-xl font-bold text-slate-900">Ubah Data Pengajar</h3>
            <button onclick="closeModal('modalEditPengajar')" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>
        <form action="#" method="POST" id="formEditPengajar" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-slate-700 font-bold text-xs uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                <input type="text" name="nama_pengajar" id="edit_nama_pengajar" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-slate-700 font-bold text-xs uppercase tracking-wider mb-1.5">No. HP / WhatsApp</label>
                <input type="tel" name="no_hp" id="edit_no_hp" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-500">
            </div>
            <div>
                <label class="block text-slate-700 font-bold text-xs uppercase tracking-wider mb-1.5">Alamat Rumah</label>
                <textarea name="alamat" id="edit_alamat" rows="3" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:border-blue-500 resize-none"></textarea>
            </div>
            <div class="mt-6 flex gap-3 pt-2">
                <button type="button" onclick="closeModal('modalEditPengajar')" class="flex-1 bg-slate-100 text-slate-600 font-bold py-3 rounded-xl text-xs uppercase">Batal</button>
                <button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-xl text-xs uppercase shadow-md">Perbarui</button>
            </div>
        </form>
    </div>
</div>

<div id="modalHapusPengajar" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-6 text-center space-y-4 border border-slate-100">
        <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-full flex items-center justify-center text-2xl mx-auto shadow-inner">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <h4 class="text-lg font-bold text-slate-900">Hapus Instruktur & Akun?</h4>
            <p class="text-xs text-slate-400 mt-1 leading-relaxed">Tindakan ini akan menghapus biodata pengajar beserta hak akses login miliknya secara permanen.</p>
        </div>
        <form action="#" method="POST" id="formHapusPengajar" class="flex items-center gap-3 w-full pt-2">
            @csrf
            @method('DELETE')
            <button type="button" onclick="closeModal('modalHapusPengajar')" class="w-1/2 py-2.5 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl uppercase tracking-wider">Batal</button>
            <button type="submit" class="w-1/2 py-2.5 bg-rose-600 text-white text-xs font-bold rounded-xl shadow-md uppercase tracking-wider">Ya, Hapus</button>
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

    // Mengurai data baris tabel ke kotak modal edit
    function prepareEditModal(id_pengajar, nama_pengajar, no_hp, alamat) {
        document.getElementById('formEditPengajar').action = '/admin/pengajar/update/' + id_pengajar;
        document.getElementById('edit_nama_pengajar').value = nama_pengajar;
        document.getElementById('edit_no_hp').value = no_hp;
        document.getElementById('edit_alamat').value = alamat;
        openModal('modalEditPengajar');
    }

    function prepareDeleteModal(id_pengajar) {
        document.getElementById('formHapusPengajar').action = '/admin/pengajar/delete/' + id_pengajar;
        openModal('modalHapusPengajar');
    }

    window.addEventListener('click', function(e) {
        const modalTambah = document.getElementById('modalTambahPengajar');
        const modalEdit = document.getElementById('modalEditPengajar');
        const modalHapus = document.getElementById('modalHapusPengajar');

        if (e.target === modalTambah) closeModal('modalTambahPengajar');
        if (e.target === modalEdit) closeModal('modalEditPengajar');
        if (e.target === modalHapus) closeModal('modalHapusPengajar');
    });
</script>
@endsection
