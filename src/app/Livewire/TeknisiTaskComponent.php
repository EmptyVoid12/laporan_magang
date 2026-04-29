<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Gangguan;
use App\Models\LaporanProses;
use Illuminate\Support\Facades\Auth;

class TeknisiTaskComponent extends Component
{
    public $gangguan_id, $keterangan_proses, $kendala;
    public $isOpen = false;

    public function openModal($id)
    {
        $this->gangguan_id = $id;
        $this->keterangan_proses = '';
        $this->kendala = '';
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function storeProses()
    {
        $this->validate([
            'keterangan_proses' => 'required',
            'kendala' => 'nullable'
        ]);

        LaporanProses::create([
            'gangguan_id' => $this->gangguan_id,
            'user_id' => Auth::id(),
            'teknisi_id' => Auth::id(),
            'tipe_update' => LaporanProses::TYPE_PROGRESS,
            'keterangan_proses' => $this->keterangan_proses,
            'kendala' => $this->kendala,
            'tanggal_update' => date('Y-m-d')
        ]);

        $gangguan = Gangguan::find($this->gangguan_id);
        if ($gangguan && $gangguan->status !== Gangguan::STATUS_SELESAI) {
            $gangguan->status = $this->kendala ? Gangguan::STATUS_MENUNGGU : Gangguan::STATUS_PROSES;
            $gangguan->save();
        }

        session()->flash('message', 'Progress berhasil ditambahkan.');
        $this->closeModal();
    }

    public function updateTaskStatus($id, $status)
    {
        $gangguan = Gangguan::find($id);
        if($gangguan) {
            $gangguan->status = $status;
            $gangguan->save();

            if ($status === Gangguan::STATUS_SELESAI) {
                $gangguan->logTimeline(
                    'Perbaikan selesai dan perangkat dinyatakan normal.',
                    Auth::id(),
                    Auth::id(),
                    LaporanProses::TYPE_COMPLETION
                );
            }

            session()->flash('message', 'Status tugas berhasil diperbarui.');
        }
    }

    public function render()
    {
        $tasks = Gangguan::where('teknisi_id', Auth::id())
            ->with(['perangkat', 'proses.user', 'proses.teknisi', 'operator'])
            ->latest()
            ->get();

        return view('livewire.teknisi-task-component', [
            'tasks' => $tasks
        ]);
    }
}
