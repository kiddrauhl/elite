@extends('layouts.main_siswa')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="py-6 px-4 sm:px-6 max-w-4xl mx-auto">

    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Registrasi Ulang</h1>
        <p class="text-sm text-slate-500 mt-2">Selesaikan administrasi untuk membuka akses penuh ke modul dan kelas lanjutan Anda.</p>
    </div>

    {{-- 🌟 TAMBAHAN LOGIKA: Cek apakah siswa sudah di level akhir (Expert) --}}
    @if($siswa && $siswa->id_level == 4)
        <div class="relative bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden border-t-4 border-t-amber-500">
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-amber-50 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative p-10 sm:p-16 text-center flex flex-col items-center justify-center">
                <div class="relative flex items-center justify-center w-24 h-24 mb-6">
                    <div class="absolute inset-0 bg-amber-200 rounded-full animate-pulse opacity-40"></div>
                    <div class="relative flex items-center justify-center w-20 h-20 bg-amber-100 text-amber-600 rounded-full shadow-inner border border-amber-200">
                        <i class="fa-solid fa-graduation-cap text-4xl"></i>
                    </div>
                </div>

                <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-amber-700 bg-amber-100 rounded-full mb-4">
                    Status: Lulus (Alumni)
                </span>

                <h3 class="text-2xl font-black text-slate-900 mb-3">Selamat, Anda Telah Lulus!</h3>
                <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed">
                    Anda telah berhasil menyelesaikan program level tertinggi <span class="font-bold text-amber-600">(Expert)</span>. Terima kasih atas dedikasi belajar Anda. Saat ini tidak ada lagi level lanjutan yang perlu diregistrasikan.
                </p>

                <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
                     <a href="/siswa/dashboard" class="px-6 py-3 bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard
                    </a>

                    @if(!is_null($siswa->file_sertifikat))
                    <a href="{{ asset('sertifikat/' . $siswa->file_sertifikat) }}" target="_blank" class="px-6 py-3 bg-white text-amber-700 border border-amber-200 hover:bg-amber-50 text-sm font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-award"></i> Lihat E-Sertifikat
                    </a>
                    @endif
                </div>
            </div>
        </div>

    {{-- LOGIKA LAMA: Jika evaluasi belum tersedia --}}
    @elseif(!$siswa || !$siswa->id_level_lanjutan)
        <div class="relative bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="absolute top-0 right-0 -mt-16 -mr-16 w-64 h-64 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-full opacity-50 blur-3xl pointer-events-none"></div>

            <div class="relative p-10 sm:p-16 text-center flex flex-col items-center justify-center">
                <div class="relative flex items-center justify-center w-24 h-24 mb-6">
                    <div class="absolute inset-0 bg-emerald-100 rounded-full animate-ping opacity-25"></div>
                    <div class="relative flex items-center justify-center w-20 h-20 bg-emerald-50 text-emerald-600 rounded-full shadow-inner border border-emerald-100/50">
                        <i class="fa-solid fa-book-open text-3xl"></i>
                    </div>
                </div>

                <span class="px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-100 rounded-full mb-4">
                    Status: Aktif Belajar
                </span>

                <h3 class="text-xl font-extrabold text-slate-900 mb-3">Evaluasi Level Belum Tersedia</h3>
                <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed">
                    Anda saat ini sedang menjalani program <span class="font-bold text-slate-700">{{ $siswa->level_sekarang ?? 'berjalan' }}</span>. Tagihan untuk level berikutnya akan diterbitkan secara otomatis setelah pengajar memberikan penilaian akhir.
                </p>
            </div>
        </div>

    {{-- LOGIKA LAMA: Jika pembayaran berhasil --}}
    @elseif($statusPembayaran == 'settlement')
        <div class="relative bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden border-t-4 border-t-blue-500">
            <div class="absolute -top-24 -right-24 w-48 h-48 bg-blue-50 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative p-10 sm:p-16 text-center flex flex-col items-center justify-center">
                <div class="relative flex items-center justify-center w-24 h-24 mb-6 text-blue-500">
                    <i class="fa-solid fa-circle-check text-6xl"></i>
                </div>

                <h3 class="text-2xl font-black text-slate-900 mb-2">Pembayaran Berhasil!</h3>
                <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed mb-6">
                    Terima kasih, administrasi untuk level <span class="font-bold text-blue-700">{{ $siswa->level_baru }}</span> telah lunas.
                </p>
                <a href="{{ route('siswa.invoice.cetak', $pembayaran->order_id) }}" target="_blank" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-lg shadow-blue-600/20 transition-all flex items-center justify-center gap-2 transform hover:-translate-y-0.5 mt-4 w-full md:w-auto">
                    <i class="fa-solid fa-file-pdf"></i> Cetak Invoice Bukti Daftar Ulang
                </a>

                <div class="bg-blue-50 border border-blue-100 text-blue-800 px-6 py-4 rounded-2xl w-full max-w-sm mt-6">
                    <p class="text-xs font-bold uppercase tracking-wider mb-1">Status Sistem</p>
                    <p class="text-sm">Menunggu admin memasukkan Anda ke ruang kelas belajar yang baru.</p>
                </div>
            </div>
        </div>

    {{-- LOGIKA LAMA: Menampilkan form/tagihan pembayaran --}}
    @else
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl shadow-slate-200/40 overflow-hidden">

            <div class="bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 px-8 py-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-full bg-white opacity-5 transform skew-x-12 translate-x-20"></div>

                <div class="relative flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <p class="text-xs uppercase font-bold text-blue-300 tracking-widest mb-1">Invoice Registrasi</p>
                        <h3 class="text-2xl font-mono font-bold tracking-tight">RE-{{ str_pad($siswa->id_siswa, 5, '0', STR_PAD_LEFT) }}</h3>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold uppercase bg-amber-500/20 text-amber-300 border border-amber-500/30 backdrop-blur-sm">
                            <span class="w-2 h-2 rounded-full bg-amber-400 mr-2 animate-pulse"></span> Menunggu Pembayaran
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="flex flex-col sm:flex-row justify-between pb-8 border-b border-slate-100 gap-6">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Ditagihkan Kepada</p>
                        <h4 class="text-base font-bold text-slate-800">{{ $siswa->nama_lengkap }}</h4>
                        <p class="text-sm text-slate-500 mt-0.5">{{ $siswa->email }}</p>
                        <p class="text-sm text-slate-500">{{ $siswa->no_hp }}</p>
                    </div>
                    <div class="sm:text-right">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Peningkatan Level</p>
                        <div class="flex items-center sm:justify-end gap-3">
                            <span class="text-sm font-medium text-slate-500 line-through">{{ $siswa->level_sekarang ?? 'Level Lama' }}</span>
                            <i class="fa-solid fa-arrow-right text-blue-500 text-xs"></i>
                            <span class="text-sm font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-md border border-blue-100">{{ $siswa->level_baru ?? 'Level Baru' }}</span>
                        </div>
                    </div>
                </div>

                <div class="py-6 space-y-4">
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center text-slate-600 font-medium">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center mr-3">
                                <i class="fa-solid fa-layer-group text-slate-400"></i>
                            </div>
                            Biaya Program Level {{ $siswa->level_baru }}
                        </div>
                        <span class="font-bold text-slate-800">Rp {{ number_format($siswa->biaya_baru - 300000, 0, ',', '.') }}</span>
                    </div>

                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center text-slate-600 font-medium">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center mr-3">
                                <i class="fa-solid fa-book text-slate-400"></i>
                            </div>
                            Modul & Buku Pembelajaran Lanjutan
                        </div>
                        <span class="font-bold text-slate-800">Rp 300.000</span>
                    </div>
                </div>

                <div class="bg-slate-50 rounded-2xl p-6 mt-2 border border-slate-100 flex justify-between items-center">
                    <span class="text-sm font-bold text-slate-500 uppercase tracking-wider">Total Tagihan</span>
                    <span class="text-3xl font-black text-blue-950">Rp {{ number_format($siswa->biaya_baru, 0, ',', '.') }}</span>
                </div>

                <div class="mt-8 space-y-4">
                    <div class="flex items-start gap-3 text-xs text-slate-500 leading-relaxed px-2">
                        <i class="fa-solid fa-lock text-emerald-500 mt-0.5"></i>
                        <p>Pembayaran dienkripsi dan diproses secara aman oleh <strong>Midtrans</strong>. Mendukung metode Virtual Account (VA), e-Wallet, dan retail.</p>
                    </div>

                    <button id="pay-button" class="group relative w-full flex justify-center items-center py-4 px-6 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-500/30 overflow-hidden transition-all transform hover:-translate-y-0.5">
                        <div class="absolute inset-0 w-full h-full bg-white/20 scale-x-0 group-hover:scale-x-100 origin-left transition-transform duration-300 ease-out"></div>
                        <i class="fa-solid fa-shield-check mr-2 z-10"></i>
                        <span class="z-10 uppercase tracking-widest">Selesaikan Pembayaran</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

</div>

@if(isset($snapToken) && $statusPembayaran == 'pending')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                console.log(result);
                Swal.fire({
                    title: 'Pembayaran Berhasil!',
                    text: 'Akses level baru Anda akan segera diproses.',
                    icon: 'success',
                    confirmButtonColor: '#3b82f6'
                }).then((result) => {
                    window.location.reload();
                });
            },
            onPending: function(result) {
                console.log(result);
                Swal.fire({
                    title: 'Menunggu Pembayaran',
                    text: 'Silakan selesaikan pembayaran melalui metode yang Anda pilih.',
                    icon: 'info',
                    confirmButtonColor: '#3b82f6'
                });
            },
            onError: function(result) {
                console.log(result);
                Swal.fire({
                    title: 'Transaksi Gagal',
                    text: 'Maaf, transaksi gagal diproses. Silakan coba lagi.',
                    icon: 'error',
                    confirmButtonColor: '#ef4444'
                });
            },
            onClose: function() {
                console.log("Pop-up Midtrans ditutup tanpa menyelesaikan pembayaran.");
            }
        });
    };
</script>
@endif
@endsection
