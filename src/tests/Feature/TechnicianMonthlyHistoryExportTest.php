<?php

use App\Models\Gangguan;
use App\Models\LaporanProses;
use App\Models\Perangkat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows teknisi to download their own monthly work history', function () {
    $teknisi = User::factory()->create([
        'role' => 'teknisi',
    ]);

    $operator = User::factory()->create([
        'role' => 'user',
    ]);

    $perangkat = Perangkat::create([
        'nama_perangkat' => 'CCTV Export',
        'jenis' => 'CCTV',
        'wilayah' => 'Jakarta Pusat',
        'lokasi' => 'Lokasi Export',
        'deskripsi' => 'Perangkat export',
    ]);

    $gangguan = Gangguan::create([
        'perangkat_id' => $perangkat->id,
        'deskripsi' => 'Gangguan untuk export',
        'tanggal' => '2026-04-10',
        'status' => Gangguan::STATUS_PROSES,
        'prioritas' => 'Sedang',
        'operator_id' => $operator->id,
        'teknisi_id' => $teknisi->id,
    ]);

    LaporanProses::create([
        'gangguan_id' => $gangguan->id,
        'user_id' => $teknisi->id,
        'teknisi_id' => $teknisi->id,
        'tipe_update' => LaporanProses::TYPE_PROGRESS,
        'keterangan_proses' => 'Pengecekan jaringan dilakukan',
        'tanggal_update' => '2026-04-12',
    ]);

    $response = $this->actingAs($teknisi)
        ->get(route('technician.monthly-history.export', ['month' => '2026-04']));

    $response->assertOk();
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    $response->assertSee('Riwayat Kerja Bulanan Teknisi', false);
    $response->assertSee('Jumlah Update Bulan Ini', false);
    $response->assertSee('Pengecekan jaringan dilakukan', false);
});

it('prevents teknisi from downloading another technicians monthly work history', function () {
    $teknisi = User::factory()->create([
        'role' => 'teknisi',
    ]);

    $teknisiLain = User::factory()->create([
        'role' => 'teknisi',
    ]);

    $this->actingAs($teknisi)
        ->get(route('technician.monthly-history.export', [
            'month' => '2026-04',
            'teknisi_id' => $teknisiLain->id,
        ]))
        ->assertForbidden();
});

it('allows admin guard user to download selected technicians monthly work history', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $teknisi = User::factory()->create([
        'role' => 'teknisi',
        'name' => 'Teknisi Export',
    ]);

    $operator = User::factory()->create([
        'role' => 'user',
    ]);

    $perangkat = Perangkat::create([
        'nama_perangkat' => 'Traffic Light Export',
        'jenis' => 'Traffic Light',
        'wilayah' => 'Jakarta Selatan',
        'lokasi' => 'Simpang Export',
        'deskripsi' => 'Perangkat admin export',
    ]);

    $gangguan = Gangguan::create([
        'perangkat_id' => $perangkat->id,
        'deskripsi' => 'Lampu padam',
        'tanggal' => '2026-04-14',
        'status' => Gangguan::STATUS_PROSES,
        'prioritas' => 'Tinggi',
        'operator_id' => $operator->id,
        'teknisi_id' => $teknisi->id,
    ]);

    LaporanProses::create([
        'gangguan_id' => $gangguan->id,
        'user_id' => $teknisi->id,
        'teknisi_id' => $teknisi->id,
        'tipe_update' => LaporanProses::TYPE_COMPLETION,
        'keterangan_proses' => 'Perbaikan selesai',
        'tanggal_update' => '2026-04-20',
    ]);

    $response = $this->actingAs($admin, 'admin')
        ->get(route('technician.monthly-history.export', [
            'month' => '2026-04',
            'teknisi_id' => $teknisi->id,
        ]));

    $response->assertOk();
    $response->assertSee('Teknisi Export', false);
    $response->assertSee('Jumlah Update Bulan Ini', false);
    $response->assertSee('Perbaikan selesai', false);
});

it('prioritizes the admin session when admin and web sessions are both active', function () {
    $webUser = User::factory()->create([
        'role' => 'user',
    ]);

    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $teknisi = User::factory()->create([
        'role' => 'teknisi',
        'name' => 'Teknisi Panel',
    ]);

    $operator = User::factory()->create([
        'role' => 'user',
    ]);

    $perangkat = Perangkat::create([
        'nama_perangkat' => 'Server Ruang Kontrol',
        'jenis' => 'Server',
        'wilayah' => 'Jakarta Timur',
        'lokasi' => 'Ruang Kontrol',
        'deskripsi' => 'Perangkat untuk uji multi-guard export',
    ]);

    $gangguan = Gangguan::create([
        'perangkat_id' => $perangkat->id,
        'deskripsi' => 'Server perlu ditinjau',
        'tanggal' => '2026-04-17',
        'status' => Gangguan::STATUS_PROSES,
        'prioritas' => 'Sedang',
        'operator_id' => $operator->id,
        'teknisi_id' => $teknisi->id,
    ]);

    LaporanProses::create([
        'gangguan_id' => $gangguan->id,
        'user_id' => $teknisi->id,
        'teknisi_id' => $teknisi->id,
        'tipe_update' => LaporanProses::TYPE_PROGRESS,
        'keterangan_proses' => 'Analisis awal dilakukan',
        'tanggal_update' => '2026-04-19',
    ]);

    $response = $this->actingAs($webUser)
        ->actingAs($admin, 'admin')
        ->get(route('technician.monthly-history.export', [
            'month' => '2026-04',
            'teknisi_id' => $teknisi->id,
        ]));

    $response->assertOk();
    $response->assertSee('Teknisi Panel', false);
    $response->assertSee('Analisis awal dilakukan', false);
});
