<?php

namespace App\Filament\Admin\Resources\PerangkatResource\Pages;

use App\Filament\Admin\Resources\PerangkatResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePerangkats extends ManageRecords
{
    protected static string $resource = PerangkatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
