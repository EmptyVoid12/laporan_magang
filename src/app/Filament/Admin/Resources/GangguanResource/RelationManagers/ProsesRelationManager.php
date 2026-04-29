<?php

namespace App\Filament\Admin\Resources\GangguanResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProsesRelationManager extends RelationManager
{
    protected static string $relationship = 'proses';
    protected static ?string $title = 'Riwayat Progress & Tindakan Teknisi';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal_update')->required(),
                Forms\Components\Select::make('tipe_update')
                    ->options([
                        'report' => 'Report',
                        'assignment' => 'Assignment',
                        'status' => 'Status',
                        'progress' => 'Progress',
                        'completion' => 'Completion',
                    ])
                    ->default('progress')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->default(auth()->id())
                    ->searchable(),
                Forms\Components\Textarea::make('keterangan_proses')->required(),
                Forms\Components\Textarea::make('kendala'),
                Forms\Components\Select::make('teknisi_id')
                    ->relationship('teknisi', 'name')
                    ->searchable(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tanggal_update')->date('d M Y'),
                Tables\Columns\TextColumn::make('tipe_update')->badge(),
                Tables\Columns\TextColumn::make('user.name')->label('Aktor'),
                Tables\Columns\TextColumn::make('keterangan_proses')->wrap(),
                Tables\Columns\TextColumn::make('kendala')->wrap(),
                Tables\Columns\TextColumn::make('teknisi.name'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
