<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentCallbackController extends Controller
{
    public function handleNotification(Request $request)
    {
        // 1. Inisialisasi konfigurasi Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        try {
            // 2. Tangkap notifikasi dari Midtrans
            $notif = new \Midtrans\Notification();

            $transactionStatus = $notif->transaction_status;
            $orderId = $notif->order_id;
            $type = $notif->payment_type;

            // 3. Cari data transaksi di database berdasarkan order_id
            $pembayaran = DB::table('pembayaran')->where('order_id', $orderId)->first();

            if (!$pembayaran) {
                return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
            }

            // 4. Tentukan status verifikasi berdasarkan status dari Midtrans
            $statusVerifikasi = 'pending';

            if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
                $statusVerifikasi = 'settlement';
            } elseif ($transactionStatus == 'pending') {
                $statusVerifikasi = 'pending';
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $statusVerifikasi = 'cancel';
            }

            // 5. Update status di tabel pembayaran terlebih dahulu
            DB::table('pembayaran')
                ->where('order_id', $orderId)
                ->update([
                    'status_verifikasi' => $statusVerifikasi,
                    'updated_at' => now()
                ]);

            // 6. LOGIKA PEMILAHAN (Pendaftar vs Siswa)

            if ($statusVerifikasi == 'settlement') {

                if (Str::startsWith($orderId, 'RE-')) {

                    $pembayaranData = DB::table('pembayaran')->where('order_id', $orderId)->first();


                    $siswaData = DB::table('siswa')->where('id_pendaftar', $pembayaranData->id_pendaftar)->first();

                    if ($siswaData && $siswaData->id_level_lanjutan) {
                        DB::table('siswa')
                            ->where('id_siswa', $siswaData->id_siswa)
                            ->update([
                                'id_level' => $siswaData->id_level_lanjutan,

                            ]);


                    }

                } else {
                    // ---- KONDISI B: JIKA PENDAFTAR BARU ----

                    $pembayaranData = DB::table('pembayaran')->where('order_id', $orderId)->first();

                    DB::table('pendaftar')
                        ->where('id_pendaftar', $pembayaranData->id_pendaftar)
                        ->update([
                            'status_pendaftaran' => 'Lunas Placement Test'
                        ]);
                }
            }

            return response()->json(['message' => 'Callback berhasil diproses'], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan pada server',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
