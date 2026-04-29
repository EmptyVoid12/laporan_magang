<?php

use App\Livewire\HomeComponent;
use App\Models\Gangguan;
use App\Models\LaporanProses;
use App\Models\Perangkat;
use App\Models\User;
use App\Notifications\TicketActivityNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('allows a pelapor to open their own ticket detail page', function () {
    $pelapor = User::factory()->create([
        'role' => 'user',
    ]);

    $teknisi = User::factory()->create([
        'role' => 'teknisi',
    ]);

    $perangkat = Perangkat::create([
        'nama_perangkat' => 'CCTV Test',
        'jenis' => 'CCTV',
        'wilayah' => 'Jakarta Pusat',
        'lokasi' => 'Bundle Test',
        'deskripsi' => 'Perangkat untuk test',
    ]);

    $gangguan = Gangguan::create([
        'perangkat_id' => $perangkat->id,
        'deskripsi' => 'Gambar tidak tampil',
        'tanggal' => now()->toDateString(),
        'status' => Gangguan::STATUS_PROSES,
        'prioritas' => 'Sedang',
        'operator_id' => $pelapor->id,
        'teknisi_id' => $teknisi->id,
    ]);

    LaporanProses::create([
        'gangguan_id' => $gangguan->id,
        'user_id' => $teknisi->id,
        'teknisi_id' => $teknisi->id,
        'tipe_update' => LaporanProses::TYPE_PROGRESS,
        'keterangan_proses' => 'Pemeriksaan awal dilakukan',
        'tanggal_update' => now()->toDateString(),
    ]);

    $response = $this->actingAs($pelapor)->get(route('user.gangguan.show', $gangguan));

    $response->assertOk();
    $response->assertSee($gangguan->kode_tiket);
    $response->assertSee('Pemeriksaan awal dilakukan');
});

it('returns csv export for admin reports', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
    ]);

    $perangkat = Perangkat::create([
        'nama_perangkat' => 'Traffic Light Test',
        'jenis' => 'Traffic Light',
        'wilayah' => 'Jakarta Selatan',
        'lokasi' => 'Perempatan Test',
        'deskripsi' => 'Perangkat untuk test export',
    ]);

    $gangguan = Gangguan::create([
        'perangkat_id' => $perangkat->id,
        'deskripsi' => 'Lampu tidak sinkron',
        'tanggal' => now()->toDateString(),
        'status' => Gangguan::STATUS_OPEN,
        'prioritas' => 'Tinggi',
        'operator_id' => $admin->id,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.reports.export', 'tickets'));

    $response->assertOk();
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    $response->assertSee($gangguan->kode_tiket, false);
});

it('marks all notifications as read for the authenticated user', function () {
    $user = User::factory()->create([
        'role' => 'user',
    ]);

    $user->notify(new TicketActivityNotification(
        'Tes Notifikasi',
        'Notifikasi untuk memastikan pusat notifikasi bekerja.',
        route('home'),
    ));

    expect($user->fresh()->unreadNotifications)->toHaveCount(1);

    $this->actingAs($user)
        ->post(route('notifications.read-all'))
        ->assertRedirect();

    expect($user->fresh()->unreadNotifications)->toHaveCount(0);
});

it('shows final verified tickets as verified on the home page', function () {
    $operator = User::factory()->create([
        'role' => 'user',
    ]);

    $perangkat = Perangkat::create([
        'nama_perangkat' => 'Camera Koridor',
        'jenis' => 'CCTV',
        'wilayah' => 'Jakarta Timur',
        'lokasi' => 'Lantai 2',
        'deskripsi' => 'Perangkat untuk test home page',
    ]);

    Gangguan::create([
        'perangkat_id' => $perangkat->id,
        'deskripsi' => 'Laporan sudah diverifikasi awal',
        'tanggal' => now()->subDay()->toDateString(),
        'status' => Gangguan::STATUS_DIVERIFIKASI,
        'prioritas' => 'Sedang',
        'operator_id' => $operator->id,
    ]);

    Gangguan::create([
        'perangkat_id' => $perangkat->id,
        'deskripsi' => 'Perbaikan selesai dan diverifikasi final',
        'tanggal' => now()->toDateString(),
        'status' => Gangguan::STATUS_SELESAI,
        'prioritas' => 'Tinggi',
        'operator_id' => $operator->id,
        'submitted_for_verification_at' => now()->subHour(),
        'verified_at' => now(),
    ]);

    Livewire::test(HomeComponent::class)
        ->assertViewHas('diverifikasi', 1);

    $this->get(route('home'))
        ->assertOk()
        ->assertSeeInOrder(['Diproses', 'Terverifikasi'])
        ->assertSee('Selesai Terverifikasi');
});
