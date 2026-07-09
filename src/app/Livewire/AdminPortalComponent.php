<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Gangguan;
use App\Models\Perangkat;
use App\Models\User;
use App\Models\LaporanProses;
use App\Support\GangguanNotifier;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Url;

class AdminPortalComponent extends Component
{
    use WithPagination;

    // Active tab state
    #[Url(as: 'tab')]
    public $activeTab = 'gangguan'; // 'gangguan', 'perangkat', 'riwayat', 'users'

    public function mount($tab = null)
    {
        $selectedTab = $tab ?? request()->query('tab');

        if ($selectedTab) {
            $this->activeTab = $selectedTab;
        }

        if (!in_array($this->activeTab, ['gangguan', 'perangkat', 'riwayat', 'users'], true)) {
            $this->activeTab = 'gangguan';
        }
    }

    // Search and Filters for Gangguan
    public $searchGangguan = '';
    public $filterJenis = '';
    public $filterWilayah = '';
    public $filterStatus = '';
    public $filterPrioritas = '';
    public $filterTanggalMulai = '';
    public $filterTanggalSelesai = '';

    // Gangguan Verification States
    public $verificationNotes = [];
    public $rejectionNotes = [];

    // CRUD Perangkat States
    public $nama_perangkat, $jenis, $wilayah, $lokasi, $deskripsi_perangkat, $perangkat_id;
    public $isPerangkatOpen = false;

    // CRUD User States
    public $name, $email, $role, $password, $password_confirmation, $user_id;
    public $isUserOpen = false;

    // Export Technician History State
    public $exportTeknisiId = '';
    public $exportMonth = '';

    // Reset pagination when search/filters change
    public function updating($name, $value)
    {
        if (in_array($name, [
            'searchGangguan',
            'filterJenis',
            'filterWilayah',
            'filterStatus',
            'filterPrioritas',
            'filterTanggalMulai',
            'filterTanggalSelesai',
        ], true)) {
            $this->resetPage('gangguanPage');
        }
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetErrorBag();
    }

    // Gangguan (Ticket) Actions
    public function assignTeknisi($gangguanId, $teknisiId)
    {
        $gangguan = Gangguan::find($gangguanId);
        if ($gangguan) {
            $gangguan->teknisi_id = $teknisiId ?: null;
            if ($teknisiId && $gangguan->status === Gangguan::STATUS_OPEN) {
                $gangguan->status = Gangguan::STATUS_DIVERIFIKASI;
            }
            $gangguan->save();
            session()->flash('message_gangguan', "Teknisi berhasil ditugaskan untuk tiket {$gangguan->kode_tiket}.");
        }
    }

    public function updateStatus($gangguanId, $status)
    {
        $gangguan = Gangguan::find($gangguanId);
        if ($gangguan) {
            $gangguan->status = $status;
            $gangguan->save();
            session()->flash('message_gangguan', "Status tiket {$gangguan->kode_tiket} berhasil diperbarui.");
        }
    }

    public function verifyTicket($ticketId)
    {
        $gangguan = Gangguan::find($ticketId);
        if (!$gangguan) return;

        $notes = $this->verificationNotes[$ticketId] ?? null;

        $gangguan->update([
            'verified_at' => now(),
            'verified_by' => Auth::guard('admin')->id(),
            'verification_notes' => $notes,
            'status' => Gangguan::STATUS_SELESAI,
        ]);

        $gangguan->logTimeline(
            'Admin memverifikasi penyelesaian akhir tiket.',
            Auth::guard('admin')->id(),
            $gangguan->teknisi_id,
            LaporanProses::TYPE_VERIFICATION
        );

        GangguanNotifier::notifyOperatorAndTeknisi(
            $gangguan->fresh(['operator', 'teknisi']),
            'Tiket selesai terverifikasi',
            'Tiket ' . $gangguan->kode_tiket . ' sudah diverifikasi final oleh admin.'
        );

        session()->flash('message_gangguan', "Tiket {$gangguan->kode_tiket} berhasil diverifikasi final.");
        unset($this->verificationNotes[$ticketId]);
    }

