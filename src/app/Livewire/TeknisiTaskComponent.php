<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Gangguan;
use App\Models\LaporanProses;
use App\Support\GangguanNotifier;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class TeknisiTaskComponent extends Component
{
    use WithFileUploads;

    public $gangguan_id, $keterangan_proses, $kendala, $lampiran;
    public $isOpen = false;
    public string $modalMode = 'progress';
    public string $exportMonth;

    public function mount(): void
    {
        $this->exportMonth = now()->format('Y-m');
    }

    public function openProgressModal($id)
    {
        $this->gangguan_id = $id;
        $this->keterangan_proses = '';
        $this->kendala = '';
        $this->lampiran = null;
        $this->modalMode = 'progress';
        $this->isOpen = true;
    }

    public function openCompletionModal($id)
    {
        $this->gangguan_id = $id;
        $this->keterangan_proses = '';
        $this->kendala = '';
        $this->lampiran = null;
        $this->modalMode = 'completion';
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function storeProses()
    {
        $isCompletion = $this->modalMode === 'completion';

        $this->validate([
            'keterangan_proses' => 'required|string',
            'kendala' => 'nullable|string',
            'lampiran' => [
                $isCompletion ? 'required' : 'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:4096',
            ],
        ]);

        $lampiranPath = $this->lampiran?->store('laporan_proses_attachments', 'public');

        LaporanProses::create([
            'gangguan_id' => $this->gangguan_id,
            'user_id' => Auth::id(),
            'teknisi_id' => Auth::id(),
            'tipe_update' => $isCompletion ? LaporanProses::TYPE_COMPLETION : LaporanProses::TYPE_PROGRESS,
            'keterangan_proses' => $this->keterangan_proses,
            'kendala' => $this->kendala,
            'attachment_path' => $lampiranPath,
            'attachment_name' => $this->lampiran?->getClientOriginalName(),
            'attachment_mime' => $this->lampiran?->getMimeType(),
            'tanggal_update' => date('Y-m-d')
        ]);

        $gangguan = Gangguan::find($this->gangguan_id);
        if ($gangguan) {
            if ($isCompletion) {
                $gangguan->submitted_for_verification_at = now();
                $gangguan->verified_at = null;
                $gangguan->verified_by = null;
                $gangguan->verification_notes = null;
                $gangguan->status = Gangguan::STATUS_SELESAI;
                $gangguan->save();

                GangguanNotifier::sendToUsers(
                    [$gangguan->operator],
                    'Tiket menunggu verifikasi akhir',
                    'Teknisi telah menandai tiket ' . $gangguan->kode_tiket . ' sebagai selesai dan menunggu verifikasi akhir.',
                    $gangguan
                );
            } elseif ($gangguan->status !== Gangguan::STATUS_SELESAI) {
                $gangguan->status = $this->kendala ? Gangguan::STATUS_MENUNGGU : Gangguan::STATUS_PROSES;
                $gangguan->save();

                GangguanNotifier::sendToUsers(
                    [$gangguan->operator],
                    'Progress baru ditambahkan',
                    'Ada update progres baru untuk tiket ' . $gangguan->kode_tiket . '.',
                    $gangguan
                );
            }
        }

        session()->flash('message', $isCompletion ? 'Penyelesaian berhasil dikirim untuk verifikasi akhir.' : 'Progress berhasil ditambahkan.');
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
            ->with(['perangkat', 'proses.user', 'proses.teknisi', 'operator', 'verifier'])
            ->when(request('ticket'), fn ($query) => $query->where('kode_tiket', request('ticket')))
            ->latest()
            ->get();

        return view('livewire.teknisi-task-component', [
            'tasks' => $tasks
        ]);
    }
}
