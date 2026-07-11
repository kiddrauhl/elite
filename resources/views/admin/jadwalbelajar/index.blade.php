@extends('layouts.main_admin')

@section('content')
<div class="p-6 space-y-6 max-w-7xl mx-auto">

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Jadwal Kelas</h1>
            <p class="text-sm text-slate-500 mt-1">Atur waktu, tanggal, dan pengajar untuk setiap pertemuan kelas.</p>
        </div>
        <button onclick="bukaModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition-all shadow-md shadow-blue-600/20 hover:scale-105">
            <i class="fa-solid fa-plus"></i> Tambah Jadwal Baru
        </button>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
        <form action="{{ route('admin.jadwalbelajar.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
            <div class="w-full sm:w-auto relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                    <i class="fa-regular fa-calendar"></i>
                </div>
                <input type="date" name="filter_tanggal" value="{{ request('filter_tanggal') }}" class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
            </div>

            <div class="w-full sm:w-64">
                <select name="filter_kelas" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id_kelas }}" {{ request('filter_kelas') == $k->id_kelas ? 'selected' : '' }}>Kelas {{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-slate-800 text-white font-bold text-sm rounded-xl hover:bg-slate-900 transition-colors">
                <i class="fa-solid fa-filter mr-1"></i> Filter
            </button>

            @if(request('filter_tanggal') || request('filter_kelas'))
                <a href="{{ route('admin.jadwalbelajar.index') }}" class="w-full sm:w-auto px-4 py-2 bg-rose-50 text-rose-600 font-bold text-sm rounded-xl border border-rose-200 hover:bg-rose-100 transition-colors text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        <th class="px-6 py-4">Waktu & Tanggal</th>
                        <th class="px-6 py-4">Kelas & Pengajar</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    @forelse($jadwalList as $j)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ $j->hari }}, {{ date('d M Y', strtotime($j->tanggal)) }}</div>
                            <div class="text-xs text-slate-500 mt-1">
                                <i class="fa-regular fa-clock"></i> {{ date('H:i', strtotime($j->jam_mulai)) }} - {{ date('H:i', strtotime($j->jam_selesai)) }} Wita
                            </div>
                            @if($j->keterangan)
                                <div class="text-[10px] font-bold uppercase tracking-wider mt-2 bg-slate-100 text-slate-500 px-2 py-1 rounded inline-block">{{ $j->keterangan }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-blue-600">Kelas {{ $j->nama_kelas }}</div>
                            <div class="text-xs text-slate-500 mt-1"><i class="fa-solid fa-chalkboard-user"></i> {{ $j->nama_pengajar }}</div>
                        </td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <a href="{{ route('admin.jadwalbelajar.edit', $j->id_jadwal) }}" class="inline-block text-amber-500 hover:text-amber-700 text-sm font-bold transition-colors mr-3">
                                <i class="fa-regular fa-pen-to-square"></i> Edit
                            </a>

                            <form action="{{ route('admin.jadwalbelajar.hapus', $j->id_jadwal) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')" class="inline-block">
                                @csrf
                                <button type="submit" class="text-rose-500 hover:text-rose-700 text-sm font-bold transition-colors">
                                    <i class="fa-regular fa-trash-can"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-slate-400 bg-white">
                            Belum ada jadwal yang cocok.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalTambahJadwal" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center transition-opacity">
    <div class="bg-white rounded-3xl w-full max-w-lg p-6 m-4 shadow-2xl relative">

        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-black text-slate-900">Tambah Jadwal Baru</h3>
            <button type="button" onclick="tutupModal()" class="text-slate-400 hover:text-rose-500 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <form id="formTambahJadwal" action="{{ route('admin.jadwalbelajar.simpan') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Pilih Kelas</label>
                <select name="id_kelas" id="pilih_kelas" onchange="autoFillPengajar()" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" required>
                    <option value="">Pilih Kelas</option>
                    @foreach($kelasList as $k)
                    <option value="{{ $k->id_kelas }}" data-pengajar="{{ $k->id_pengajar }}">
                        Kelas {{ $k->nama_kelas }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest">Pilih Pengajar</label>
                    <span class="text-[9px] text-emerald-600 font-bold bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded">Otomatis / Bisa Diubah</span>
                </div>
                <select name="id_pengajar" id="pilih_pengajar" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" required>
                    <option value="">Pilih Pengajar</option>
                    @foreach($pengajarList as $p)
                    <option value="{{ $p->id_pengajar }}">{{ $p->nama_lengkap }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Tanggal Kelas</label>
                    <input type="date" name="tanggal" id="input_tanggal" onchange="otomatisIsiHari()" required class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Hari</label>
                    <input type="text" name="hari" id="input_hari" readonly required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-500 font-bold cursor-not-allowed focus:outline-none" placeholder="Otomatis terisi...">
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Level Siswa</label>
                <select name="id_level" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 font-semibold text-slate-700 text-sm cursor-pointer">
                    <option value="" disabled selected>-- Pilih Level --</option>

                    @foreach($levelList as $lvl)
                        <option value="{{ $lvl->id_level }}">{{ $lvl->nama_level }}</option>
                    @endforeach

                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Jam Mulai</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        <input type="text" name="jam_mulai" required class="custom-timepicker w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Contoh: 14:30">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Jam Selesai</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        <input type="text" name="jam_selesai" required class="custom-timepicker w-full pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Contoh: 16:00">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Keterangan (Opsional)</label>
                <input type="text" name="keterangan" placeholder="Contoh: Modul 1 / Pengganti" class="w-full px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="tutupModal()" class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm rounded-xl transition-colors">Batal</button>
                <button type="button" onclick="konfirmasiSimpan()" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition-colors shadow-sm shadow-blue-500/20">Simpan Jadwal</button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<div id="flash-messages" data-success="{{ session('success') }}" class="hidden"></div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // FUNGSI MODAL
    function bukaModal() {
        document.getElementById('modalTambahJadwal').classList.remove('hidden');
    }

    function tutupModal() {
        document.getElementById('modalTambahJadwal').classList.add('hidden');
    }

    // FUNGSI LOGIKA FORM (Autofill Pengajar & Hari)
    function autoFillPengajar() {
        const kelasSelect = document.getElementById('pilih_kelas');
        const pengajarSelect = document.getElementById('pilih_pengajar');
        const selectedOption = kelasSelect.options[kelasSelect.selectedIndex];
        const idPengajarBawaan = selectedOption.getAttribute('data-pengajar');

        if(idPengajarBawaan) {
            pengajarSelect.value = idPengajarBawaan;
        } else {
            pengajarSelect.value = '';
        }
    }

    function otomatisIsiHari() {
        const inputTanggal = document.getElementById('input_tanggal').value;
        const inputHari = document.getElementById('input_hari');

        if (inputTanggal) {
            const dateObj = new Date(inputTanggal);
            const daftarHari = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            inputHari.value = daftarHari[dateObj.getDay()];
        } else {
            inputHari.value = '';
        }
    }

    // FUNGSI SWEETALERT KONFIRMASI
    function konfirmasiSimpan() {
        const form = document.getElementById('formTambahJadwal');

        // Cek validasi wajib (required) di form sebelum konfirmasi
        if(!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: 'Simpan Jadwal Baru?',
            text: "Pastikan hari, tanggal, dan pengajar sudah sesuai.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#2563eb', // bg-blue-600
            cancelButtonColor: '#94a3b8', // bg-slate-400
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // Submit form ke Controller
            }
        });
    }

    // INISIALISASI
    document.addEventListener('DOMContentLoaded', function () {
        // Flatpickr Timepicker 24 Jam
        flatpickr(".custom-timepicker", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            minuteIncrement: 5
        });

        // JavaScript murni mengambil data dari elemen HTML
        const flashDiv = document.getElementById('flash-messages');
        const successMessage = flashDiv ? flashDiv.dataset.success : '';

        // Jika variabel successMessage ada isinya, jalankan SweetAlert
        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: successMessage,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });
        }
    });
</script>
@endsection