    public function rejectTicket($ticketId)
    {
        $gangguan = Gangguan::find($ticketId);
        if (!$gangguan) return;

        $notes = $this->rejectionNotes[$ticketId] ?? null;

        if (empty(trim($notes))) {
            $this->addError("rejection_{$ticketId}", 'Alasan penolakan wajib diisi.');
            return;
        }

        $gangguan->update([
            'status' => Gangguan::STATUS_PROSES,
            'submitted_for_verification_at' => null,
            'verified_at' => null,
            'verified_by' => null,
            'verification_notes' => $notes,
        ]);

        $gangguan->logTimeline(
            'Admin menolak penyelesaian akhir: ' . $notes,
            Auth::guard('admin')->id(),
            $gangguan->teknisi_id,
            LaporanProses::TYPE_VERIFICATION
        );

        GangguanNotifier::notifyOperatorAndTeknisi(
            $gangguan->fresh(['operator', 'teknisi']),
            'Penyelesaian akhir perlu revisi',
            'Tiket ' . $gangguan->kode_tiket . ' perlu ditindaklanjuti lagi. Catatan: ' . $notes
        );

        session()->flash('message_gangguan', "Tiket {$gangguan->kode_tiket} ditolak dan dikembalikan ke teknisi.");
        unset($this->rejectionNotes[$ticketId]);
    }

    // CRUD Perangkat Actions
    public function createPerangkat()
    {
        $this->resetPerangkatFields();
        $this->isPerangkatOpen = true;
    }

    public function closePerangkatModal()
    {
        $this->isPerangkatOpen = false;
        $this->resetPerangkatFields();
    }

    private function resetPerangkatFields()
    {
        $this->nama_perangkat = '';
        $this->jenis = '';
        $this->wilayah = '';
        $this->lokasi = '';
        $this->deskripsi_perangkat = '';
        $this->perangkat_id = null;
    }

    public function storePerangkat()
    {
        $this->validate([
            'nama_perangkat' => 'required|string|max:255',
            'jenis' => 'required',
            'wilayah' => 'required',
            'lokasi' => 'required|string|max:255',
        ]);

        Perangkat::updateOrCreate(['id' => $this->perangkat_id], [
            'nama_perangkat' => $this->nama_perangkat,
            'jenis' => $this->jenis,
            'wilayah' => $this->wilayah,
            'lokasi' => $this->lokasi,
            'deskripsi' => $this->deskripsi_perangkat,
        ]);

        session()->flash('message_perangkat', $this->perangkat_id ? 'Perangkat berhasil diperbarui.' : 'Perangkat berhasil ditambahkan.');
        $this->closePerangkatModal();
    }

    public function editPerangkat($id)
    {
        $perangkat = Perangkat::findOrFail($id);
        $this->perangkat_id = $perangkat->id;
        $this->nama_perangkat = $perangkat->nama_perangkat;
        $this->jenis = $perangkat->jenis;
        $this->wilayah = $perangkat->wilayah;
        $this->lokasi = $perangkat->lokasi;
        $this->deskripsi_perangkat = $perangkat->deskripsi;
        $this->isPerangkatOpen = true;
    }

    public function deletePerangkat($id)
    {
        Perangkat::findOrFail($id)->delete();
        session()->flash('message_perangkat', 'Perangkat berhasil dihapus.');
    }

    // CRUD User Actions
    public function createUser()
    {
        $this->resetUserFields();
        $this->isUserOpen = true;
    }

    public function closeUserModal()
    {
        $this->isUserOpen = false;
        $this->resetUserFields();
    }

    private function resetUserFields()
    {
        $this->name = '';
        $this->email = '';
        $this->role = 'user';
        $this->password = '';
        $this->password_confirmation = '';
        $this->user_id = null;
    }

