<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Perangkat;

class DummySeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@noc.com'], [
            'name' => 'Admin Utama',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        User::updateOrCreate(['email' => 'operator@noc.com'], [
            'name' => 'Operator Lapangan',
            'password' => Hash::make('password'),
            'role' => 'operator'
        ]);

        User::updateOrCreate(['email' => 'teknisi@noc.com'], [
            'name' => 'Teknisi Handal',
            'password' => Hash::make('password'),
            'role' => 'teknisi'
        ]);
        
        User::updateOrCreate(['email' => 'teknisi2@noc.com'], [
            'name' => 'Teknisi Pendukung',
            'password' => Hash::make('password'),
            'role' => 'teknisi'
        ]);

        Perangkat::updateOrCreate(['nama_perangkat' => 'CCTV 01'], ['jenis' => 'CCTV', 'wilayah' => 'Jakarta Pusat', 'lokasi' => 'Simpang RS', 'deskripsi' => 'CCTV Utama']);
        Perangkat::updateOrCreate(['nama_perangkat' => 'CCTV 02'], ['jenis' => 'CCTV', 'wilayah' => 'Jakarta Barat', 'lokasi' => 'Simpang Lima', 'deskripsi' => 'CCTV Tambahan']);
        Perangkat::updateOrCreate(['nama_perangkat' => 'Traffic Light A'], ['jenis' => 'Traffic Light', 'wilayah' => 'Jakarta Selatan', 'lokasi' => 'Jalan Sudirman', 'deskripsi' => 'Lampu Lalu Lintas Utama']);
    }
}
