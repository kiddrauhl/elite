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
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Katalog Hadiah Point Stars</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola daftar item hadiah merchandise yang bisa diklaim oleh siswa berprestasi.</p>
        </div>
        <button onclick="openModal('modalTambahGift')" class="bg-yellow-400 text-blue-900 font-bold px-6 py-2.5 rounded-xl hover:bg-yellow-500 shadow-md text-sm flex items-center gap-2 transition-all transform hover:-translate-y-0.5">
            <i class="fa-solid fa-plus"></i> Tambah Item Baru
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <th class="px-6 py-4 w-20">Foto</th>
                    <th class="px-6 py-4">Nama Merchandise / Gift</th>
                    <th class="px-6 py-4">Deskripsi Hadiah</th>
                    <th class="px-6 py-4">Poin Dibutuhkan</th>
                    <th class="px-6 py-4">Stok Barang</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                @forelse($gifts as $g)
                <tr class="hover:bg-slate-50/40 transition-colors">
                    <td class="px-6 py-4">
                        @if($g->foto_gift)
                            <img src="{{ asset('uploads/gifts/' . $g->foto_gift) }}" class="w-12 h-12 object-cover rounded-xl border border-slate-200 shadow-sm">
                        @else
                            <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center text-slate-400 text-[10px] font-bold border border-dashed">No Pic</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 font-bold text-slate-900 text-base">{{ $g->nama_gift }}</td>
                    <td class="px-6 py-4 text-xs text-slate-500 max-w-[200px] truncate" title="{{ $g->deskripsi }}">{{ $g->deskripsi ?? '-' }}</td>
                    <td class="px-6 py-4 font-bold text-amber-500"><i class="fa-solid fa-star mr-1"></i>{{ $g->poin_dibutuhkan }} <span class="text-xs text-slate-400 font-normal">Stars</span></td>
                    <td class="px-6 py-4 font-semibold text-slate-600">{{ $g->stok }} Unit</td>
                    <td class="px-6 py-4 text-right whitespace-nowrap space-x-2">
                        <button onclick="prepareEditModal('{{ $g->id_gift }}', '{{ $g->nama_gift }}', '{{ $g->deskripsi }}', '{{ $g->poin_dibutuhkan }}', '{{ $g->stok }}', '{{ $g->foto_gift }}')" class="px-3 py-1.5 bg-blue-50 text-blue-700 border border-blue-200 rounded-xl text-xs font-bold hover:bg-blue-600 hover:text-white transition-all shadow-sm"><i class="fa-solid fa-pen-to-square mr-1"></i>Edit</button>
                        <button onclick="prepareDeleteModal('{{ $g->id_gift }}')" class="px-3 py-1.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-xl text-xs font-bold hover:bg-rose-600 hover:text-white transition-all shadow-sm"><i class="fa-solid fa-trash mr-1"></i>Hapus</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada item merchandise terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="modalTambahGift" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-md p-6 rounded-3xl shadow-2xl border border-slate-100 space-y-4">
        <h3 class="text-xl font-bold text-slate-900 border-b pb-2">Tambah Item Hadiah</h3>
        <form action="/admin/gift" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div><label class="block text-slate-700 font-bold text-xs uppercase mb-1">Nama Item Hadiah</label><input type="text" name="nama_gift" class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:border-blue-500" required></div>
            <div><label class="block text-slate-700 font-bold text-xs uppercase mb-1">Deskripsi Ringkas</label><textarea name="deskripsi" rows="2" class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:border-blue-500 resize-none" placeholder="Deskripsi spesifikasi barang..."></textarea></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-slate-700 font-bold text-xs uppercase mb-1">Poin Bintang</label><input type="number" name="poin_dibutuhkan" class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:border-blue-500" required></div>
                <div><label class="block text-slate-700 font-bold text-xs uppercase mb-1">Jumlah Stok</label><input type="number" name="stok" class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:border-blue-500" required></div>
            </div>
            <div>
                <label class="block text-slate-700 font-bold text-xs uppercase mb-1">Upload Gambar / Foto Merchandise</label>
                <input type="file" name="foto_gift" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
            </div>
            <div class="flex gap-3 pt-2"><button type="button" onclick="closeModal('modalTambahGift')" class="flex-1 bg-slate-100 font-bold py-3 rounded-xl text-xs uppercase">Batal</button><button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-xl text-xs uppercase">Simpan</button></div>
        </form>
    </div>
</div>

