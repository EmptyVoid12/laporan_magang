<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Illuminate\Support\Facades\DB::getDriverName() !== 'sqlite') {
            Illuminate\Support\Facades\DB::statement("
                ALTER TABLE gangguans
                MODIFY COLUMN status ENUM('Open', 'Diterima', 'Proses', 'Menunggu', 'Selesai', 'Diverifikasi', 'Ditolak')
                DEFAULT 'Open'
            ");
        }

        // Migrate existing verified tickets
        Illuminate\Support\Facades\DB::table('gangguans')
            ->where('status', 'Selesai')
            ->whereNotNull('verified_at')
            ->update(['status' => 'Diverifikasi']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert migrated tickets
        Illuminate\Support\Facades\DB::table('gangguans')
            ->where('status', 'Diverifikasi')
            ->update(['status' => 'Selesai']);

        if (Illuminate\Support\Facades\DB::getDriverName() !== 'sqlite') {
            Illuminate\Support\Facades\DB::statement("
                ALTER TABLE gangguans
                MODIFY COLUMN status ENUM('Open', 'Diterima', 'Proses', 'Menunggu', 'Selesai', 'Ditolak')
                DEFAULT 'Open'
            ");
        }
    }
};
