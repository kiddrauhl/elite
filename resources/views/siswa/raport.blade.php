@extends('layouts.main_siswa')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@section('content')
<div class="p-6 space-y-6 max-w-5xl mx-auto">

    <div>
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">E-Raport Pembelajaran</h1>
        <p class="text-sm text-slate-500 mt-1">Raport hasil evaluasi belajar dari kelas yang telah kamu selesaikan.</p>
    </div>

    @if(session('error'))
        <div class="bg-rose-50 text-rose-700 p-4 rounded-xl font-bold text-sm border border-rose-200">
            <i class="fa-solid fa-circle-exclamation mr-1"></i> {{ session('error') }}
        </div>
    @endif

    <!-- 🌟 TAMBAHAN: Container Grafik Kemajuan -->
    @if(count($raportList) > 0)
    <div class="bg-white rounded-[24px] border border-slate-200 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-extrabold text-slate-800">
                <i class="fa-solid fa-chart-line text-blue-600 mr-2"></i> Grafik Kemajuan Belajar
            </h3>
        </div>

        <div class="relative h-72 w-full">
            <canvas id="raportChart"></canvas>
        </div>
    </div>
    @endif

    <div class="space-y-4">
        @forelse($raportList as $r)
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between gap-6 hover:border-blue-300 transition-all">

                <div class="flex items-center gap-6 w-full md:w-auto">
                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-4xl font-black shadow-inner
                        {{ $r->rata_rata >= 85 ? 'bg-emerald-100 text-emerald-600' : ($r->rata_rata >= 70 ? 'bg-blue-100 text-blue-600' : 'bg-amber-100 text-amber-600') }}">
                        @if($r->rata_rata >= 85) A
                        @elseif($r->rata_rata >= 70) B
                        @elseif($r->rata_rata >= 50) C
                        @else D
                        @endif
                    </div>

                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase rounded border">{{ $r->nama_level }}</span>
                            <span class="text-xs font-bold text-slate-400"><i class="fa-regular fa-calendar text-[10px]"></i> {{ date('d M Y', strtotime($r->tanggal_dibagikan)) }}</span>
                        </div>
                        <h2 class="text-xl font-black text-slate-800 leading-tight mb-1">{{ $r->nama_kelas }}</h2>
                        <p class="text-sm text-slate-500">Instruktur: <span class="font-semibold">{{ $r->nama_pengajar }}</span></p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-4 w-full md:w-auto mt-4 md:mt-0">
                    <div class="text-center sm:text-right w-full sm:w-auto">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Nilai Akhir</p>
                        <p class="text-2xl font-black text-blue-950">{{ number_format($r->rata_rata, 1) }}</p>
                    </div>

                    <a href="{{ route('siswa.raport.cetak', $r->id_kelas) }}" target="_blank" class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition-colors shadow-md flex items-center justify-center gap-2">
                        <i class="fa-solid fa-file-pdf"></i> Unduh Raport
                    </a>
                </div>

            </div>
        @empty
            <div class="bg-white rounded-3xl border border-dashed border-slate-300 p-12 text-center shadow-sm">
                <i class="fa-solid fa-award text-5xl text-slate-300 mb-4 block"></i>
                <h3 class="text-lg font-bold text-slate-700">Belum Ada Raport</h3>
                <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Instruktur kamu belum mempublikasikan hasil evaluasi belajar. Silakan tunggu di akhir periode kelas.</p>
            </div>
        @endforelse
    </div>

</div>

<!-- 🌟 TAMBAHAN: Script Logika Chart.js -->
@if(count($raportList) > 0)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dataRaport = json($raportList);

        // Membalik urutan array agar grafik dibaca dari kelas pertama (terlama) ke terbaru
        const chartData = [...dataRaport].reverse();

        // Mengambil nama kelas untuk sumbu X dan rata-rata untuk sumbu Y
        const labels = chartData.map(item => item.nama_kelas);
        const dataPoin = chartData.map(item => item.rata_rata);

        const ctx = document.getElementById('raportChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nilai Akhir',
                    data: dataPoin,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 9,
                    fill: true,
                    tension: 0.3 // Memberi efek garis yang sedikit melengkung
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 0,
                        max: 100 // Skala nilai maksimal 100
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 14 },
                        bodyFont: { size: 14, weight: 'bold' },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Nilai Akhir: ' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endif
@endsection
