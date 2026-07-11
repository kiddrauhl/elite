<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Buat tabel penampung log riwayat terlebih dahulu
        Schema::create('log_perpindahan_kelas', function (Blueprint $table) {
            $table->id('id_log');
            $table->integer('id_siswa');
            $table->integer('id_kelas_lama')->nullable();
            $table->integer('id_kelas_baru')->nullable();
            $table->integer('id_level_lama')->nullable();
            $table->integer('id_level_baru')->nullable();
            $table->timestamp('waktu_perubahan')->useCurrent();
        });

        // 2. Buat Trigger MySQL
        DB::unprepared("
            CREATE TRIGGER setelah_siswa_pindah_kelas
            AFTER UPDATE ON siswa
            FOR EACH ROW
            BEGIN
                -- Memeriksa apakah terjadi perubahan pada id_kelas atau id_level
                IF (OLD.id_kelas <=> NEW.id_kelas) = 0 OR (OLD.id_level <=> NEW.id_level) = 0 THEN
                    INSERT INTO log_perpindahan_kelas (
                        id_siswa,
                        id_kelas_lama,
                        id_kelas_baru,
                        id_level_lama,
                        id_level_baru,
                        waktu_perubahan
                    )
                    VALUES (
                        OLD.id_siswa,
                        OLD.id_kelas,
                        NEW.id_kelas,
                        OLD.id_level,
                        NEW.id_level,
                        NOW()
                    );
                END IF;
            END
        ");
    }

    public function down(): void
    {
        // Hapus trigger dan tabel jika migration di-rollback
        DB::unprepared("DROP TRIGGER IF EXISTS setelah_siswa_pindah_kelas");
        Schema::dropIfExists('log_perpindahan_kelas');
    }
};
