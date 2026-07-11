@extends('layouts.main_pengajar')

@section('content')
<div class="p-6 max-w-5xl mx-auto space-y-6">

    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm mb-6">
        <i class="fa-solid fa-circle-check text-xl"></i>
        <p class="font-bold text-sm">{{ session('success') }}</p>
    </div>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Kelola Point Stars</h1>
            <p class="text-sm text-slate-500 mt-1">Berikan reward poin bintang kepada siswa yang aktif dan disiplin.</p>
        </div>
        <i class="fa-solid fa-star text-4xl text-yellow-400 opacity-20"></i>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">

        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <form action="{{ route('pengajar.poin.index') }}" method="GET" class="max-w-md">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Pilih Kelas</label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <select name="filter_kelas" onchange="this.form.submit()" class="w-full sm:w-1/2 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-400/20 focus:border-yellow-400 font-medium text-slate-700 shadow-sm cursor-pointer">
                        <option value="">Silakan Pilih Kelas</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id_kelas }}" {{ $selectedKelas == $k->id_kelas ? 'selected' : '' }}>
                                Kelas {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>

                    <select name="filter_level" onchange="this.form.submit()" class="w-full sm:w-1/2 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-yellow-400/20 focus:border-yellow-400 font-medium text-slate-700 shadow-sm cursor-pointer">
                        <option value="">Semua Level</option>
                        @foreach($levelList as $lvl)
                            <option value="{{ $lvl->id_level }}" {{ $selectedLevel == $lvl->id_level ? 'selected' : '' }}>
                                {{ $lvl->nama_level }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-slate-100 text-xs font-bold text-slate-400 uppercase tracking-wider">
                        <th class="px-6 py-4">Nama Siswa</th>
                        <th class="px-6 py-4 text-center">Total Point Stars</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm text-slate-600">
                    @if($selectedKelas && $siswaList->count() > 0)
                        @foreach($siswaList as $s)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-slate-800">{{ $s->nama_lengkap }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-yellow-50 text-yellow-700 font-black rounded-lg">
                                    <i class="fa-solid fa-star text-yellow-400"></i> {{ $s->total_point ?? 0 }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="bukaModal('{{ $s->id_siswa }}', '{{ $s->nama_lengkap }}')" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 hover:bg-blue-600 text-blue-700 hover:text-white font-bold text-xs rounded-xl transition-colors">
                                    <i class="fa-solid fa-plus"></i> Tambah Poin
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    @elseif($selectedKelas)
                        <tr><td colspan="3" class="px-6 py-12 text-center text-slate-400">Belum ada siswa di kelas ini.</td></tr>
                    @else
                        <tr><td colspan="3" class="px-6 py-12 text-center text-slate-400 italic">Silakan pilih kelas terlebih dahulu untuk melihat daftar siswa.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalTambahPoin" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-md p-6 m-4 shadow-2xl relative animate-bounce-in">

        <div class="flex justify-between items-center mb-5">
            <h3 class="text-lg font-black text-slate-900">Beri Point Stars</h3>
            <button onclick="tutupModal()" class="text-slate-400 hover:text-rose-500 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form id="formTambahPoin" action="{{ route('pengajar.poin.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_siswa" id="input_id_siswa">

            <div class="bg-blue-50/50 p-4 rounded-2xl mb-5">
                <p class="text-xs text-slate-500 font-bold uppercase tracking-widest mb-1">Penerima Bintang</p>
                <p class="text-base font-black text-blue-900" id="teks_nama_siswa">Nama Siswa</p>
            </div>

            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Nominal Poin (Angka)</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-yellow-400">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <input type="number" id="input_nominal" name="nominal" min="1" required class="w-full pl-10 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm font-bold focus:outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-400/20 transition-all" placeholder="Contoh: 10">
                </div>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="tutupModal()" class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm rounded-xl transition-colors">Batal</button>

                <button type="button" onclick="konfirmasiKirim()" class="w-full py-3 bg-yellow-400 hover:bg-yellow-500 text-blue-950 font-black text-sm rounded-xl transition-colors shadow-md shadow-yellow-400/20">Kirim Poin</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function bukaModal(idSiswa, namaSiswa) {
        document.getElementById('input_id_siswa').value = idSiswa;
        document.getElementById('teks_nama_siswa').innerText = namaSiswa;
        document.getElementById('modalTambahPoin').classList.remove('hidden');
    }

    function tutupModal() {
        document.getElementById('modalTambahPoin').classList.add('hidden');
    }

    function konfirmasiKirim() {
        const nominal = document.getElementById('input_nominal').value;
        const namaSiswa = document.getElementById('teks_nama_siswa').innerText;

        if (!nominal || nominal <= 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Harap masukkan nominal poin yang valid!',
                confirmButtonColor: '#3b82f6',
                confirmButtonText: 'Baik, mengerti'
            });
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Pemberian Poin',
            html: `Anda akan memberikan <b>${nominal} Point Stars</b> kepada <b>${namaSiswa}</b>. Lanjutkan?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#eab308',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: '<i class="fa-solid fa-check mr-1"></i> Ya, Berikan!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            backdrop: `rgba(15, 23, 42, 0.6)`
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formTambahPoin').submit();
            }
        });
    }

    // 🌟 PERBAIKAN FINAL: Memanggil session sebagai string murni untuk menghindari error
    const successMessage = "{{ session('success') }}";

    // Jika successMessage tidak kosong, jalankan SweetAlert
    if (successMessage !== "") {
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: successMessage,
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        });
    }
</script>

@endsection
