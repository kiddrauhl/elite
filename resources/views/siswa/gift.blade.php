@extends('layouts.main_siswa')

@section('content')
<div class="p-6 space-y-8 max-w-7xl mx-auto">

    @if(session('success'))
    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm animate-fade-in">
        <i class="fa-solid fa-circle-check text-emerald-500"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2 shadow-sm">
        <i class="fa-solid fa-circle-xmark text-rose-500"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="bg-gradient-to-r from-amber-400 to-amber-500 rounded-3xl p-8 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
        <div class="z-10">
            <h1 class="text-3xl font-black tracking-tight mb-2">Reward Point Stars</h1>
            <p class="text-amber-50 text-sm max-w-lg">Tukarkan poin prestasi yang kamu kumpulkan di kelas dengan berbagai merchandise eksklusif SIBIJAR.</p>
        </div>
        <div class="bg-white/20 backdrop-blur-sm border border-white/40 p-4 rounded-2xl flex items-center gap-4 z-10 w-full md:w-auto shadow-inner">
            <div class="w-14 h-14 bg-white text-amber-500 rounded-xl flex items-center justify-center text-3xl shadow-sm">
                <i class="fa-solid fa-star"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-amber-100 uppercase tracking-wider mb-0.5">Saldo Poin Kamu</p>
                <p class="text-3xl font-black">{{ $siswa->total_point ?? 0 }} <span class="text-sm font-medium">Stars</span></p>
            </div>
        </div>
        <i class="fa-solid fa-gift absolute -right-4 -bottom-10 text-[150px] text-white/20 rotate-12"></i>
    </div>

    <div>
        <h2 class="text-xl font-bold text-slate-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-bag-shopping text-blue-600"></i> Katalog Merchandise
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($gifts as $g)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all flex flex-col overflow-hidden relative group">

                @if($g->stok < 1)
                <div class="absolute inset-0 bg-slate-900/60 z-10 backdrop-blur-sm flex items-center justify-center">
                    <span class="bg-rose-500 text-white px-4 py-2 rounded-xl font-bold text-sm shadow-lg rotate-12">Stok Habis</span>
                </div>
                @endif

                <div class="aspect-square bg-slate-50 relative overflow-hidden">
                    @if($g->foto_gift)
                        <img src="{{ asset('uploads/gifts/' . $g->foto_gift) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <i class="fa-solid fa-image text-5xl"></i>
                        </div>
                    @endif
                </div>

                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="font-bold text-slate-900 leading-tight mb-1">{{ $g->nama_gift }}</h3>
                    <p class="text-xs text-slate-500 line-clamp-2 mb-4 flex-1">{{ $g->deskripsi ?? 'Merchandise eksklusif SIBIJAR' }}</p>

                    <div class="flex items-center justify-between mb-4 border-t border-slate-100 pt-3">
                        <span class="font-black text-amber-500 text-lg flex items-center gap-1">
                            <i class="fa-solid fa-star text-sm"></i> {{ $g->poin_dibutuhkan }}
                        </span>
                        <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-md">Stok: {{ $g->stok }}</span>
                    </div>

                    @if($siswa->total_point >= $g->poin_dibutuhkan && $g->stok > 0)
                        <form action="{{ route('siswa.gift.tukar', $g->id_gift) }}" method="POST" class="form-tukar-gift" data-nama="{{ $g->nama_gift }}" data-poin="{{ $g->poin_dibutuhkan }}">
                            @csrf
                            <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition-colors shadow-sm">
                                Ajukan Penukaran
                            </button>
                        </form>
                    @else
                        <button disabled class="w-full py-2.5 bg-slate-100 text-slate-400 font-bold text-sm rounded-xl cursor-not-allowed">
                            {{ $g->stok <= 0 ? 'Stok Habis' : 'Poin Tidak Cukup' }}
                        </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-span-full bg-white p-12 text-center rounded-3xl border border-slate-200">
                <i class="fa-solid fa-box-open text-4xl text-slate-300 mb-4"></i>
                <h3 class="font-bold text-slate-700">Katalog Kosong</h3>
                <p class="text-sm text-slate-500">Belum ada item hadiah yang ditambahkan oleh admin.</p>
            </div>
            @endforelse
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 bg-slate-50/50">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-slate-400"></i> Riwayat Klaim Hadiah
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                        <th class="px-6 py-3">Tanggal Ajuan</th>
                        <th class="px-6 py-3">Item Hadiah</th>
                        <th class="px-6 py-3">Poin Digunakan</th>
                        <th class="px-6 py-3 text-right">Status Penukaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayat as $r)
                    <tr class="hover:bg-slate-50/40 transition-colors">
                        <td class="px-6 py-4 font-medium text-slate-600">{{ date('d M Y', strtotime($r->tanggal_penukaran)) }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $r->nama_gift }}</td>
                        <td class="px-6 py-4 font-bold text-amber-500"><i class="fa-solid fa-star text-[10px] mr-1"></i>{{ $r->poin_dibutuhkan }}</td>
                        <td class="px-6 py-4 text-right">
                            @if($r->status == 'selesai')
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-xl border border-emerald-200"><i class="fa-solid fa-check mr-1"></i> Sukses</span>
                            @elseif($r->status == 'dibatalkan')
                                <span class="px-3 py-1 bg-rose-50 text-rose-700 text-xs font-bold rounded-xl border border-rose-200"><i class="fa-solid fa-xmark mr-1"></i> Ditolak</span>
                            @else
                                <span class="px-3 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-xl border border-amber-200"><i class="fa-solid fa-spinner animate-spin mr-1"></i> Menunggu Konfirmasi</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada riwayat penukaran hadiah.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@if(session('success'))
    <div id="flash-success" data-message="{{ session('success') }}" style="display: none;"></div>
@endif

@if(session('error'))
    <div id="flash-error" data-message="{{ session('error') }}" style="display: none;"></div>
@endif

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // 1. TANGKAP SEMUA FORM PENUKARAN
        const formTukarGift = document.querySelectorAll('.form-tukar-gift');

        formTukarGift.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault(); // Mencegah loading langsung

                const namaBarang = this.getAttribute('data-nama');
                const jumlahPoin = this.getAttribute('data-poin');

                Swal.fire({
                    title: 'Konfirmasi Penukaran',
                    text: `Tukar ${jumlahPoin} poin untuk ${namaBarang}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#1e3a8a',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Tukar Sekarang!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses Penukaran...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        this.submit(); // Lanjutkan submit form
                    }
                });
            });
        });

        // 2. TAMPILKAN NOTIFIKASI BERHASIL/GAGAL SETELAH REFRESH
        const flashSuccess = document.getElementById('flash-success');
        if (flashSuccess) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: flashSuccess.getAttribute('data-message'),
                showConfirmButton: false,
                timer: 2500
            });
        }

        const flashError = document.getElementById('flash-error');
        if (flashError) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: flashError.getAttribute('data-message'),
                confirmButtonColor: '#1e3a8a'
            });
        }

    });
</script>



@endsection
