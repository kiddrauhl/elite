@extends('layouts.main_admin')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Verifikasi Pendaftar Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola persetujuan berkas pendaftar dan jadwalkan pertemuan Level Test siswa.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200/60">
                <span class="h-2 w-2 rounded-full bg-amber-500 animate-pulse"></span>
                {{ $pendaftar->count() }} Pendaftar Menunggu Jadwal
            </span>
        </div>
    </div>

    <form action="{{ url()->current() }}" method="GET" class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-between bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">

        <div class="relative w-full md:w-96">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass text-sm"></i>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pendaftar atau sekolah..." class="w-full pl-10 pr-4 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all" autocomplete="off">
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto items-center">

            <div class="w-full sm:w-56">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 pl-1">Gelombang</label>
                <select name="gelombang" onchange="this.form.submit()" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer text-slate-700 font-medium">
                    <option value="">Semua Gelombang</option>
                    @foreach($listGelombang as $g)
                        <option value="{{ $g->id_jadwal_daftar }}" {{ request('gelombang') == $g->id_jadwal_daftar ? 'selected' : '' }}>
                            {{ $g->nama_gelombang }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-44">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1 pl-1">Jenjang Pendidikan</label>
                <select name="jenjang" onchange="this.form.submit()" class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer text-slate-700 font-medium">
                    <option value="">Semua Jenjang</option>
                    <option value="SD" {{ request('jenjang') == 'SD' ? 'selected' : '' }}>SD</option>
                    <option value="SMP" {{ request('jenjang') == 'SMP' ? 'selected' : '' }}>SMP</option>
                    <option value="SMA" {{ request('jenjang') == 'SMA' ? 'selected' : '' }}>SMA</option>
                    <option value="Kuliah" {{ request('jenjang') == 'Kuliah' ? 'selected' : '' }}>Kuliah/Umum</option>
                </select>
            </div>

        </div>
    </form>

    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Biodata Pendaftar</th>
                        <th class="px-6 py-4">Kontak & Alamat</th>
                        <th class="px-6 py-4">Akademik</th>
                        <th class="px-6 py-4">Status Daftar</th>
                        <th class="px-6 py-4 text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">

                    @forelse($pendaftar as $item)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-900">{{ $item->nama_lengkap }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">Daftar: {{ date('d M Y', strtotime($item->tanggal_daftar)) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5 text-xs font-medium text-slate-700">
                                <i class="fa-brands fa-whatsapp text-emerald-500 text-sm"></i> {{ $item->no_hp }}
                            </div>
                            <div class="text-xs text-slate-400 mt-1 max-w-[200px] truncate" title="{{ $item->alamat }}">
                                {{ $item->alamat }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-semibold
                                @if($item->tingkat_sekolah == 'SD') bg-orange-50 text-orange-700 border border-orange-100
                                @elseif($item->tingkat_sekolah == 'SMP') bg-blue-50 text-blue-700 border border-blue-100
                                @elseif($item->tingkat_sekolah == 'SMA') bg-purple-50 text-purple-700 border border-purple-100
                                @else bg-emerald-50 text-emerald-700 border border-emerald-100 @endif">
                                {{ $item->tingkat_sekolah }}
                            </span>
                            <div class="text-xs text-slate-500 mt-1">{{ $item->asal_sekolah }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="openModalJadwal('{{ $item->nama_lengkap }}', '{{ $item->id_pendaftar }}')" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-xl shadow-sm transition-all transform hover:-translate-y-0.5">
                                <i class="fa-solid fa-calendar-plus"></i> Atur Jadwal Tes
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                            <i class="fa-solid fa-folder-open text-3xl block mb-2 text-slate-300"></i>
                            <p class="text-xs font-medium">Hari ini bersih! Tidak ada antrean pendaftar baru.</p>
                        </td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="modalJadwalTes" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full border border-slate-100 overflow-hidden transform transition-all">

        <div class="bg-blue-950 px-6 py-5 text-white flex justify-between items-center">
            <div>
                <h3 class="font-bold text-lg">Setujui & Atur Jadwal Tes</h3>
                <p class="text-xs text-blue-200 mt-0.5">Siswa: <span id="modalNamaSiswa" class="text-yellow-400 font-semibold"></span></p>
            </div>
            <button onclick="closeModalJadwal()" class="text-blue-300 hover:text-white transition-colors text-lg focus:outline-none">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="#" method="POST" id="formJadwalTes" class="p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Pelaksanaan Test</label>
                <input type="date" name="tanggal" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 font-semibold text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Pilih Pengajar Penguji</label>
                <select name="id_pengajar" required class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 font-semibold text-slate-700 text-sm cursor-pointer">
                    <option value="" disabled selected>Pilih Pengajar</option>
                    @foreach($pengajarList as $p)
                        <option value="{{ $p->id_pengajar }}">{{ $p->nama_pengajar }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Waktu / Jam Tes</label>
                <div class="relative flex items-center">
                    <input type="text" name="jam_tes" id="jam_tes_picker" placeholder="Pilih Jam (24 Jam)" required
                        class="w-full pl-4 pr-16 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 font-semibold text-slate-700 text-sm cursor-pointer">
                    <span class="absolute right-4 text-xs font-bold text-slate-500 bg-slate-50 pl-2 pointer-events-none">WITA</span>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Catatan / Lokasi Ruang</label>
                <input type="text" name="catatan_tes" placeholder="Contoh: Ruang CBT Lantai 2" required
                    class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm placeholder-slate-400">
            </div>

            <div class="bg-amber-50 border border-amber-200/60 rounded-xl p-3 text-[11px] text-amber-800 leading-relaxed mt-2">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-1"></i> Dengan menekan tombol setuju di bawah, berkas pendaftar dinyatakan <strong>Sah</strong> dan jadwal otomatis terkirim ke dasbor akun siswa.
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="button" onclick="closeModalJadwal()" class="w-1/3 py-3.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-bold tracking-wide uppercase rounded-xl transition-colors">
                    Batal
                </button>
                <button type="submit" class="w-2/3 py-3.5 bg-yellow-400 hover:bg-yellow-500 text-blue-950 text-xs font-bold tracking-wide uppercase rounded-xl shadow-md transition-colors">
                    Setujui & Kirim Jadwal
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    function openModalJadwal(namaSiswa, idSiswa) {
        document.getElementById('modalNamaSiswa').innerText = namaSiswa;

        // Membawa action URL formulir ke ID pendaftar riil database
        document.getElementById('formJadwalTes').action = '/admin/verifikasi/jadwal/' + idSiswa;

        const modal = document.getElementById('modalJadwalTes');
        modal.classList.remove('hidden');
    }

    function closeModalJadwal() {
        const modal = document.getElementById('modalJadwalTes');
        modal.classList.add('hidden');
    }

    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#jam_tes_picker", {
            enableTime: true,
            noCalendar: true, // Matikan kalender, hanya tampilkan jam
            dateFormat: "H:i", // H kapital memaksa format 24 Jam (misal 14:30)
            time_24hr: true, // Pastikan picker 24 jam aktif
            disableMobile: true // Mencegah HP menimpa dengan kalender bawaannya
        });
    });

    window.addEventListener('click', function(e) {
        const modal = document.getElementById('modalJadwalTes');
        if (e.target === modal) {
            closeModalJadwal();
        }
    });
</script>
@endsection
