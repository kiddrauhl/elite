@extends('layouts.main_admin')

@section('content')
<div class="p-6 max-w-7xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Laporan Perkembangan Siswa</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau pencapaian Point Stars, nilai akademik raport, dan performa kelas secara real-time.</p>
        </div>
        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
            <i class="fa-solid fa-chart-line"></i>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
        <form action="{{ route('laporan.perkembangan') }}" method="GET" class="flex flex-wrap items-center gap-4">

            <div class="w-full sm:w-60">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Filter Level</label>
                <select name="id_level" onchange="this.form.submit()" class="w-full text-xs border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white font-medium text-slate-700 cursor-pointer shadow-sm">
                    <option value="">Semua Level</option>
                    @foreach($levels as $lvl)
                        <option value="{{ $lvl->id_level }}" {{ $selectedLevel == $lvl->id_level ? 'selected' : '' }}>
                            {{ $lvl->nama_level }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-full sm:w-60">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Filter Kelas</label>
                <select name="id_kelas" onchange="this.form.submit()" class="w-full text-xs border-slate-200 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-white font-medium text-slate-700 cursor-pointer shadow-sm">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $kls)
                        <option value="{{ $kls->id_kelas }}" {{ $selectedKelas == $kls->id_kelas ? 'selected' : '' }}>
                            Kelas {{ $kls->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if($selectedLevel || $selectedKelas)
                <div class="pt-5">
                    <a href="{{ route('laporan.perkembangan') }}" class="inline-flex items-center text-xs text-rose-600 hover:text-rose-700 font-bold gap-1 transition-colors">
                        <i class="fa-solid fa-arrow-rotate-left"></i> Atur Ulang Filter
                    </a>
                </div>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
        <div class="mb-5">
            <h2 class="text-base font-black text-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-chart-bar text-emerald-500"></i> Grafik Performa Rata-Rata Kelas
            </h2>
            <p class="text-xs text-slate-400 mt-0.5">Analisis perbandingan akumulasi nilai ujian raport serta keaktifan point stars siswa antar rombongan belajar.</p>
        </div>

        <div class="relative w-full h-[320px]">
            <canvas id="perkembanganChart" data-chart="{{ json_encode($chartData) }}"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 relative overflow-hidden flex flex-col justify-between">
            <div>
                <h2 class="text-base font-black text-slate-800 mb-5 flex items-center gap-2">
                    <i class="fa-solid fa-star text-yellow-400"></i> Peringkat Point Stars Tertinggi
                </h2>
                <div class="space-y-3.5">
                    @forelse($topStars as $index => $siswa)
                        @php
                            $styles = [
                                0 => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600', 'border' => 'border-yellow-200'],
                                1 => ['bg' => 'bg-slate-50', 'text' => 'text-slate-500', 'border' => 'border-slate-200'],
                                2 => ['bg' => 'bg-amber-50/60', 'text' => 'text-amber-700', 'border' => 'border-amber-200']
                            ];
                            $currStyle = $styles[$index] ?? ['bg' => 'bg-slate-50', 'text' => 'text-slate-400', 'border' => 'border-slate-100'];
                        @endphp
                        <div class="flex items-center gap-4 p-3.5 rounded-2xl border border-slate-100 bg-slate-50/40 hover:bg-white hover:shadow-sm transition-all group">
                            <div class="w-9 h-9 shrink-0 rounded-xl {{ $currStyle['bg'] }} {{ $currStyle['text'] }} flex items-center justify-center font-black text-sm border {{ $currStyle['border'] }}">
                                #{{ $index + 1 }}
                            </div>
                            <div class="w-11 h-11 shrink-0 rounded-full overflow-hidden border-2 {{ $currStyle['border'] }} bg-white">
                                @if(isset($siswa->foto_profil) && $siswa->foto_profil)
                                    <img src="{{ asset('storage/' . $siswa->foto_profil) }}" alt="Foto Siswa" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-blue-950 text-white flex items-center justify-center font-black text-xs">
                                        {{ substr($siswa->nama_lengkap, 0, 2) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-slate-800 truncate group-hover:text-blue-600 transition-colors text-sm">{{ $siswa->nama_lengkap }}</h4>
                                <p class="text-xs text-slate-400 truncate">Kelas {{ $siswa->nama_kelas ?? '-' }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="font-black text-base text-slate-800 flex items-center gap-1 justify-end">
                                    <i class="fa-solid fa-star text-yellow-400 text-xs"></i> {{ $siswa->total_point }}
                                </div>
                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Total Poin</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400 text-xs border-2 border-dashed border-slate-200 rounded-2xl">
                            Tidak ditemukan siswa dengan kepemilikan poin pada filter ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 relative overflow-hidden flex flex-col justify-between">
            <div>
                <h2 class="text-base font-black text-slate-800 mb-5 flex items-center gap-2">
                    <i class="fa-solid fa-graduation-cap text-blue-600"></i> Peringkat Nilai Raport Tertinggi
                </h2>
                <div class="space-y-3.5">
                    @forelse($topNilai as $index => $siswa)
                        @php
                            $styles = [
                                0 => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600', 'border' => 'border-yellow-200'],
                                1 => ['bg' => 'bg-slate-50', 'text' => 'text-slate-500', 'border' => 'border-slate-200'],
                                2 => ['bg' => 'bg-amber-50/60', 'text' => 'text-amber-700', 'border' => 'border-amber-200']
                            ];
                            $currStyle = $styles[$index] ?? ['bg' => 'bg-slate-50', 'text' => 'text-slate-400', 'border' => 'border-slate-100'];
                        @endphp
                        <div class="flex items-center gap-4 p-3.5 rounded-2xl border border-slate-100 bg-slate-50/40 hover:bg-white hover:shadow-sm transition-all group">
                            <div class="w-9 h-9 shrink-0 rounded-xl {{ $currStyle['bg'] }} {{ $currStyle['text'] }} flex items-center justify-center font-black text-sm border {{ $currStyle['border'] }}">
                                #{{ $index + 1 }}
                            </div>
                            <div class="w-11 h-11 shrink-0 rounded-full overflow-hidden border-2 {{ $currStyle['border'] }} bg-white">
                                @if(isset($siswa->foto_profil) && $siswa->foto_profil)
                                    <img src="{{ asset('storage/' . $siswa->foto_profil) }}" alt="Foto Siswa" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-blue-950 text-white flex items-center justify-center font-black text-xs">
                                        {{ substr($siswa->nama_lengkap, 0, 2) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-slate-800 truncate group-hover:text-blue-600 transition-colors text-sm">{{ $siswa->nama_lengkap }}</h4>
                                <p class="text-xs text-slate-400 truncate">Kelas {{ $siswa->nama_kelas ?? '-' }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="font-black text-base text-blue-600">{{ $siswa->rata_rata }}</div>
                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Skor Akhir</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 text-slate-400 text-xs border-2 border-dashed border-slate-200 rounded-2xl">
                            Tidak ditemukan data nilai raport pada kriteria filter ini.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Kehadiran Mengikuti Kelas (Siswa)</h3>
                    <p class="text-xs text-slate-500">Persentase partisipasi siswa dalam tatap muka akademik.</p>
                </div>
                <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-bold rounded-lg uppercase">Real-time</span>
            </div>
        
            <div class="overflow-x-auto overflow-y-auto max-h-80 pr-2 custom-scrollbar">
                <table class="w-full text-sm text-left text-slate-600 relative">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200 sticky top-0 z-10">
                        <tr>
                            <th class="py-3 px-4">Siswa</th>
                            <th class="py-3 px-4">Kelas</th>
                            <th class="py-3 px-4 text-center">Hadir / Sesi</th>
                            <th class="py-3 px-4 text-center">Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($kehadiranSiswa as $ks)
                            @php
                                $persentase = $ks->total_sesi > 0 ? round(($ks->total_hadir / $ks->total_sesi) * 100, 1) : 0;
                                $warnaBar = $persentase >= 75 ? 'bg-emerald-500' : ($persentase >= 50 ? 'bg-amber-400' : 'bg-red-500');
                                $warnaTeks = $persentase >= 75 ? 'text-emerald-600' : ($persentase >= 50 ? 'text-amber-600' : 'text-red-600');
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 font-semibold text-slate-800">{{ $ks->nama_lengkap }}</td>
                                <td class="py-3 px-4 text-xs font-medium text-slate-600">{{ $ks->nama_kelas }}</td>
                                <td class="py-3 px-4 text-center font-medium">{{ $ks->total_hadir }} / {{ $ks->total_sesi }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-full bg-slate-100 rounded-full h-2 max-w-[60px] hidden sm:block">
                                            <div @style(["width: {$persentase}%"]) class="{{ $warnaBar }} h-2 rounded-full"></div>
                                        </div>
                                        <span class="text-xs font-bold {{ $warnaTeks }}">{{ $persentase }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Kehadiran Mengajar (Instruktur)</h3>
                    <p class="text-xs text-slate-500">Pemantauan jurnal log mengajar instruktur aktif.</p>
                </div>
                <span class="px-2.5 py-1 bg-amber-50 text-amber-700 text-xs font-bold rounded-lg uppercase">Bulan Ini</span>
            </div>
        
            <div class="overflow-x-auto overflow-y-auto max-h-80 pr-2 custom-scrollbar">
                <table class="w-full text-sm text-left text-slate-600 relative">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200 sticky top-0 z-10">
                        <tr>
                            <th class="py-3 px-4">Nama Instruktur</th>
                            <th class="py-3 px-4">Kelas Yang Diampu</th>
                            <th class="py-3 px-4 text-center">Total Pertemuan</th>
                            <th class="py-3 px-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($kehadiranPengajar as $kp)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-3 px-4 font-semibold text-slate-800">{{ $kp->nama }}</td>
                                <td class="py-3 px-4 text-xs font-medium text-slate-600">{{ $kp->nama_kelas }}</td>
                                <td class="py-3 px-4 text-center font-black text-slate-700">{{ $kp->total_mengajar }} Sesi</td>
                                <td class="py-3 px-4 text-center">
                                    @if($kp->total_mengajar > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full me-1.5 animate-pulse"></span> Aktif Memenuhi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-50 text-slate-400">
                                            <span class="w-1.5 h-1.5 bg-slate-300 rounded-full me-1.5"></span> Belum Ada Sesi
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        try {
            const canvas = document.getElementById('perkembanganChart');
            if (!canvas) return;
            const rawChartData = JSON.parse(canvas.dataset.chart || '[]');
            const chartData = Array.isArray(rawChartData) ? rawChartData : Object.values(rawChartData);

            const labels = chartData.length > 0 ? chartData.map(item => 'Kelas ' + (item.nama_kelas || 'N/A')) : ['Belum Ada Data'];
            const dataNilai = chartData.length > 0 ? chartData.map(item => item.avg_nilai ? parseFloat(item.avg_nilai) : 0) : [0];
            const dataStars = chartData.length > 0 ? chartData.map(item => item.avg_stars ? parseFloat(item.avg_stars) : 0) : [0];

            new Chart(canvas.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Rata-rata Nilai Akhir',
                            data: dataNilai,
                            backgroundColor: 'rgba(30, 58, 138, 0.9)',
                            borderColor: 'rgb(30, 58, 138)',
                            borderWidth: 1,
                            borderRadius: 6,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8
                        },
                        {
                            label: 'Rata-rata Point Stars',
                            data: dataStars,
                            backgroundColor: 'rgba(234, 179, 8, 0.9)',
                            borderColor: 'rgb(234, 179, 8)',
                            borderWidth: 1,
                            borderRadius: 6,
                            barPercentage: 0.6,
                            categoryPercentage: 0.8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { family: 'inherit', weight: 'bold', size: 12 },
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { family: 'inherit', size: 11 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'inherit', size: 12, weight: '600' } }
                        }
                    }
                }
            });

        } catch (error) {
            console.error("Gagal memuat grafik: ", error);
        }
    });
</script>

@endsection
