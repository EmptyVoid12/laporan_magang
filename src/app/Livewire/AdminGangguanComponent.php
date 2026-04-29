<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Gangguan;
use App\Models\Perangkat;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class AdminGangguanComponent extends Component
{
    use WithPagination;

    public $search = '';
    public $filterJenis = '';
    public $filterWilayah = '';
    public $filterStatus = '';
    public $filterPrioritas = '';
    public $filterTanggalMulai = '';
    public $filterTanggalSelesai = '';

    public function updating($name, $value)
    {
        if (in_array($name, [
            'search',
            'filterJenis',
            'filterWilayah',
            'filterStatus',
            'filterPrioritas',
            'filterTanggalMulai',
            'filterTanggalSelesai',
        ], true)) {
            $this->resetPage();
        }
    }

    public function assignTeknisi($gangguanId, $teknisiId)
    {
        $gangguan = Gangguan::find($gangguanId);
        if($gangguan) {
            $gangguan->teknisi_id = $teknisiId ?: null;
            if($teknisiId && $gangguan->status === Gangguan::STATUS_OPEN) {
                $gangguan->status = Gangguan::STATUS_DIVERIFIKASI;
            }
            $gangguan->save();
            session()->flash('message', 'Teknisi berhasil ditugaskan.');
        }
    }

    public function updateStatus($gangguanId, $status)
    {
        $gangguan = Gangguan::find($gangguanId);
        if($gangguan) {
            $gangguan->status = $status;
            $gangguan->save();
            session()->flash('message', 'Status diperbarui.');
        }
    }

    public function render()
    {
        $gangguans = Gangguan::query()
            ->with(['perangkat', 'operator', 'teknisi', 'proses.user', 'proses.teknisi'])
            ->when($this->search, function ($query) {
                $query->where(function ($innerQuery) {
                    $innerQuery
                        ->where('kode_tiket', 'like', '%' . $this->search . '%')
                        ->orWhere('deskripsi', 'like', '%' . $this->search . '%')
                        ->orWhereHas('perangkat', function ($perangkatQuery) {
                            $perangkatQuery->where('nama_perangkat', 'like', '%' . $this->search . '%')
                                ->orWhere('lokasi', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->filterJenis, fn ($query) => $query->whereHas('perangkat', fn ($perangkatQuery) => $perangkatQuery->where('jenis', $this->filterJenis)))
            ->when($this->filterWilayah, fn ($query) => $query->whereHas('perangkat', fn ($perangkatQuery) => $perangkatQuery->where('wilayah', $this->filterWilayah)))
            ->when($this->filterStatus, fn ($query) => $query->where('status', $this->filterStatus))
            ->when($this->filterPrioritas, fn ($query) => $query->where('prioritas', $this->filterPrioritas))
            ->when($this->filterTanggalMulai, fn ($query) => $query->whereDate('tanggal', '>=', $this->filterTanggalMulai))
            ->when($this->filterTanggalSelesai, fn ($query) => $query->whereDate('tanggal', '<=', $this->filterTanggalSelesai))
            ->latest()
            ->paginate(10);

        $teknisis = User::where('role', 'teknisi')->get();

        return view('livewire.admin-gangguan-component', [
            'gangguans' => $gangguans,
            'teknisis' => $teknisis,
            'jenisOptions' => Perangkat::JENIS_OPTIONS,
            'wilayahOptions' => Perangkat::WILAYAH_OPTIONS,
            'statusOptions' => Gangguan::STATUS_OPTIONS,
            'prioritasOptions' => Gangguan::PRIORITAS_OPTIONS,
        ]);
    }
}
