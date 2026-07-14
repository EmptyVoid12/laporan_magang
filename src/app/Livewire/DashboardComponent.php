<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Gangguan;

class DashboardComponent extends Component
{
    public function render()
    {
        $total = Gangguan::count();
        $open = Gangguan::where('status', Gangguan::STATUS_OPEN)->count();
        $diterima = Gangguan::where('status', Gangguan::STATUS_DITERIMA)->count();
        $proses = Gangguan::where('status', Gangguan::STATUS_PROSES)->count();
        $menunggu = Gangguan::where('status', Gangguan::STATUS_MENUNGGU)->count();
        $selesai = Gangguan::where('status', Gangguan::STATUS_SELESAI)->count();
        $diverifikasi = Gangguan::where('status', Gangguan::STATUS_DIVERIFIKASI)->count();
        $ditolak = Gangguan::where('status', Gangguan::STATUS_DITOLAK)->count();

        $recentGangguan = Gangguan::with(['perangkat', 'teknisi'])->latest()->take(5)->get();
        $laporanPerWilayah = Gangguan::query()
            ->join('perangkats', 'gangguans.perangkat_id', '=', 'perangkats.id')
            ->selectRaw('COALESCE(perangkats.wilayah, "Tanpa Wilayah") as wilayah, COUNT(*) as total')
            ->groupBy('perangkats.wilayah')
            ->orderByDesc('total')
            ->get();

        $laporanPerJenis = Gangguan::query()
            ->join('perangkats', 'gangguans.perangkat_id', '=', 'perangkats.id')
            ->selectRaw('perangkats.jenis as jenis, COUNT(*) as total')
            ->groupBy('perangkats.jenis')
            ->orderByDesc('total')
            ->get();

        $activeAduan = Gangguan::with('perangkat')
            ->whereHas('perangkat', function ($query) {
                $query->whereNotNull('latitude')->whereNotNull('longitude');
            })
            ->whereIn('status', [
                Gangguan::STATUS_OPEN,
                Gangguan::STATUS_DITERIMA,
                Gangguan::STATUS_PROSES,
                Gangguan::STATUS_MENUNGGU,
                Gangguan::STATUS_SELESAI
            ])
            ->get()
            ->map(function ($g) {
                return [
                    'id' => $g->id,
                    'kode_tiket' => $g->kode_tiket,
                    'status' => $g->status,
                    'prioritas' => $g->prioritas,
                    'nama_perangkat' => $g->perangkat->nama_perangkat,
                    'jenis_perangkat' => $g->perangkat->jenis,
                    'lokasi' => $g->perangkat->lokasi,
                    'latitude' => $g->perangkat->latitude,
                    'longitude' => $g->perangkat->longitude,
                ];
            });

        $heatmapData = Gangguan::with('perangkat')
            ->whereHas('perangkat', function ($query) {
                $query->whereNotNull('latitude')->whereNotNull('longitude');
            })
            ->get()
            ->map(function ($g) {
                return [
                    $g->perangkat->latitude,
                    $g->perangkat->longitude,
                    1.0
                ];
            });

        return view('livewire.dashboard-component', [
            'total' => $total,
            'open' => $open,
            'diterima' => $diterima,
            'proses' => $proses,
            'menunggu' => $menunggu,
            'selesai' => $selesai,
            'diverifikasi' => $diverifikasi,
            'ditolak' => $ditolak,
            'recent' => $recentGangguan,
            'laporanPerWilayah' => $laporanPerWilayah,
            'laporanPerJenis' => $laporanPerJenis,
            'activeAduan' => $activeAduan,
            'heatmapData' => $heatmapData,
        ]);
    }
}
