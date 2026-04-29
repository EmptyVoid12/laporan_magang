<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Perangkat;
use Livewire\WithPagination;

class PerangkatComponent extends Component
{
    use WithPagination;

    public $nama_perangkat, $jenis, $wilayah, $lokasi, $deskripsi, $perangkat_id;
    public $isOpen = false;

    protected $rules = [
        'nama_perangkat' => 'required',
        'jenis' => 'required',
        'wilayah' => 'required',
        'lokasi' => 'required',
    ];

    public function render()
    {
        return view('livewire.perangkat-component', [
            'perangkats' => Perangkat::paginate(10),
            'jenisOptions' => Perangkat::JENIS_OPTIONS,
            'wilayahOptions' => Perangkat::WILAYAH_OPTIONS,
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields(){
        $this->nama_perangkat = '';
        $this->jenis = '';
        $this->wilayah = '';
        $this->lokasi = '';
        $this->deskripsi = '';
        $this->perangkat_id = '';
    }

    public function store()
    {
        $this->validate();

        Perangkat::updateOrCreate(['id' => $this->perangkat_id], [
            'nama_perangkat' => $this->nama_perangkat,
            'jenis' => $this->jenis,
            'wilayah' => $this->wilayah,
            'lokasi' => $this->lokasi,
            'deskripsi' => $this->deskripsi
        ]);

        session()->flash('message', 
            $this->perangkat_id ? 'Perangkat Berhasil Diupdate.' : 'Perangkat Berhasil Ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $perangkat = Perangkat::findOrFail($id);
        $this->perangkat_id = $id;
        $this->nama_perangkat = $perangkat->nama_perangkat;
        $this->jenis = $perangkat->jenis;
        $this->wilayah = $perangkat->wilayah;
        $this->lokasi = $perangkat->lokasi;
        $this->deskripsi = $perangkat->deskripsi;
    
        $this->openModal();
    }

    public function delete($id)
    {
        Perangkat::find($id)->delete();
        session()->flash('message', 'Perangkat Dihapus.');
    }
}
