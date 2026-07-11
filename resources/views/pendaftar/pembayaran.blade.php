@extends('layouts.main_pendaftar')

@section('content')
<div class="p-6 max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Invoice & Pembayaran Program</h1>
        <p class="text-sm text-slate-500 mt-1">Selesaikan biaya administrasi bimbingan belajar untuk membuka modul penuh kelas.</p>
    </div>

    @if(!$pendaftar || !$pendaftar->id_level)
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-12 text-center flex flex-col items-center justify-center">
            <div class="w-20 h-20 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mb-5">
                <i class="fa-solid fa-clipboard-question text-3xl"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-2">Menunggu Hasil Placement Test</h3>
            <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed">
                Tagihan pembayaran belum tersedia. Silakan ikuti tes penempatan level terlebih dahulu. Halaman ini akan otomatis menampilkan nominal tagihan setelah pengajar mengonfirmasi kelas belajar Anda.
            </p>
        </div>

    @elseif(isset($pembayaran) && $pembayaran->status_verifikasi == 'settlement')
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-3xl border border-emerald-600 shadow-lg p-1 text-center flex flex-col items-center justify-center relative overflow-hidden">
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-white/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-12 -bottom-12 w-48 h-48 bg-emerald-900/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="bg-white rounded-[22px] w-full p-10 flex flex-col items-center relative z-10">
                <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-5 shadow-inner border border-emerald-100">
                    <i class="fa-solid fa-check-double text-4xl"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-900 mb-2">Tagihan Lunas!</h3>
                <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed">
                    Terima kasih, pembayaran Anda telah berhasil diverifikasi oleh sistem. Saat ini <strong>Admin sedang memproses penempatan kelas Anda secara manual</strong>. Silakan pantau status perkembangan Anda di halaman Dashboard utama.
                </p>

                <a href="{{ route('pendaftar.invoice.cetak', $pembayaran->order_id) }}" target="_blank" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-emerald-600/20 transition-all flex items-center gap-2 transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-file-pdf"></i> Cetak Invoice Resmi
                </a>
            </div>
        </div>

    @else
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="bg-blue-950 px-6 py-6 text-white flex justify-between items-center">
                <div>
                    <p class="text-[10px] uppercase font-bold text-blue-300 tracking-widest">Nomor Transaksi</p>
                    <h3 class="text-base font-mono font-bold mt-0.5">REG-{{ str_pad($pendaftar->id_pendaftar, 5, '0', STR_PAD_LEFT) }}</h3>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 rounded-lg text-xs font-bold uppercase bg-amber-500 text-blue-950 shadow-sm shadow-amber-500/10">
                        Menunggu Pembayaran
                    </span>
                </div>
            </div>

            <div class="p-6 md:p-8 space-y-6">
                <div class="space-y-3">
                    <div class="flex justify-between text-sm font-medium text-slate-600">
                        <span>Biaya Program Level ({{ $pendaftar->nama_level ?? 'Bimbingan' }})</span>
                        <span class="font-bold text-slate-900">Rp {{ number_format($pendaftar->biaya - 300000, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm font-medium text-slate-600">
                        <span>Modul & Buku Pembelajaran</span>
                        <span class="font-bold text-slate-900">Rp 300.000</span>
                    </div>
                    <hr class="border-dashed border-slate-200 my-2">
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-base font-black text-slate-800">Total Pembayaran :</span>
                        <span class="text-2xl font-black text-blue-950">Rp {{ number_format($pendaftar->biaya, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-xs text-slate-500 leading-relaxed flex gap-3">
                    <div class="text-blue-600 text-base mt-0.5"><i class="fa-solid fa-shield-halved"></i></div>
                    <p>Gerbang pembayaran otomatis kami ditenagai oleh <strong>Midtrans Security Payment</strong>. Anda dapat membayar dengan aman via Transfer Bank (VA), e-Wallet (Gopay/OVO), maupun retail minimarket.</p>
                </div>

                <div class="pt-2">
                    <button id="pay-button" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm uppercase tracking-wider rounded-xl shadow-lg shadow-blue-600/10 transition-all flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-credit-card"></i> Lanjutkan ke Pembayaran
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

@if($snapToken && (!isset($pembayaran) || $pembayaran->status_verifikasi != 'settlement'))
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        snap.pay('{{ $snapToken }}', {
            // Hilangkan semua alert! Langsung reload halaman agar Blade mendeteksi status baru
            onSuccess: function(result) {
                window.location.reload();
            },
            onPending: function(result) {
                window.location.reload();
            },
            onError: function(result) {
                window.location.reload();
            },
            onClose: function() {
                // Biarkan kosong agar popup tertutup diam-diam saat user menekan 'X'
            }
        });
    };
</script>
@endif
@endsection
