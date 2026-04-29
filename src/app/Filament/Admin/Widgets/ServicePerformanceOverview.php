<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Gangguan;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ServicePerformanceOverview extends BaseWidget
{
    use HasWidgetShield;

    protected function getStats(): array
    {
        $tickets = Gangguan::query()->get();
        $verified = $tickets->filter(fn (Gangguan $gangguan) => $gangguan->isFinallyVerified());
        $avgResolution = $verified->isNotEmpty()
            ? round($verified->avg(fn (Gangguan $gangguan) => $gangguan->created_at->diffInHours($gangguan->verified_at)), 2)
            : null;

        return [
            Stat::make('Menunggu Verifikasi Akhir', (string) $tickets->filter(fn (Gangguan $gangguan) => $gangguan->isAwaitingFinalVerification())->count())
                ->description('Tiket selesai yang belum dicek admin')
                ->color('warning'),
            Stat::make('Selesai Terverifikasi', (string) $verified->count())
                ->description('Tiket yang sudah closed final')
                ->color('success'),
            Stat::make('Rata-rata Waktu Penyelesaian Laporan', $avgResolution !== null ? $avgResolution . ' jam' : '-')
                ->description('Dari tiket dibuat sampai verified')
                ->color('info'),
        ];
    }
}
