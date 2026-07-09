<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perangkat;

class TrafficLightSeeder extends Seeder
{
    public function run(): void
    {
        $jsonPath = database_path('data/traffic_lights_mapped.json');
        if (!file_exists($jsonPath)) {
            $this->command->error("JSON data file not found at: {$jsonPath}");
            return;
        }

        $tlData = json_decode(file_get_contents($jsonPath), true);
        if (!$tlData) {
            $this->command->error("Failed to decode JSON data.");
            return;
        }

        $this->command->info("Seeding " . count($tlData) . " Traffic Light devices...");

        foreach ($tlData as $item) {
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

        $this->command->info("Traffic Light devices seeded successfully!");
    }
}
