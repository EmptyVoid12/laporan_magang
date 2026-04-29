<?php

namespace App\Filament\Admin\Resources\TeknisiResource\Pages;

use App\Filament\Admin\Resources\TeknisiResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTeknisis extends ManageRecords
{
    protected static string $resource = TeknisiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
