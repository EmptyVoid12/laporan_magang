<?php

use App\Models\LaporanProses;
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
        Schema::table('laporan_proses', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('teknisi_id')->constrained('users')->nullOnDelete();
            $table->string('tipe_update')->default(LaporanProses::TYPE_PROGRESS)->after('user_id');
        });

        DB::table('laporan_proses')
            ->whereNull('user_id')
            ->update(['user_id' => DB::raw('teknisi_id')]);

        Schema::table('laporan_proses', function (Blueprint $table) {
            $table->foreignId('teknisi_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('laporan_proses')
            ->whereNull('teknisi_id')
            ->delete();

        Schema::table('laporan_proses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn('tipe_update');
        });

        Schema::table('laporan_proses', function (Blueprint $table) {
            $table->foreignId('teknisi_id')->nullable(false)->change();
        });
    }
};
