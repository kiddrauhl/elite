@extends('layouts.main_admin')

@section('content')
<div class="p-6 space-y-6 max-w-3xl mx-auto">

    <div>
        <h1 class="text-2xl font-bold text-slate-900">Profil Administrator</h1>
        <p class="text-sm text-slate-500 mt-1">Kelola data diri, foto profil, dan keamanan akun admin pusat di sini.</p>
    </div>

    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm font-semibold">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl text-sm font-semibold mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
        <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-6">
                <label class="block text-sm font-bold text-slate-700 mb-3">Foto Profil</label>
                <div class="flex items-center gap-5">
                    @if($profil->foto_profil)
                        <img src="{{ asset('storage/' . $profil->foto_profil) }}" class="w-20 h-20 rounded-full object-cover border-4 border-slate-50 shadow-sm">
                    @else
                        <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 border-4 border-slate-50 shadow-sm">
                            <i class="fa-solid fa-user-shield text-3xl"></i>
                        </div>
                    @endif
                    <div class="flex-1">
                        <input type="file" name="foto_profil" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                        <p class="text-xs text-slate-400 mt-1.5">Format didukung: JPG, PNG, JPEG. Maks 2MB.</p>
                    </div>
                </div>
            </div>

            <hr class="border-slate-100 mb-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ $profil->nama }}" class="w-full border-slate-300 rounded-lg px-4 py-2.5 bg-slate-50 border focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Email</label>
                    <input type="email" name="email" value="{{ $profil->email }}" class="w-full border-slate-300 rounded-lg px-4 py-2.5 bg-slate-50 border focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
                </div>
            </div>

            <hr class="border-slate-100 mb-6">

            <div class="mb-8">
                <h3 class="text-sm font-bold text-slate-800 mb-1">Keamanan Akun</h3>
                <p class="text-xs text-slate-400 mb-4">Biarkan kosong jika Anda tidak ingin mengubah password saat ini.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Password Baru</label>
                        <input type="password" name="password" placeholder="Masukkan password baru..." class="w-full border-slate-300 rounded-lg px-4 py-2.5 bg-slate-50 border focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru..." class="w-full border-slate-300 rounded-lg px-4 py-2.5 bg-slate-50 border focus:ring-2 focus:ring-blue-500 outline-none transition-all">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-2.5 px-8 rounded-lg transition-colors flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
