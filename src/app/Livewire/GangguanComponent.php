<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Gangguan;
use App\Models\Perangkat;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class GangguanComponent extends Component
{
    use WithFileUploads;

    public $perangkat_id, $jenis, $wilayah, $deskripsi, $tanggal, $prioritas, $foto;

    protected $rules = [
        'perangkat_id' => 'required|exists:perangkats,id',
        'deskripsi' => 'required',
        'tanggal' => 'required|date',
        'prioritas' => 'required',
        'foto' => 'nullable|image|max:2048',
    ];

    public function mount()
    {
        $this->tanggal = date('Y-m-d');
        $this->prioritas = 'Sedang';
        $this->jenis = '';
        $this->wilayah = '';
    }

    public function updatedJenis()
    {
        $this->perangkat_id = '';
    }

    public function updatedWilayah()
    {
        $this->perangkat_id = '';
    }

    public function store()
    {
        $this->validate();

        $fotoPath = null;
        if($this->foto) {
            $fotoPath = $this->foto->store('gangguan_fotos', 'public');
        }

        $gangguan = Gangguan::create([
            'perangkat_id' => $this->perangkat_id,
            'deskripsi' => $this->deskripsi,
            'tanggal' => $this->tanggal,
            'prioritas' => $this->prioritas,
            'operator_id' => Auth::id(),
            'status' => Gangguan::STATUS_OPEN,
            'foto' => $fotoPath
        ]);

        session()->flash('message', 'Laporan gangguan berhasil dikirim dengan tiket ' . $gangguan->fresh()->kode_tiket . '.');
        $this->reset(['perangkat_id', 'deskripsi', 'foto']);
        $this->tanggal = date('Y-m-d');
        $this->prioritas = 'Sedang';
    }

    public function deleteLaporan($id)
    {
        $laporan = Gangguan::where('id', $id)->where('operator_id', Auth::id())->first();
        if ($laporan) {
            $laporan->delete();
            session()->flash('message', 'Laporan berhasil dihapus.');
        }
    }

    public function render()
    {
        $perangkats = Perangkat::query()
            ->when($this->jenis, fn ($query) => $query->where('jenis', $this->jenis))
            ->when($this->wilayah, fn ($query) => $query->where('wilayah', $this->wilayah))
            ->orderBy('nama_perangkat')
            ->get();

        $riwayatLaporan = Gangguan::where('operator_id', Auth::id())
            ->with('perangkat')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.gangguan-component', [
            'perangkats' => $perangkats,
            'riwayatLaporan' => $riwayatLaporan,
            'jenisOptions' => Perangkat::JENIS_OPTIONS,
            'wilayahOptions' => Perangkat::WILAYAH_OPTIONS,
        ]);
    }
}
