<?php

namespace App\Filament\Admin\Resources\LaporanProsesResource\Pages;

use App\Filament\Admin\Resources\LaporanProsesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLaporanProses extends ListRecords
{
    protected static string $resource = LaporanProsesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action because history is only generated automatically via Teknisi UI
        ];
    }
}
