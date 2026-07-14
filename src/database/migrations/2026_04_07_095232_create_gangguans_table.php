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
        Schema::create('gangguans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perangkat_id')->constrained('perangkats')->onDelete('cascade');
            $table->text('deskripsi');
            $table->date('tanggal');
            $table->enum('status', ['Open', 'Proses', 'Selesai'])->default('Open');
            $table->enum('prioritas', ['Rendah', 'Sedang', 'Tinggi'])->default('Sedang');
            $table->foreignId('operator_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('teknisi_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gangguans');
    }
};
