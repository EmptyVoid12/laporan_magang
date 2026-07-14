<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Gangguan;
use App\Models\Perangkat;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class MapDashboardWidget extends Widget
{
    protected const DEFAULT_CENTER = [-6.2088, 106.8456];

    protected const ACTIVE_STATUSES = [
        Gangguan::STATUS_OPEN,
        Gangguan::STATUS_DITERIMA,
        Gangguan::STATUS_PROSES,
        Gangguan::STATUS_MENUNGGU,
    ];

    protected static string $view = 'filament.admin.widgets.map-dashboard-widget';

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $mappedDevices = Perangkat::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderBy('nama_perangkat')
            ->get();

        $hotspots = Perangkat::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->withCount('gangguans')
            ->withCount([
                'gangguans as active_gangguans_count' => fn ($query) => $query->whereIn('status', self::ACTIVE_STATUSES),
            ])
            ->withMax('gangguans', 'created_at')
            ->get()
            ->filter(fn (Perangkat $perangkat) => $perangkat->gangguans_count > 0)
            ->sort(fn (Perangkat $left, Perangkat $right) => [
                $right->gangguans_count,
                $right->active_gangguans_count,
            ] <=> [
                $left->gangguans_count,
                $left->active_gangguans_count,
            ])
            ->values();

        $maxGangguanCount = max(1, (int) $hotspots->max('gangguans_count'));

        $hotspotPoints = $hotspots->map(function (Perangkat $perangkat, int $index) use ($maxGangguanCount) {
            $lastReportedAt = $perangkat->gangguans_max_created_at
                ? Carbon::parse($perangkat->gangguans_max_created_at)->format('d M Y H:i')
                : null;

            return [
                'id' => $perangkat->id,
                'rank' => $index + 1,
                'nama_perangkat' => $perangkat->nama_perangkat,
                'jenis_perangkat' => $perangkat->jenis,
                'wilayah' => $perangkat->wilayah,
                'lokasi' => $perangkat->lokasi,
                'latitude' => (float) $perangkat->latitude,
                'longitude' => (float) $perangkat->longitude,
                'total_laporan' => (int) $perangkat->gangguans_count,
                'aduan_aktif' => (int) $perangkat->active_gangguans_count,
                'laporan_terakhir' => $lastReportedAt,
                'bobot_peta' => round(max($perangkat->gangguans_count / $maxGangguanCount, 0.2), 3),
            ];
        });

        $heatmapData = $hotspotPoints->map(function (array $hotspot) {
            return [
                $hotspot['latitude'],
                $hotspot['longitude'],
                $hotspot['bobot_peta'],
            ];
        });

        $hasHotspotData = $hotspotPoints->isNotEmpty();
        $displayPoints = $hasHotspotData
            ? $hotspotPoints
            : $mappedDevices->map(function (Perangkat $perangkat) {
                return [
                    'id' => $perangkat->id,
                    'rank' => null,
                    'nama_perangkat' => $perangkat->nama_perangkat,
                    'jenis_perangkat' => $perangkat->jenis,
                    'wilayah' => $perangkat->wilayah,
                    'lokasi' => $perangkat->lokasi,
                    'latitude' => (float) $perangkat->latitude,
                    'longitude' => (float) $perangkat->longitude,
                    'total_laporan' => 0,
                    'aduan_aktif' => 0,
                    'laporan_terakhir' => null,
                    'bobot_peta' => 0.2,
                ];
            });

        $topHotspot = $hotspotPoints->first();

        return [
            'mapCenter' => self::DEFAULT_CENTER,
            'hotspots' => $displayPoints,
            'heatmapData' => $hasHotspotData ? $heatmapData : collect(),
            'hasHotspotData' => $hasHotspotData,
            'topHotspot' => $topHotspot,
            'hotspotCount' => $hotspotPoints->count(),
            'mappedDeviceCount' => $mappedDevices->count(),
            'activeHotspotCount' => $hotspotPoints->where('aduan_aktif', '>', 0)->count(),
        ];
    }
}
