@extends('layouts.main_pendaftar')

@section('content')
<div class="p-6 max-w-5xl mx-auto space-y-6">

    <div class="relative bg-slate-900 p-6 md:p-8 rounded-3xl border border-slate-800 shadow-xl overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="absolute -right-12 -top-12 w-48 h-48 bg-yellow-400/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-2 mb-2">
                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-white/10 text-yellow-400 text-[10px]">
                    <i class="fa-solid fa-sparkles"></i>
                </span>
                <span class="text-[10px] font-bold text-yellow-400 uppercase tracking-wider">Portal Calon Siswa</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">Halo, {{ $pendaftar->nama_lengkap }}!</h1>
            <p class="text-slate-300 mt-2 text-sm max-w-lg leading-relaxed">Selamat datang di website pendaftaran. Ini adalah ruang utamamu untuk memantau seluruh proses pendaftaran hingga kamu resmi menjadi siswa kami.</p>
        </div>

        <div class="relative z-10 text-center bg-slate-800/90 backdrop-blur-md p-4 rounded-2xl border border-slate-600 min-w-[170px] shadow-inner">
            <p class="text-[10px] font-extrabold text-slate-200 uppercase tracking-widest mb-2">Status Saat Ini</p>

            @php
                $statusDb = strtolower($pendaftar->status);

                // Warna Default
                $statusStyle = 'bg-slate-700/80 text-slate-100 border-slate-500';
                $statusIcon = 'fa-solid fa-circle-info';
                $statusText = $pendaftar->status;

                // Warna Dinamis
                if ($statusDb == 'pending') {
                    $statusStyle = 'bg-amber-500/20 text-amber-300 border-amber-500/40';
                    $statusIcon = 'fa-solid fa-clock-rotate-left';
                    $statusText = 'Menunggu Verifikasi';
                } elseif ($statusDb == 'proses') {
                    $statusStyle = 'bg-blue-500/20 text-blue-300 border-blue-500/40';
                    $statusIcon = 'fa-solid fa-spinner animate-spin';
                } elseif ($statusDb == 'diterima') {
                    $statusStyle = 'bg-emerald-500/20 text-emerald-300 border-emerald-500/40';
                    $statusIcon = 'fa-solid fa-check-circle';
                }
            @endphp

            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold capitalize border shadow-sm {{ $statusStyle }}">
                <i class="{{ $statusIcon }} mr-1.5"></i>
                {{ $statusText }}
            </span>
        </div>
    </div>

    @if($pendaftar->status == 'pending')
        <div class="bg-amber-50 rounded-3xl border border-amber-200/60 p-8 text-center shadow-sm">
            <div class="w-20 h-20 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fa-solid fa-hourglass-half text-3xl animate-pulse"></i>
            </div>
            <h2 class="text-xl font-bold text-amber-900 mb-2">Berkas Sedang Ditinjau</h2>
            <p class="text-amber-700 text-sm max-w-md mx-auto">Admin kami sedang memverifikasi data pendaftaranmu. Jadwal Level Test akan segera muncul di sini setelah berkas disetujui. Silakan cek secara berkala.</p>
        </div>

    @elseif($pendaftar->status == 'proses' && $pendaftaran)
        <div class="bg-gradient-to-br from-blue-900 to-blue-950 rounded-3xl p-1 shadow-lg">
            <div class="bg-white rounded-[22px] p-6 md:p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-calendar-check text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-800">Jadwal Level Test Kamu</h2>
                        <p class="text-sm text-slate-500">Persiapkan dirimu sebaik mungkin untuk tes ini.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-slate-50 border border-slate-200 p-4 rounded-2xl flex items-center gap-4">
                        <div class="text-blue-500"><i class="fa-regular fa-calendar-days text-2xl"></i></div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase">Tanggal & Waktu</p>
                            <p class="text-sm font-bold text-slate-800">{{ date('d F Y', strtotime($pendaftaran->tanggal)) }}</p>
                            <p class="text-sm font-bold text-blue-600">{{ $pendaftaran->waktu ? date('H:i', strtotime($pendaftaran->waktu)) : 'Menunggu Info' }} WITA</p>
                        </div>
                    </div>

                    <div class="bg-slate-50 border border-slate-200 p-4 rounded-2xl flex items-center gap-4">
                        <div class="text-blue-500"><i class="fa-solid fa-door-open text-2xl"></i></div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase">Ruangan Ujian</p>
                            <p class="text-sm font-bold text-slate-800">{{ $pendaftaran->ruangan ?? 'Belum Ditentukan' }}</p>
                        </div>
                    </div>

                    <div class="bg-slate-50 border border-slate-200 p-4 rounded-2xl flex items-center gap-4">
                        <div class="text-blue-500"><i class="fa-solid fa-chalkboard-user text-2xl"></i></div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase">Instruktur Penguji</p>
                            <p class="text-sm font-bold text-slate-800">{{ $pendaftaran->nama_pengajar ?? 'Menunggu...' }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 bg-blue-50 border border-blue-100 p-4 rounded-2xl text-sm text-blue-800 flex gap-3">
                    <i class="fa-solid fa-circle-info mt-0.5"></i>
                    <p>Tes akan menentukan level kelas yang paling sesuai untukmu. Pastikan hadir tepat waktu atau bersiap 15 menit sebelum tes dimulai.</p>
                </div>
            </div>
        </div>

    @elseif($pendaftar->status == 'diterima' && $siswaData)

        @php
            $cekPembayaran = DB::table('pembayaran')
                ->where('id_pendaftar', $pendaftar->id_pendaftar)
                ->where('status_verifikasi', 'settlement')
                ->first();
        @endphp

        @if($cekPembayaran)
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 text-white p-8 md:p-12 rounded-3xl shadow-lg text-center space-y-5 relative overflow-hidden">
                <div class="absolute -right-12 -top-12 w-48 h-48 bg-white/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-emerald-900/20 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="w-20 h-20 bg-white/20 text-white rounded-full flex items-center justify-center text-4xl mx-auto backdrop-blur-sm border border-white/30 shadow-inner mb-6">
                        <i class="fa-solid fa-people-roof"></i>
                    </div>
                    <h2 class="text-2xl md:text-3xl font-black tracking-tight mb-3">Menunggu Penempatan Kelas</h2>
                    <p class="text-emerald-50 max-w-lg mx-auto text-sm leading-relaxed">
                        Pembayaran tagihanmu telah berhasil diverifikasi! Saat ini <strong>Admin sedang meninjau dan mencarikan ruang kelas yang paling tepat</strong> untukmu. Silakan cek halaman ini secara berkala untuk melihat jadwal kelas aktifmu nanti.
                    </p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <div class="lg:col-span-2 bg-gradient-to-br from-blue-900 to-blue-950 text-white p-8 rounded-3xl shadow-md flex flex-col justify-center space-y-4">
                    <div class="w-12 h-12 bg-yellow-400 text-blue-950 rounded-xl flex items-center justify-center text-xl font-bold">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <h2 class="text-xl font-bold tracking-tight">Selamat! Hasil Level Test Kamu Telah Keluar</h2>
                    <p class="text-sm text-blue-200 leading-relaxed">
                        Berdasarkan evaluasi instruktur dan keputusan akademik, kamu dinyatakan **Diterima** di SIBIJAR dan ditempatkan pada:<br>
                        <span class="text-yellow-400 font-bold">Kelas: {{ $siswaData->nama_kelas }}</span> | <span class="text-yellow-400 font-bold uppercase">Level: {{ $siswaData->nama_level }}</span>
                    </p>
                    <p class="text-xs text-slate-400">Silakan selesaikan invoice tagihan di sebelah kanan untuk mengaktifkan akun siswa dan masuk ke ruang belajar digital.</p>
                </div>

                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between space-y-6">
                    <div>
                        <h3 class="font-bold text-slate-400 text-xs uppercase tracking-wider border-b pb-2 mb-4">Invoice Pelunasan Siswa Baru</h3>
                        <div class="space-y-3 text-xs font-medium text-slate-600">
                            <div class="flex justify-between">
                                <span>Biaya Bimbingan (1 Semester)</span>
                                <span class="font-bold text-slate-900">Rp 1.850.000</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Modul Belajar & Buku Paket</span>
                                <span class="font-bold text-slate-900">Rp 300.000</span>
                            </div>
                            <hr class="border-dashed border-slate-200">
                            <div class="flex justify-between items-center pt-1">
                                <span class="text-xs font-bold text-slate-700">Total Pembayaran:</span>
                                <span class="text-xl font-black text-blue-950">Rp 2.150.000</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('pendaftar.pembayaran') }}" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-wallet"></i> Bayar & Aktivasi Akun
                    </a>
                </div>

            </div>
        @endif
    @endif

</div>
@endsection