<div id="modalEditGift" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-white w-full max-w-md p-6 rounded-3xl shadow-2xl border border-slate-100 space-y-4">
        <h3 class="text-xl font-bold text-slate-900 border-b pb-2">Ubah Data Hadiah</h3>
        <form action="#" method="POST" id="formEditGift" enctype="multipart/form-data" class="space-y-4">
            @csrf @method('PUT')
            <div><label class="block text-slate-700 font-bold text-xs uppercase mb-1">Nama Item Hadiah</label><input type="text" name="nama_gift" id="edit_nama_gift" class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:border-blue-500" required></div>
            <div><label class="block text-slate-700 font-bold text-xs uppercase mb-1">Deskripsi Ringkas</label><textarea name="deskripsi" id="edit_deskripsi" rows="2" class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:border-blue-500 resize-none"></textarea></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-slate-700 font-bold text-xs uppercase mb-1">Poin Bintang</label><input type="number" name="poin_dibutuhkan" id="edit_poin_dibutuhkan" class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:border-blue-500" required></div>
                <div><label class="block text-slate-700 font-bold text-xs uppercase mb-1">Jumlah Stok</label><input type="number" name="stok" id="edit_stok" class="w-full px-4 py-2.5 rounded-xl border text-sm focus:outline-none focus:border-blue-500" required></div>
            </div>
            <div>
                <label class="block text-slate-700 font-bold text-xs uppercase mb-1">Ganti Foto Merchandise (Opsional)</label>
                <div class="flex items-center gap-3 mt-1">
                    <img id="edit_preview_foto" src="#" class="w-12 h-12 object-cover rounded-xl border hidden">
                    <input type="file" name="foto_gift" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                </div>
            </div>
            <div class="flex gap-3 pt-2"><button type="button" onclick="closeModal('modalEditGift')" class="flex-1 bg-slate-100 font-bold py-3 rounded-xl text-xs uppercase">Batal</button><button type="submit" class="flex-1 bg-blue-600 text-white font-bold py-3 rounded-xl text-xs uppercase">Perbarui</button></div>
        </form>
    </div>
</div>

<div id="modalHapusGift" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-6 text-center space-y-4">
        <h4 class="text-lg font-bold text-slate-900">Hapus Item Hadiah?</h4>
        <form action="#" method="POST" id="formHapusGift" class="flex gap-3"><button type="button" onclick="closeModal('modalHapusGift')" class="w-1/2 py-2.5 bg-slate-100 text-xs font-bold rounded-xl uppercase">Batal</button>@csrf @method('DELETE')<button type="submit" class="w-1/2 py-2.5 bg-rose-600 text-white text-xs font-bold rounded-xl uppercase">Ya, Hapus</button></form>
    </div>
</div>

@if(session('success'))
    <div id="flash-success" data-message="{{ session('success') }}" style="display: none;"></div>
@endif

@if($errors->any())
    <div id="flash-error" style="display: none;"></div>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

    function openModal(id) { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    // Menerima parameter nama file foto untuk pratinjau data lama
    function prepareEditModal(id, nama, deskripsi, poin, stok, foto) {
        document.getElementById('formEditGift').action = '/admin/gift/update/' + id;
        document.getElementById('edit_nama_gift').value = nama;
        document.getElementById('edit_deskripsi').value = deskripsi;
        document.getElementById('edit_poin_dibutuhkan').value = poin;
        document.getElementById('edit_stok').value = stok;

        const previewImg = document.getElementById('edit_preview_foto');
        if (foto && foto !== '') {
            previewImg.src = '/uploads/gifts/' + foto;
            previewImg.classList.remove('hidden');
        } else {
            previewImg.classList.add('hidden');
        }
        openModal('modalEditGift');
    }
    function prepareDeleteModal(id) { document.getElementById('formHapusGift').action = '/admin/gift/delete/' + id; openModal('modalHapusGift'); }

    document.addEventListener('DOMContentLoaded', function () {

        // 1. LOGIKA KONFIRMASI SEBELUM SUBMIT FORM
        const formGift = document.getElementById('form-tambah-gift');

        if (formGift) {
            formGift.addEventListener('submit', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Konfirmasi Penambahan',
                    text: "Apakah Anda yakin data Gift yang diisi sudah benar?",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#1e3a8a',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: '<i class="fa-solid fa-check"></i> Ya, Simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menyimpan...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        formGift.submit();
                    }
                });
            });
        }

        // 2. LOGIKA NOTIFIKASI BERHASIL (Murni JavaScript)
        const flashSuccess = document.getElementById('flash-success');
        if (flashSuccess) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: flashSuccess.getAttribute('data-message'), // Ambil pesan dari HTML
                showConfirmButton: false,
                timer: 2500
            });
        }

        // 3. LOGIKA NOTIFIKASI ERROR (Murni JavaScript)
        const flashError = document.getElementById('flash-error');
        if (flashError) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyimpan',
                text: 'Silakan periksa kembali inputan Anda.',
                confirmButtonColor: '#1e3a8a'
            });
        }

    });
</script>


@endsection
