<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LaporanProsesResource\Pages;
use App\Models\LaporanProses;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class LaporanProsesResource extends Resource
{
    protected static ?string $model = LaporanProses::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Riwayat Kerja Teknisi';

    protected static ?string $pluralModelLabel = 'Riwayat Kerja Teknisi';

    protected static ?string $modelLabel = 'Riwayat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('gangguan_id')
                    ->relationship('gangguan', 'deskripsi')
                    ->disabled(),
                Forms\Components\TextInput::make('tipe_update')
                    ->disabled(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->disabled(),
                Forms\Components\Select::make('teknisi_id')
                    ->relationship('teknisi', 'name')
                    ->disabled(),
                Forms\Components\Textarea::make('keterangan_proses')
                    ->disabled(),
                Forms\Components\Textarea::make('kendala')
                    ->disabled(),
                Forms\Components\DatePicker::make('tanggal_update')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gangguan.kode_tiket')
                    ->label('Tiket')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('gangguan.perangkat.nama_perangkat')
                    ->label('Perangkat')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('gangguan.status')
                    ->label('Status Laporan')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Selesai' => 'success',
                        'Proses' => 'warning',
                        'Diverifikasi' => 'info',
                        'Menunggu' => 'gray',
                        'Ditolak' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('gangguan.teknisi.name')
                    ->label('Teknisi')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Belum diassign'),
                Tables\Columns\TextColumn::make('gangguan.proses_count')
                    ->label('Total Progres')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('tanggal_update')
                    ->date('d M Y')
                    ->sortable()
                    ->label('Update Terakhir'),
                Tables\Columns\TextColumn::make('tipe_update')
                    ->label('Jenis Update Terakhir')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'report' => 'Laporan Dibuat',
                        'assignment' => 'Penugasan',
                        'status' => 'Perubahan Status',
                        'progress' => 'Progress',
                        'completion' => 'Penyelesaian',
                        'verification' => 'Verifikasi Akhir',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'report' => 'info',
                        'assignment' => 'warning',
                        'status' => 'gray',
                        'progress' => 'primary',
                        'completion' => 'success',
                        'verification' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('attachment_path')
                    ->label('Lampiran')
                    ->boolean()
                    ->trueIcon('heroicon-o-paper-clip')
                    ->falseIcon('heroicon-o-minus'),
                Tables\Columns\TextColumn::make('keterangan_proses')
                    ->label('Ringkasan Progress Terakhir')
                    ->searchable()
                    ->wrap()
                    ->limit(70),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('View')
                    ->modalHeading(fn (LaporanProses $record): string => 'Detail Riwayat ' . ($record->gangguan?->kode_tiket ?? 'Laporan'))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalWidth('5xl')
                    ->modalContent(fn (LaporanProses $record): View => view(
                        'filament.admin.resources.laporan-proses-resource.timeline',
                        [
                            'gangguan' => $record->gangguan?->load([
                                'perangkat',
                                'teknisi',
                                'operator',
                                'proses.user',
                                'proses.teknisi',
                            ]),
                        ]
                    )),
            ])
            ->defaultSort('tanggal_update', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanProses::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereIn('id', LaporanProses::query()
                ->selectRaw('MAX(id)')
                ->whereNotNull('gangguan_id')
                ->groupBy('gangguan_id'))
            ->with([
                'gangguan' => fn ($query) => $query
                    ->with(['perangkat', 'teknisi', 'operator'])
                    ->withCount('proses'),
            ]);
    }
}
