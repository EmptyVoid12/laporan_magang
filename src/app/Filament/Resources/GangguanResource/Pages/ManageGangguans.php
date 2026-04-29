<?php

namespace App\Filament\Resources\GangguanResource\Pages;

use App\Filament\Resources\GangguanResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageGangguans extends ManageRecords
{
    protected static string $resource = GangguanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
