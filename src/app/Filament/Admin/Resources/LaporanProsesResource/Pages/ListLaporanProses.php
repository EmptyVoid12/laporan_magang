<?php

namespace App\Filament\Admin\Resources\LaporanProsesResource\Pages;

use App\Filament\Admin\Resources\LaporanProsesResource;
use App\Models\User;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;

class ListLaporanProses extends ListRecords
{
    protected static string $resource = LaporanProsesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('downloadMonthlyHistory')
                ->label('Download Riwayat Bulanan CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->button()
                ->color('primary')
                ->form([
                    Forms\Components\Select::make('teknisi_id')
                        ->label('Teknisi')
                        ->options(User::query()->where('role', 'teknisi')->orderBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    Forms\Components\TextInput::make('month')
                        ->label('Bulan')
                        ->type('month')
                        ->default(now()->format('Y-m'))
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $this->redirect(route('technician.monthly-history.export', [
                        'teknisi_id' => $data['teknisi_id'],
                        'month' => $data['month'],
                    ]), navigate: false);
                }),
        ];
    }
}
