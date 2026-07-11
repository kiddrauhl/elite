@extends('layouts.main_admin')

@section('content')
<div class="p-6 space-y-6 max-w-7xl mx-auto">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Dashboard Admin</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau perkembangan siswa, pendaftar, dan pencapaian prestasi.</p>
        </div>
        <div class="text-right hidden sm:block">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Tanggal Hari Ini</span>
            <span class="text-sm font-bold text-blue-600">{{ date('d F Y') }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex items-center gap-4 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl relative z-10">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Pendaftar</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $totalPendaftar }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex items-center gap-4 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-emerald-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl relative z-10">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Siswa Aktif</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $totalSiswa }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm flex items-center gap-4 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-amber-50 rounded-full group-hover:scale-110 transition-transform"></div>
            <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center text-2xl relative z-10">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Instruktur Pengajar</p>
                <h3 class="text-3xl font-black text-slate-800">{{ $totalPengajar }}</h3>
            </div>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 bg-white rounded-3xl p-6 border border-slate-200 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-blue-600"></i> Top Prestasi Nilai Raport
                </h2>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="prestasiChart"></canvas>
            </div>
        </div>

        <div class="bg-gradient-to-b from-blue-950 to-blue-900 rounded-3xl p-1 shadow-lg">
            <div class="bg-white rounded-[22px] p-6 h-full flex flex-col">
                <h2 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                    <i class="fa-solid fa-crown text-yellow-500"></i> Ranking Bintang
                </h2>

                <div class="space-y-4 flex-1">
                    @forelse($topSiswa as $index => $siswa)
                        <div class="flex items-center justify-between p-3 rounded-2xl {{ $index == 0 ? 'bg-yellow-50 border border-yellow-200/60' : 'hover:bg-slate-50 transition-colors' }}">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs
                                    {{ $index == 0 ? 'bg-yellow-400 text-yellow-900' : ($index == 1 ? 'bg-slate-300 text-slate-700' : ($index == 2 ? 'bg-amber-700 text-amber-100' : 'bg-slate-100 text-slate-500')) }}">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800 leading-tight">{{ $siswa->nama_lengkap }}</p>
                                    <p class="text-[10px] font-semibold text-slate-400 uppercase">ID: {{ str_pad($siswa->id_siswa, 4, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg text-xs font-bold">
                                    {{ $siswa->total_point ?? 0 }} <i class="fa-solid fa-star text-yellow-500"></i>
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-slate-400 py-10">
                            <i class="fa-regular fa-star text-4xl mb-2 opacity-50"></i>
                            <p class="text-sm">Belum ada perolehan point stars.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <div id="sumberDataGrafik"
         data-labels='{!! json_encode($labelGrafik) !!}'
         data-values='{!! json_encode($dataGrafik) !!}'
         style="display: none;">
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('prestasiChart').getContext('2d');

        // 🌟 AMBIL DATA DARI HTML (100% JavaScript Murni, VS Code pasti senang!)
        const sumberData = document.getElementById('sumberDataGrafik');
        const labelGrafik = JSON.parse(sumberData.getAttribute('data-labels'));
        const dataGrafik = JSON.parse(sumberData.getAttribute('data-values'));

        // Buat Gradien Biru SIBIJAR untuk area di bawah garis grafik
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)'); // blue-600 transparan
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

        new Chart(ctx, {
            type: 'line', // Jenis grafik: garis
            data: {
                labels: labelGrafik,
                datasets: [{
                    label: 'Rata-Rata Nilai Raport',
                    data: dataGrafik,
                    borderColor: '#2563eb', // blue-600
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#facc15', // yellow-400
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.4 // Membuat garisnya melengkung halus (bezier)
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e3a8a', // blue-900
                        titleFont: { size: 13, family: 'Inter' },
                        bodyFont: { size: 14, weight: 'bold', family: 'Inter' },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100, // Nilai maksimal raport
                        grid: { borderDash: [4, 4], color: '#e2e8f0' },
                        ticks: { font: { family: 'Inter' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Inter' } }
                    }
                }
            }
        });
    });
</script>
@endsection
