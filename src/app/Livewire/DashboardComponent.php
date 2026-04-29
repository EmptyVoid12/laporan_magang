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
        $diverifikasi = Gangguan::where('status', Gangguan::STATUS_DIVERIFIKASI)->count();
        $proses = Gangguan::where('status', Gangguan::STATUS_PROSES)->count();
        $menunggu = Gangguan::where('status', Gangguan::STATUS_MENUNGGU)->count();
        $selesai = Gangguan::where('status', Gangguan::STATUS_SELESAI)->count();
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

        return view('livewire.dashboard-component', [
            'total' => $total,
            'open' => $open,
            'diverifikasi' => $diverifikasi,
            'proses' => $proses,
            'menunggu' => $menunggu,
            'selesai' => $selesai,
            'ditolak' => $ditolak,
            'recent' => $recentGangguan,
            'laporanPerWilayah' => $laporanPerWilayah,
            'laporanPerJenis' => $laporanPerJenis,
        ]);
    }
}
