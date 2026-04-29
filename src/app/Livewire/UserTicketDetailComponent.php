<?php

namespace App\Livewire;

use App\Models\Gangguan;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserTicketDetailComponent extends Component
{
    public Gangguan $gangguan;

    public function mount(Gangguan $gangguan): void
    {
        abort_unless($gangguan->operator_id === Auth::id(), 403);

        $this->gangguan = $gangguan->load([
            'perangkat',
            'teknisi',
            'verifier',
            'proses.user',
            'proses.teknisi',
        ]);
    }

    public function render()
    {
        return view('livewire.user-ticket-detail-component');
    }
}
