@extends('layouts.main') {{-- Sesuaikan dengan nama layout authentication Anda --}}

@section('content')
<div class="min-h-screen flex items-center justify-center bg-slate-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-3xl border border-slate-200 p-8 sm:p-10 shadow-xl relative overflow-hidden">
        <!-- Dekorasi Background -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-amber-500 rounded-full blur-3xl opacity-10 pointer-events-none"></div>

        <!-- Ikon -->
        <div class="w-20 h-20 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner relative z-10">
            <i class="fa-solid fa-envelope-circle-check text-4xl"></i>
        </div>

        <!-- Teks Judul -->
        <div class="text-center mb-8 relative z-10">
            <h3 class="text-2xl font-black text-slate-900 tracking-tight">Verifikasi Email</h3>
            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                Kami telah mengirimkan 6 digit kode OTP ke email <br>
                <span class="font-bold text-slate-700">{{ session('verifikasi_email') }}</span>
            </p>
        </div>

        <!-- Form OTP -->
        <form action="{{ route('otp.proses') }}" method="POST" class="relative z-10">
            @csrf
            
            <div class="mb-6">
                <label for="otp" class="block text-sm font-bold text-slate-700 mb-3 text-center">Masukkan Kode OTP</label>
                <input 
                    type="text" 
                    name="otp" 
                    id="otp"
                    required 
                    maxlength="6"
                    autocomplete="off"
                    class="w-full px-4 py-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-4 focus:ring-amber-500/20 focus:border-amber-500 outline-none transition-all text-center text-3xl font-black tracking-[0.5em] text-slate-900 placeholder-slate-300"
                    placeholder="••••••"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                >
                <!-- Keterangan: oninput di atas memastikan pendaftar hanya bisa mengetik angka -->
            </div>
            
            <!-- Pesan Error (Jika OTP Salah) -->
            @if(session('error'))
                <div class="mb-4 text-center p-3 bg-red-50 text-red-600 rounded-xl text-sm font-bold border border-red-100">
                    <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ session('error') }}
                </div>
            @endif
            
            <button type="submit" class="w-full inline-flex items-center justify-center px-8 py-4 text-sm font-bold text-white bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 rounded-xl transition-all shadow-lg shadow-amber-500/30 transform hover:-translate-y-0.5">
                <i class="fa-solid fa-shield-check mr-2"></i> Verifikasi & Masuk
            </button>
        </form>

        <!-- Bantuan -->
        <p class="text-center text-sm text-slate-500 mt-8 relative z-10">
            Belum menerima email? <br> Coba periksa Akun <b>Gmail</b> Anda.
        </p>
    </div>
</div>
@endsection