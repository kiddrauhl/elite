@extends('layouts.main_pengajar') @section('content')
<div class="p-6 space-y-6 max-w-6xl mx-auto">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Manajemen Materi</h1>
            <p class="text-sm text-slate-500 mt-1">Unggah dan kelola modul pembelajaran untuk siswa Anda.</p>
        </div>
        <button onclick="bukaModalUpload()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md transition-all flex items-center gap-2">
            <i class="fa-solid fa-cloud-arrow-up"></i> Unggah Materi Baru
        </button>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-700 p-4 rounded-xl font-bold text-sm border border-emerald-200">
            <i class="fa-solid fa-circle-check mr-1"></i> {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-rose-50 text-rose-700 p-4 rounded-xl font-bold text-sm border border-rose-200">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($materiList as $m)
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col hover:border-blue-300 transition-colors">
                <div class="flex justify-between items-start mb-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
                        <i class="fa-solid fa-file-lines"></i>
                    </div>
                    <div class="text-right space-y-1">
                        <span class="block text-[10px] font-bold text-blue-700 bg-blue-50 px-2.5 py-1 rounded-md uppercase tracking-wider">{{ $m->nama_kelas }}</span>
                        <span class="block text-[9px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">{{ date('d M Y', strtotime($m->tanggal_upload)) }}</span>
                    </div>
                </div>

                <h3 class="font-bold text-slate-800 text-lg leading-tight mb-2">{{ $m->judul_materi }}</h3>
                <p class="text-sm text-slate-500 line-clamp-2 flex-grow">{{ $m->deskripsi ?? 'Tidak ada deskripsi.' }}</p>

                <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                    <a href="{{ asset('storage/materi/' . $m->file_materi) }}" target="_blank" class="flex-1 text-center py-2 bg-slate-50 hover:bg-blue-50 text-slate-700 hover:text-blue-700 text-xs font-bold rounded-lg border border-slate-200 transition-colors">
                        <i class="fa-solid fa-download mr-1"></i> Unduh
                    </a>
                    <a href="{{ route('pengajar.materi.hapus', $m->id_materi) }}" onclick="return confirm('Yakin ingin menghapus materi ini?')" class="px-3 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-lg border border-rose-100 transition-colors">
                        <i class="fa-solid fa-trash-can"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl border border-dashed border-slate-300 p-12 text-center">
                <i class="fa-solid fa-folder-open text-5xl text-slate-300 mb-4 block"></i>
                <h3 class="text-lg font-bold text-slate-700">Belum Ada Materi</h3>
                <p class="text-sm text-slate-500 mt-1">Anda belum mengunggah file apa pun. Klik tombol unggah di atas untuk memulai.</p>
            </div>
        @endforelse
    </div>

</div>

<div id="modalUpload" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-xl max-w-md w-full overflow-hidden">
        <div class="px-6 py-4 border-b flex justify-between items-center bg-slate-50">
            <h3 class="font-bold text-slate-800">Unggah Materi Pembelajaran</h3>
            <button onclick="tutupModalUpload()" class="text-slate-400 hover:text-slate-700"><i class="fa-solid fa-xmark text-lg"></i></button>
        </div>

        <form action="{{ route('pengajar.materi.upload') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Judul Materi</label>
                <input type="text" name="judul_materi" required placeholder="Contoh: Modul 1 - Basic Grammar" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Kelas Tujuan</label>
                @if($kelasList->count() > 0)
                    <select name="id_kelas" id="kelas-dropdown" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer transition-all">
                        <option value="" disabled selected>Pilih Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id_kelas }}">Kelas {{ $kelas->nama_kelas }}</option>
                        @endforeach
                    </select>
                @else
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-rose-500">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="text" readonly value="Belum ada kelas yang ditugaskan untuk Anda." class="w-full pl-10 pr-4 py-2.5 bg-rose-50 border border-rose-200 text-rose-600 rounded-xl text-sm font-semibold cursor-not-allowed outline-none">
                    </div>
                    <input type="hidden" name="id_kelas" value="" required>
                @endif
            </div>

            <div class="mb-4">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Pilih Level Siswa</label>
                <select name="id_level" id="level-dropdown" required class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 font-medium text-slate-700 shadow-sm cursor-pointer transition-all disabled:bg-slate-100 disabled:text-slate-400" disabled>
                    <option value="" disabled selected>Pilih kelas terlebih dahulu</option>
                </select>
                <p id="level-loading" class="text-xs text-blue-500 mt-1 hidden"><i class="fa-solid fa-circle-notch fa-spin mr-1"></i> Memuat level...</p>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Pilih File (PDF, Word, Excel, PPT, ZIP)</label>
                <input type="file" name="file_materi" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl p-1">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi (Opsional)</label>
                <textarea name="deskripsi" rows="3" placeholder="Tuliskan instruksi atau ringkasan materi..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors">
                    <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Upload Sekarang
                </button>
            </div>
        </form>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const kelasDropdown = document.getElementById('kelas-dropdown');
                const levelDropdown = document.getElementById('level-dropdown');
                const levelLoading = document.getElementById('level-loading');

                if(kelasDropdown) {
                    kelasDropdown.addEventListener('change', function() {
                        const idKelas = this.value;

                        // Reset dropdown level & tampilkan loading
                        levelDropdown.innerHTML = '<option value="" disabled selected>Memuat data...</option>';
                        levelDropdown.disabled = true;
                        levelLoading.classList.remove('hidden');

                        // 🌟 PERBAIKAN: Gunakan helper Laravel url() agar link selalu valid
                        const urlTujuan = `{{ url('/pengajar/materi/get-level') }}/${idKelas}`;

                        fetch(urlTujuan)
                            .then(response => {
                                // Cek apakah response dari server sukses (kode 200)
                                if (!response.ok) {
                                    throw new Error('Terjadi kesalahan di server atau route tidak ditemukan');
                                }
                                return response.json();
                            })
                            .then(data => {
                                // Sembunyikan loading
                                levelLoading.classList.add('hidden');
                                levelDropdown.innerHTML = '<option value="" disabled selected>-- Pilih Level --</option>';

                                // Cek apakah ada level yang diajar di kelas tersebut
                                if(data.length > 0) {
                                    levelDropdown.disabled = false;
                                    data.forEach(level => {
                                        const option = document.createElement('option');
                                        option.value = level.id_level;
                                        option.textContent = level.nama_level;
                                        levelDropdown.appendChild(option);
                                    });
                                } else {
                                    levelDropdown.innerHTML = '<option value="" disabled selected>Tidak ada jadwal untuk kelas ini</option>';
                                    levelDropdown.disabled = true;
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                levelLoading.classList.add('hidden');
                                levelDropdown.innerHTML = '<option value="" disabled selected>Gagal memuat data</option>';
                            });
                    });
                }
            });
        </script>
    </div>
</div>

<script>
    function bukaModalUpload() { document.getElementById('modalUpload').classList.remove('hidden'); }
    function tutupModalUpload() { document.getElementById('modalUpload').classList.add('hidden'); }
</script>

<div id="flash-messages"
     data-success="{{ session('success') }}"
     data-error="{{ session('error') }}"
     class="hidden"></div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // 1. Ambil elemen penampung
        const flashDiv = document.getElementById('flash-messages');

        if (flashDiv) {
            // Tarik data pesan dari atribut HTML
            const successMessage = flashDiv.dataset.success;
            const errorMessage = flashDiv.dataset.error;

            // 2. Jika ada pesan SUKSES
            if (successMessage) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: successMessage,
                    showConfirmButton: false,
                    timer: 2500, // Hilang otomatis dalam 2.5 detik
                    timerProgressBar: true
                });
            }

            // 3. Jika ada pesan GAGAL/ERROR
            if (errorMessage) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops! Gagal',
                    text: errorMessage,
                    confirmButtonColor: '#3b82f6', // Tombol biru agar senada dengan tema
                    confirmButtonText: 'Baik, saya mengerti'
                });
            }
        }

    });
</script>
@endsection
