<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Gangguan;

class HomeComponent extends Component
{
    public $ticketCode = '';
    public $ticketResult = null;
    public $ticketLookupAttempted = false;

    public function searchTicket()
    {
        $this->validate([
            'ticketCode' => 'required|string|min:6',
        ]);

        $this->ticketLookupAttempted = true;
        $normalizedCode = strtoupper(trim($this->ticketCode));
        $this->ticketCode = $normalizedCode;

        $this->ticketResult = Gangguan::with([
            'perangkat',
            'teknisi',
            'verifier',
            'proses.user',
            'proses.teknisi',
        ])
            ->where('kode_tiket', $normalizedCode)
            ->first();
    }

    public function render()
    {
        $total = Gangguan::count();
        $open = Gangguan::where('status', Gangguan::STATUS_OPEN)->count();
        $diverifikasi = Gangguan::where('status', Gangguan::STATUS_DIVERIFIKASI)->count();
        $proses = Gangguan::where('status', Gangguan::STATUS_PROSES)->count();
        $menunggu = Gangguan::where('status', Gangguan::STATUS_MENUNGGU)->count();
        $selesai = Gangguan::where('status', Gangguan::STATUS_SELESAI)->count();
        $recentReports = Gangguan::with(['perangkat', 'teknisi'])
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.home-component', [
            'total' => $total,
            'open' => $open,
            'diverifikasi' => $diverifikasi,
            'proses' => $proses,
            'menunggu' => $menunggu,
            'selesai' => $selesai,
            'recentReports' => $recentReports,
        ]);
    }
}
