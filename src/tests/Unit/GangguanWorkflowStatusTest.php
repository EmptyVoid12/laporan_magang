<?php

use App\Models\Gangguan;

uses(Tests\TestCase::class);

it('labels pending and verified completion states correctly', function () {
    $pending = new Gangguan([
        'status' => Gangguan::STATUS_SELESAI,
        'submitted_for_verification_at' => now(),
        'verified_at' => null,
    ]);

    $verified = new Gangguan([
        'status' => Gangguan::STATUS_SELESAI,
        'submitted_for_verification_at' => now(),
        'verified_at' => now(),
    ]);

    $regular = new Gangguan([
        'status' => Gangguan::STATUS_PROSES,
    ]);

    expect($pending->workflow_status_label)->toBe('Menunggu Verifikasi Akhir');
    expect($verified->workflow_status_label)->toBe('Selesai Terverifikasi');
    expect($regular->workflow_status_label)->toBe(Gangguan::STATUS_PROSES);
});
