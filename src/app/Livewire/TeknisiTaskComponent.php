<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Gangguan;
use App\Models\LaporanProses;
use App\Support\GangguanNotifier;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Carbon\Carbon;

class TeknisiTaskComponent extends Component
{
    use WithFileUploads, WithPagination;

    // Filters
    public $filterStatus = '';
    public $filterBulan = '';
    public $filterTahun = '';

    // Modal Update Progress
    public $isOpen = false;
    public $selectedGangguanId = null;
    public $statusUpdate = '';
    public $keterangan = '';
    public $foto_bukti;

    public function mount(): void
    {
        $this->filterBulan = date('m');
        $this->filterTahun = date('Y');
    }

    public function updating($field)
    {
        if (in_array($field, ['filterStatus', 'filterBulan', 'filterTahun'])) {
            $this->resetPage();
        }
    }

    public function openUpdateModal($id)
    {
        $this->selectedGangguanId = $id;
        $gangguan = Gangguan::find($id);
        if ($gangguan) {
            $this->statusUpdate = $gangguan->status;
        }
        $this->keterangan = '';
        $this->foto_bukti = null;
        $this->isOpen = true;
    }

    public function closeUpdateModal()
    {
        $this->isOpen = false;
        $this->selectedGangguanId = null;
        $this->reset(['statusUpdate', 'keterangan', 'foto_bukti']);
    }

    public function updateProgress()
    {
        $this->validate([
            'statusUpdate' => 'required|string',
            'keterangan' => 'required|string|min:5',
            'foto_bukti' => 'nullable|image|max:2048',
        ]);

        $gangguan = Gangguan::find($this->selectedGangguanId);
        if (!$gangguan) return;

        $lampiranPath = $this->foto_bukti ? $this->foto_bukti->store('bukti_proses', 'public') : null;

        // Catat riwayat proses
        LaporanProses::create([
            'gangguan_id' => $gangguan->id,
            'user_id' => Auth::id(),
            'actor_name' => Auth::user()->name,
            'status_berubah_menjadi' => $this->statusUpdate,
            'keterangan_proses' => $this->keterangan,
            'foto_bukti' => $lampiranPath,
            'tanggal_update' => now(),
        ]);

        // Update status gangguan utama
        $gangguan->status = $this->statusUpdate;
        
        // Logika penyelesaian
        if ($this->statusUpdate === Gangguan::STATUS_SELESAI) {
            $gangguan->submitted_for_verification_at = now();
            
            GangguanNotifier::sendToUsers(
                [$gangguan->operator],
                'Tiket Selesai Diperbaiki',
                'Teknisi telah menyelesaikan perbaikan tiket ' . $gangguan->kode_tiket . '.',
                $gangguan
            );
        }

        $gangguan->save();

        session()->flash('message', 'Update progres berhasil disimpan.');
        $this->closeUpdateModal();
    }

    public function exportTaskBulanan()
    {
        return redirect()->route('technician.monthly-history.export', [
            'month' => $this->filterTahun . '-' . $this->filterBulan,
            'type' => 'pdf'
        ]);
    }

    public function exportTaskBulananExcel()
    {
        return redirect()->route('technician.monthly-history.export', [
            'month' => $this->filterTahun . '-' . $this->filterBulan,
            'type' => 'excel'
        ]);
    }

    public function render()
    {
        $query = Gangguan::with(['perangkat', 'proses'])
            ->where('teknisi_id', Auth::id());

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterBulan && $this->filterTahun) {
            $query->whereYear('tanggal', $this->filterTahun)
                  ->whereMonth('tanggal', $this->filterBulan);
        }

        $selectedGangguan = null;
        $riwayatProses = collect();
        if ($this->selectedGangguanId) {
            $selectedGangguan = Gangguan::with('perangkat', 'operator')->find($this->selectedGangguanId);
            $riwayatProses = LaporanProses::where('gangguan_id', $this->selectedGangguanId)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('livewire.teknisi-task-component', [
            'tasks' => $query->latest('tanggal')->paginate(10),
            'statusOptions' => Gangguan::STATUS_OPTIONS,
            'selectedGangguan' => $selectedGangguan,
            'riwayatProses' => $riwayatProses,
        ]);
    }
}
