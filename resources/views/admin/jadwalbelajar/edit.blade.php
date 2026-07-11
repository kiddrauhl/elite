@extends('layouts.main_admin')

@section('content')
<div class="p-6 space-y-6 max-w-3xl mx-auto">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.jadwalbelajar.index') }}" class="w-10 h-10 bg-white border border-slate-200 rounded-full flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-blue-600 transition-colors shadow-sm">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Jadwal Kelas</h1>
            <p class="text-sm text-slate-500 mt-1">Lakukan penyesuaian untuk jadwal yang sudah dibuat.</p>
        </div>
    </div>

    <div id="flash-messages" data-success="{{ session('success') }}" class="hidden"></div>

    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
        @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 rounded-xl text-sm mb-5 shadow-sm">
            <div class="font-bold mb-2 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> Gagal Menyimpan:
            </div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="formEditJadwal" action="{{ route('admin.jadwalbelajar.update', $jadwal->id_jadwal) }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Pilih Kelas</label>
                    <select name="id_kelas" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" required>
                        @foreach($kelasList as $k)
                        <option value="{{ $k->id_kelas }}" {{ $jadwal->id_kelas == $k->id_kelas ? 'selected' : '' }}>Kelas {{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Pilih Pengajar</label>
                    <select name="id_pengajar" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" required>
                        @foreach($pengajarList as $p)
                        <option value="{{ $p->id_pengajar }}" {{ $jadwal->id_pengajar == $p->id_pengajar ? 'selected' : '' }}>{{ $p->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Tanggal</label>
                    <input type="date" name="tanggal" id="input_tanggal" onchange="otomatisIsiHari()" value="{{ $jadwal->tanggal }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20" required>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Hari</label>
                    <input type="text" name="hari" id="input_hari" value="{{ $jadwal->hari }}" readonly required class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-500 font-bold cursor-not-allowed focus:outline-none" placeholder="Otomatis terisi...">
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
                        <input type="text" name="jam_mulai" value="{{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}" required class="custom-timepicker w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Contoh: 14:30">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Jam Selesai</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        <input type="text" name="jam_selesai" value="{{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}" required class="custom-timepicker w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Contoh: 16:00">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1.5">Keterangan (Opsional)</label>
                <input type="text" name="keterangan" value="{{ $jadwal->keterangan }}" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20">
            </div>

            <div class="pt-4 flex gap-3">
                <a href="{{ route('admin.jadwalbelajar.index') }}" class="w-full text-center py-3 bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-sm rounded-xl transition-colors">Batal</a>
                <button type="button" onclick="konfirmasiUpdate()" class="w-full py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm rounded-xl transition-colors shadow-sm shadow-amber-500/20">
                    <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // FUNGSI LOGIKA FORM (Autofill Hari)
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

    // FUNGSI SWEETALERT KONFIRMASI EDIT
    function konfirmasiUpdate() {
        const form = document.getElementById('formEditJadwal');

        if(!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: 'Simpan Perubahan?',
            text: "Data jadwal akan diperbarui di sistem.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b', // bg-amber-500
            cancelButtonColor: '#94a3b8', // bg-slate-400
            confirmButtonText: 'Ya, Perbarui!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    // INISIALISASI
    document.addEventListener('DOMContentLoaded', function () {
        // Eksekusi fungsi isi hari sekali di awal untuk memastikan sinkronisasi data bawaan
        otomatisIsiHari();

        // Flatpickr Timepicker 24 Jam
        flatpickr(".custom-timepicker", {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            minuteIncrement: 5
        });

        // JavaScript murni mengambil data pesan sukses dari elemen HTML
        const flashDiv = document.getElementById('flash-messages');
        const successMessage = flashDiv ? flashDiv.dataset.success : '';

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
