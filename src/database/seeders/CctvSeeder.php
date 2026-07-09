<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perangkat;

class CctvSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/cctv_mapped.json');
        if (!file_exists($jsonPath)) {
            $this->command->error("JSON data file not found at: {$jsonPath}");
            return;
        }

        $cctvData = json_decode(file_get_contents($jsonPath), true);
        if (!$cctvData) {
            $this->command->error("Failed to decode JSON data.");
            return;
        }

        $this->command->info("Seeding " . count($cctvData) . " CCTV devices...");

        foreach ($cctvData as $item) {
            Perangkat::updateOrCreate(
                ['nama_perangkat' => $item['nama_perangkat']],
                [
                    'jenis' => $item['jenis'],
                    'wilayah' => $item['wilayah'],
                    'lokasi' => $item['lokasi'],
                    'deskripsi' => $item['deskripsi'],
                ]
            );
        }

        $this->command->info("CCTV devices seeded successfully!");
    }
}
