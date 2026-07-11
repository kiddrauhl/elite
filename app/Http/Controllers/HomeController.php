<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
        $hariIni = now()->format('Y-m-d');

        $gelombangAktif = DB::table('jadwal_pendaftaran')
            ->where('status', 'buka')
            ->where('tanggal_mulai', '<=', $hariIni)
            ->where('tanggal_selesai', '>=', $hariIni)
            ->get();

        return view('home', compact('gelombangAktif'));
    }
}
