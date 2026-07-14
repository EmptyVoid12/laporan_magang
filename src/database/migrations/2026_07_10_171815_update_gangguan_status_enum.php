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
            // Step 1: Temporarily add Diterima to the ENUM
            Illuminate\Support\Facades\DB::statement("
                ALTER TABLE gangguans
                MODIFY COLUMN status ENUM('Open', 'Diverifikasi', 'Diterima', 'Proses', 'Menunggu', 'Selesai', 'Ditolak')
                DEFAULT 'Open'
            ");
        }

        // Step 2: Migrate existing records
        Illuminate\Support\Facades\DB::table('gangguans')
            ->where('status', 'Diverifikasi')
            ->update(['status' => 'Diterima']);

        if (Illuminate\Support\Facades\DB::getDriverName() !== 'sqlite') {
            // Step 3: Save final ENUM without Diverifikasi
            Illuminate\Support\Facades\DB::statement("
                ALTER TABLE gangguans
                MODIFY COLUMN status ENUM('Open', 'Diterima', 'Proses', 'Menunggu', 'Selesai', 'Ditolak')
                DEFAULT 'Open'
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Illuminate\Support\Facades\DB::getDriverName() !== 'sqlite') {
            // Step 1: Temporarily add Diverifikasi to the ENUM
            Illuminate\Support\Facades\DB::statement("
                ALTER TABLE gangguans
                MODIFY COLUMN status ENUM('Open', 'Diverifikasi', 'Diterima', 'Proses', 'Menunggu', 'Selesai', 'Ditolak')
                DEFAULT 'Open'
            ");
        }

        // Step 2: Revert migrated records
        Illuminate\Support\Facades\DB::table('gangguans')
            ->where('status', 'Diterima')
            ->update(['status' => 'Diverifikasi']);

        if (Illuminate\Support\Facades\DB::getDriverName() !== 'sqlite') {
            // Step 3: Save old ENUM without Diterima
            Illuminate\Support\Facades\DB::statement("
                ALTER TABLE gangguans
                MODIFY COLUMN status ENUM('Open', 'Diverifikasi', 'Proses', 'Menunggu', 'Selesai', 'Ditolak')
                DEFAULT 'Open'
            ");
        }
    }
};
