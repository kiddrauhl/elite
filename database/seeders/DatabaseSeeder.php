<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        $password = Hash::make('password'); // Sesuaikan password default jika perlu

        $users = [
            ['nama' => 'Admin Utama', 'email' => 'adminelite@gmail.com', 'role' => 'admin', 'password' => $password],
            ['nama' => 'Admin Dua', 'email' => 'admin2@gmail.com', 'role' => 'admin', 'password' => $password],
            ['nama' => 'Zahra Tri Yanti', 'email' => 'yanti@gmail.com', 'role' => 'siswa', 'password' => $password],
            ['nama' => 'Aurelia Isabella', 'email' => 'aurelia@gmail.com', 'role' => 'siswa', 'password' => $password],
            ['nama' => 'Bagas Tri Rahman', 'email' => 'bagasrahman@gmail.com', 'role' => 'siswa', 'password' => $password],
            ['nama' => 'Vanessa Viktoria', 'email' => 'viktoria@gmail.com', 'role' => 'siswa', 'password' => $password],
            ['nama' => 'Ridha Ahmad', 'email' => 'ridha@gmail.com', 'role' => 'siswa', 'password' => $password],
            ['nama' => 'Laila Safitri Rahmawati', 'email' => 'lailarahmawati@gmail.com', 'role' => 'siswa', 'password' => $password],
            ['nama' => 'Reyhan Sebastian William Wijaya', 'email' => 'reyhan@gmail.com', 'role' => 'siswa', 'password' => $password],
            ['nama' => 'Natasha Salsabilla', 'email' => 'salsabilla@gmail.com', 'role' => 'siswa', 'password' => $password],
            ['nama' => 'Rafael', 'email' => 'rafael@gmail.com', 'role' => 'siswa', 'password' => $password],
            ['nama' => 'Michelle Sutanto', 'email' => 'michelle@gmail.com', 'role' => 'siswa', 'password' => $password],
        ];

        DB::table('users')->insert($users);
    }
}
