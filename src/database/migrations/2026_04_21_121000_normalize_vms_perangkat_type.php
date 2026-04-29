<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('perangkats')
            ->where('jenis', 'VMS')
            ->update(['jenis' => 'Lainnya']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('perangkats')
            ->where('jenis', 'Lainnya')
            ->where('nama_perangkat', 'like', '%VMS%')
            ->update(['jenis' => 'VMS']);
    }
};
