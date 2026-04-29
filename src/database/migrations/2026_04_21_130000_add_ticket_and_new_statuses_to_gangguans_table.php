<?php

use App\Models\Gangguan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('gangguans', function (Blueprint $table) {
            $table->string('kode_tiket')->nullable()->after('id');
        });

        DB::table('gangguans')
            ->select('id', 'tanggal')
            ->orderBy('id')
            ->get()
            ->each(function ($gangguan): void {
                DB::table('gangguans')
                    ->where('id', $gangguan->id)
                    ->update([
                        'kode_tiket' => Gangguan::generateKodeTiket($gangguan->id, $gangguan->tanggal),
                    ]);
            });

        Schema::table('gangguans', function (Blueprint $table) {
            $table->unique('kode_tiket');
        });

        DB::statement("
            ALTER TABLE gangguans
            MODIFY COLUMN status ENUM('Open', 'Diverifikasi', 'Proses', 'Menunggu', 'Selesai', 'Ditolak')
            DEFAULT 'Open'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('gangguans')
            ->whereIn('status', ['Diverifikasi', 'Menunggu'])
            ->update(['status' => 'Proses']);

        DB::table('gangguans')
            ->where('status', 'Ditolak')
            ->update(['status' => 'Open']);

        DB::statement("
            ALTER TABLE gangguans
            MODIFY COLUMN status ENUM('Open', 'Proses', 'Selesai')
            DEFAULT 'Open'
        ");

        Schema::table('gangguans', function (Blueprint $table) {
            $table->dropUnique(['kode_tiket']);
            $table->dropColumn('kode_tiket');
        });
    }
};
