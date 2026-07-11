<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. DATA GLOBAL UNTUK LAYOUT SISWA
        View::composer('layouts.main_siswa', function ($view) {
            if (Auth::check()) {
                $navbarProfil = DB::table('pendaftar')
                    ->leftJoin('siswa', 'pendaftar.id_user', '=', 'siswa.id_user')
                    ->where('pendaftar.id_user', Auth::id())
                    ->select('pendaftar.nama_lengkap', 'siswa.foto_profil')
                    ->first();

                $view->with('navbarProfil', $navbarProfil);
            }
        });

        // 2. DATA GLOBAL UNTUK LAYOUT PENGAJAR
        View::composer('layouts.main_pengajar', function ($view) {
            if (Auth::check()) {
                $navbarProfil = DB::table('users')
                    ->where('id', Auth::id())
                    ->select('nama', 'email', 'foto_profil')
                    ->first();

                $view->with('navbarProfil', $navbarProfil);
            }
        });

        // 3. DATA GLOBAL UNTUK LAYOUT ADMIN
        View::composer('layouts.main_admin', function ($view) {
            if (Auth::check()) {
                $navbarProfil = DB::table('users')
                    ->where('id', Auth::id())
                    ->select('nama', 'email', 'foto_profil')
                    ->first();

                $view->with('navbarProfil', $navbarProfil);
            }
        });
    }
}
