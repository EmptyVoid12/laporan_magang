<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Perangkat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Users
        User::updateOrCreate(
            ['email' => 'admin@noc.com'],
            ['name' => 'Admin NOC', 'password' => Hash::make('password'), 'role' => 'admin']
        );

        User::updateOrCreate(
            ['email' => 'operator@noc.com'],
            ['name' => 'Operator Lapangan', 'password' => Hash::make('password'), 'role' => 'operator']
        );

        User::updateOrCreate(
            ['email' => 'teknisi@noc.com'],
            ['name' => 'Teknisi A', 'password' => Hash::make('password'), 'role' => 'teknisi']
        );

        User::updateOrCreate(
            ['email' => 'teknisi2@noc.com'],
            ['name' => 'Teknisi B', 'password' => Hash::make('password'), 'role' => 'teknisi']
        );

        // Seed default 3 devices with coordinates
        Perangkat::updateOrCreate(['nama_perangkat' => 'CCTV 01'], [
            'jenis' => 'CCTV',
            'wilayah' => 'Jakarta Pusat',
            'lokasi' => 'Gambir',
            'latitude' => -6.1865,
            'longitude' => 106.8294,
            'deskripsi' => 'CCTV Utama'
        ]);
        Perangkat::updateOrCreate(['nama_perangkat' => 'CCTV 02'], [
            'jenis' => 'CCTV',
            'wilayah' => 'Jakarta Barat',
            'lokasi' => 'Grogol Petamburan',
            'latitude' => -6.1683,
            'longitude' => 106.7588,
            'deskripsi' => 'CCTV Tambahan'
        ]);
        Perangkat::updateOrCreate(['nama_perangkat' => 'Traffic Light A'], [
            'jenis' => 'Traffic Light',
            'wilayah' => 'Jakarta Selatan',
            'lokasi' => 'Kebayoran Baru',
            'latitude' => -6.2615,
            'longitude' => 106.8106,
            'deskripsi' => 'Lampu Lalu Lintas Utama'
        ]);
    }
}
