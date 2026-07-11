use Midtrans\Config;
use Midtrans\Snap;

public function bayar(Request $request, $id_pendaftaran) {
    // 1. Ambil data pendaftaran dan pendaftar dari database
    $pendaftaran = Pendaftaran::find($id_pendaftaran);
    $pendaftar = Pendaftar::find($pendaftaran->id_pendaftar);

    // 2. Buat record di tabel pembayaran dengan status 'pending'
    $pembayaran = Pembayaran::create([
        'id_pendaftar' => $pendaftar->id_pendaftar,
        'tanggal_bayar' => now(),
        'status_verifikasi' => 'pending'
        // bukti_pembayaran tidak wajib lagi jika pakai Midtrans
    ]);

    // 3. Konfigurasi Midtrans
    Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
    Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
    Config::$is3ds = env('MIDTRANS_IS_3DS');

    // 4. Siapkan parameter pesanan
    $params = [
        'transaction_details' => [
            'order_id' => 'ELITE-' . $pembayaran->id_pembayaran . '-' . time(),
            'gross_amount' => 500000, // Harga kursus, bisa diambil dari tabel Level
        ],
        'customer_details' => [
            'first_name' => $pendaftar->nama_lengkap,
            'email' => $pendaftar->user->email,
            'phone' => $pendaftar->no_hp,
        ]
    ];

    // 5. Dapatkan Token dari Midtrans
    $snapToken = Snap::getSnapToken($params);

    // Kirim token ini ke halaman view (blade) untuk memunculkan tombol bayar
    return view('pembayaran.checkout', compact('snapToken', 'pembayaran'));
}
