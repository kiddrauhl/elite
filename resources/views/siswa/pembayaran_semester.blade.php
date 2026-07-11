@extends('layouts.main_siswa')

@section('content')
<div class="p-6 max-w-5xl mx-auto space-y-6">

    <div class="bg-white p-6 md:p-8 rounded-3xl border border-slate-200 shadow-sm">
        <span class="px-2.5 py-1 bg-amber-100 text-amber-700 text-[10px] font-black uppercase rounded-md tracking-wider">Aktivasi Akun Siswa</span>
        <h1 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight mt-2">Langkah Terakhir, {{ $siswa->nama_lengkap }}! 🎓</h1>
        <p class="text-slate-500 mt-1 text-sm">Kelas final kamu telah ditetapkan di <span class="text-blue-600 font-bold">{{ $siswa->nama_kelas }}</span> dengan Level <span class="text-amber-500 font-bold uppercase">{{ $siswa->nama_level }}</span>. Selesaikan administrasi untuk mulai belajar.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-gradient-to-br from-slate-900 to-blue-950 text-white p-8 rounded-3xl shadow-md space-y-4 flex flex-col justify-center relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-900/20 rounded-full"></div>
            <div class="w-12 h-12 bg-yellow-400 text-blue-950 rounded-xl flex items-center justify-center text-xl font-bold">
                <i class="fa-solid fa-lock"></i>
            </div>
            <h2 class="text-xl font-bold tracking-tight">Modul Belajar Masih Terkunci</h2>
            <p class="text-sm text-slate-300 leading-relaxed">Untuk mengunduh modul materi bimbingan belajar, melihat kalender akademik kelas, serta mengakses akumulasi e-raport berkala dari pengajar, silakan lakukan aktivasi pembayaran semester terlebih dahulu.</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex flex-col justify-between space-y-6">
            <div>
                <h3 class="font-bold text-slate-400 text-xs uppercase tracking-wider border-b pb-2 mb-4">Rincian Invoice SPP</h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-xs font-medium text-slate-600">
                        <span>Biaya Program Bimbingan</span>
                        <span class="font-bold text-slate-900">Rp 750.000</span>
                    </div>
                    <div class="flex justify-between text-xs font-medium text-slate-600">
                        <span>Penyediaan Modul Lengkap</span>
                        <span class="font-bold text-slate-900">Rp 150.000</span>
                    </div>
                    <hr class="border-dashed border-slate-200">
                    <div class="flex justify-between items-center pt-1">
                        <span class="text-xs font-bold text-slate-700">Total Tagihan:</span>
                        <span class="text-xl font-black text-blue-950">Rp 900.000</span>
                    </div>
                </div>
            </div>

            <div class="space-y-3">
                <div class="bg-slate-50 p-3 rounded-xl border text-[11px] text-slate-500 leading-normal flex gap-2">
                    <i class="fa-solid fa-shield-halved text-blue-600 text-sm mt-0.5"></i>
                    <span>Pembayaran aman & otomatis mendukung Virtual Account dan e-Wallet via Midtrans.</span>
                </div>
                <button class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-credit-card"></i> Bayar Sekarang via Midtrans
                </button>
            </div>
        </div>

    </div>

</div>
@endsection