    public function storeUser()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user_id,
            'role' => 'required|in:admin,operator,teknisi,user',
        ];

        if (!$this->user_id) {
            $rules['password'] = 'required|min:8|confirmed';
        } else {
            $rules['password'] = 'nullable|min:8|confirmed';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        User::updateOrCreate(['id' => $this->user_id], $data);

        session()->flash('message_user', $this->user_id ? 'Pengguna berhasil diperbarui.' : 'Pengguna berhasil ditambahkan.');
        $this->closeUserModal();
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        $this->user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = '';
        $this->password_confirmation = '';
        $this->isUserOpen = true;
    }

    public function deleteUser($id)
    {
        User::findOrFail($id)->delete();
        session()->flash('message_user', 'Pengguna berhasil dihapus.');
    }

    // Export Action
    public function exportTeknisiBulanan()
    {
        $this->validate([
            'exportTeknisiId' => 'required',
            'exportMonth' => 'required',
        ]);

        return redirect()->route('technician.monthly-history.export', [
            'teknisi_id' => $this->exportTeknisiId,
            'month' => $this->exportMonth,
        ]);
    }

    public function render()
    {
        // 1. Dashboard View Data
        $totalTickets = Gangguan::count();
        $openCount = Gangguan::where('status', Gangguan::STATUS_OPEN)->count();
        $diverifikasiCount = Gangguan::where('status', Gangguan::STATUS_DIVERIFIKASI)->count();
        $prosesCount = Gangguan::where('status', Gangguan::STATUS_PROSES)->count();
        $menungguCount = Gangguan::where('status', Gangguan::STATUS_MENUNGGU)->count();
        $selesaiCount = Gangguan::where('status', Gangguan::STATUS_SELESAI)->count();
        $ditolakCount = Gangguan::where('status', Gangguan::STATUS_DITOLAK)->count();

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

        // 2. Gangguan (Tickets) View Data
        $gangguans = Gangguan::query()
            ->with(['perangkat', 'operator', 'teknisi', 'proses.user', 'proses.teknisi'])
            ->when($this->searchGangguan, function ($query) {
                $query->where(function ($innerQuery) {
                    $innerQuery->where('kode_tiket', 'like', '%' . $this->searchGangguan . '%')
                        ->orWhere('deskripsi', 'like', '%' . $this->searchGangguan . '%')
                        ->orWhereHas('perangkat', function ($perangkatQuery) {
                            $perangkatQuery->where('nama_perangkat', 'like', '%' . $this->searchGangguan . '%')
                                ->orWhere('lokasi', 'like', '%' . $this->searchGangguan . '%');
                        });
                });
            })
            ->when($this->filterJenis, fn ($query) => $query->whereHas('perangkat', fn ($pq) => $pq->where('jenis', $this->filterJenis)))
            ->when($this->filterWilayah, fn ($query) => $query->whereHas('perangkat', fn ($pq) => $pq->where('wilayah', $this->filterWilayah)))
            ->when($this->filterStatus, fn ($query) => $query->where('status', $this->filterStatus))
            ->when($this->filterPrioritas, fn ($query) => $query->where('prioritas', $this->filterPrioritas))
            ->when($this->filterTanggalMulai, fn ($query) => $query->whereDate('tanggal', '>=', $this->filterTanggalMulai))
            ->when($this->filterTanggalSelesai, fn ($query) => $query->whereDate('tanggal', '<=', $this->filterTanggalSelesai))
            ->latest()
            ->paginate(10, ['*'], 'gangguanPage');

        $teknisis = User::where('role', 'teknisi')->orderBy('name')->get();

        // 3. Perangkat View Data
        $perangkats = Perangkat::orderBy('nama_perangkat')->paginate(10, ['*'], 'perangkatPage');

        // 4. Riwayat & Performa Teknisi View Data
        $technicianPerformance = User::where('role', 'teknisi')
            ->with(['assignedGangguans', 'completedGangguans'])
            ->get()
            ->map(function (User $teknisi) {
                $verified = $teknisi->completedGangguans->filter(fn ($g) => $g->verified_at);
                $avgHours = $verified->isNotEmpty()
                    ? round($verified->avg(fn ($g) => $g->created_at->diffInHours($g->verified_at)), 2)
                    : null;

                return [
                    'id' => $teknisi->id,
                    'name' => $teknisi->name,
                    'email' => $teknisi->email,
                    'active' => $teknisi->assignedGangguans->whereNotIn('status', [Gangguan::STATUS_SELESAI, Gangguan::STATUS_DITOLAK])->count(),
                    'pending' => $teknisi->assignedGangguans->filter(fn ($g) => $g->isAwaitingFinalVerification())->count(),
                    'completed' => $verified->count(),
                    'avg_hours' => $avgHours,
                ];
            });

        // 5. Users View Data
        $users = User::orderBy('name')->paginate(10, ['*'], 'userPage');

        return view('livewire.admin-portal-component', [
            // Dashboard Data
            'total' => $totalTickets,
            'open' => $openCount,
            'diverifikasi' => $diverifikasiCount,
            'proses' => $prosesCount,
            'menunggu' => $menungguCount,
            'selesai' => $selesaiCount,
            'ditolak' => $ditolakCount,
            'recent' => $recentGangguan,
            'laporanPerWilayah' => $laporanPerWilayah,
            'laporanPerJenis' => $laporanPerJenis,

            // Gangguan Data
            'gangguans' => $gangguans,
            'teknisis' => $teknisis,
            'jenisOptions' => Perangkat::JENIS_OPTIONS,
            'wilayahOptions' => Perangkat::WILAYAH_OPTIONS,
            'statusOptions' => Gangguan::STATUS_OPTIONS,
            'prioritasOptions' => Gangguan::PRIORITAS_OPTIONS,

            // Perangkat Data
            'perangkats' => $perangkats,

            // Riwayat & Performa Data
            'technicianPerformance' => $technicianPerformance,

            // Users Data
            'users' => $users,
        ])->layout('components.layouts.app');
    }
}
