<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PerangkatResource\Pages;
use App\Models\Perangkat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PerangkatResource extends Resource
{
    protected static ?string $model = Perangkat::class;
    protected static ?string $navigationIcon = 'heroicon-o-server';
    protected static ?string $navigationGroup = 'Master Data';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_perangkat')->required(),
                Forms\Components\Select::make('jenis')
                    ->options(Perangkat::JENIS_OPTIONS)
                    ->required(),
                Forms\Components\Select::make('wilayah')
                    ->options(Perangkat::WILAYAH_OPTIONS)
                    ->required(),
                Forms\Components\TextInput::make('lokasi')->required(),
                Forms\Components\Textarea::make('deskripsi'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_perangkat')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('jenis')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('wilayah')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('lokasi')->searchable(),
            ])
            ->filters([
                //
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePerangkats::route('/'),
        ];
    }
}
