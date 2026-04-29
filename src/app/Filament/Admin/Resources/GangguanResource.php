<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\GangguanResource\Pages;
use App\Models\Gangguan;
use App\Models\Perangkat;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GangguanResource extends Resource
{
    protected static ?string $model = Gangguan::class;
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationGroup = 'Laporan Layanan';
    protected static ?string $modelLabel = 'Laporan Gangguan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kode_tiket')
                    ->disabled()
                    ->dehydrated(false),
                Forms\Components\Select::make('perangkat_id')
                    ->label('Perangkat')
                    ->options(fn () => Perangkat::query()
                        ->orderBy('nama_perangkat')
                        ->get()
                        ->mapWithKeys(fn (Perangkat $perangkat) => [
                            $perangkat->id => "{$perangkat->nama_perangkat} - {$perangkat->jenis} - " . ($perangkat->wilayah ?: 'Tanpa Wilayah') . " ({$perangkat->lokasi})",
                        ])
                        ->all())
                    ->searchable()
                    ->required(),
                Forms\Components\DatePicker::make('tanggal')->required(),
                Forms\Components\Select::make('prioritas')
                    ->options(Gangguan::PRIORITAS_OPTIONS)
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options(Gangguan::STATUS_OPTIONS)
                    ->required(),
                Forms\Components\Select::make('teknisi_id')
                    ->label('Assign Teknisi')
                    ->options(User::where('role', 'teknisi')->pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),
                Forms\Components\Textarea::make('deskripsi')->required()->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_tiket')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('tanggal')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('perangkat.nama_perangkat')->searchable(),
                Tables\Columns\TextColumn::make('perangkat.jenis')->label('Jenis')->searchable(),
                Tables\Columns\TextColumn::make('perangkat.wilayah')->label('Wilayah')->searchable(),
                Tables\Columns\TextColumn::make('operator.name')->label('Pelapor'),
                Tables\Columns\TextColumn::make('teknisi.name')->label('Teknisi'),
                Tables\Columns\TextColumn::make('prioritas')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Tinggi' => 'danger',
                        'Sedang' => 'warning',
                        'Rendah' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Open' => 'danger',
                        'Diverifikasi' => 'info',
                        'Proses' => 'warning',
                        'Menunggu' => 'warning',
                        'Selesai' => 'success',
                        'Ditolak' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(Gangguan::STATUS_OPTIONS),
                Tables\Filters\SelectFilter::make('prioritas')
                    ->options(Gangguan::PRIORITAS_OPTIONS),
                Tables\Filters\SelectFilter::make('jenis')
                    ->options(Perangkat::JENIS_OPTIONS)
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'] ?? null,
                        fn (Builder $query, $value) => $query->whereHas('perangkat', fn (Builder $perangkatQuery) => $perangkatQuery->where('jenis', $value))
                    )),
                Tables\Filters\SelectFilter::make('wilayah')
                    ->options(Perangkat::WILAYAH_OPTIONS)
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['value'] ?? null,
                        fn (Builder $query, $value) => $query->whereHas('perangkat', fn (Builder $perangkatQuery) => $perangkatQuery->where('wilayah', $value))
                    )),
                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'] ?? null, fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date))
                            ->when($data['until'] ?? null, fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date));
                    }),
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

    public static function getRelations(): array
    {
        return [
            \App\Filament\Admin\Resources\GangguanResource\RelationManagers\ProsesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGangguans::route('/'),
            'create' => Pages\CreateGangguan::route('/create'),
            'edit' => Pages\EditGangguan::route('/{record}/edit'),
        ];
    }
}
