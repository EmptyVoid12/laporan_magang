<?php

namespace App\Filament\Admin\Pages;

use App\Models\Gangguan;
use App\Models\User;
use Filament\Pages\Page;

class ReportsOverview extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationGroup = 'Laporan Layanan';

    protected static ?string $navigationLabel = 'Report';

    protected static string $view = 'filament.admin.pages.reports-overview';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user && (in_array($user->role, ['admin', 'operator'], true) || $user->hasRole('super_admin'));
    }

    protected function getViewData(): array
    {
        $tickets = Gangguan::query()
            ->with(['teknisi', 'perangkat'])
            ->latest()
            ->get();

        $technicianRows = User::query()
            ->where('role', 'teknisi')
            ->with('assignedGangguans')
            ->get()
            ->map(function (User $teknisi) {
                $assigned = $teknisi->assignedGangguans;
                $verified = $assigned->filter(fn (Gangguan $gangguan) => $gangguan->verified_at);

                return [
                    'name' => $teknisi->name,
                    'assigned' => $assigned->count(),
                    'active' => $assigned->whereNotIn('status', [Gangguan::STATUS_SELESAI, Gangguan::STATUS_DITOLAK])->count(),
                    'pending_verification' => $assigned->filter(fn (Gangguan $gangguan) => $gangguan->isAwaitingFinalVerification())->count(),
                    'verified_done' => $verified->count(),
                    'avg_resolution_hours' => $verified->isNotEmpty()
                        ? round($verified->avg(fn (Gangguan $gangguan) => $gangguan->created_at->diffInHours($gangguan->verified_at)), 2)
                        : null,
                ];
            });

        $verifiedTickets = $tickets->filter(fn (Gangguan $gangguan) => $gangguan->verified_at);
        $avgResolution = $verifiedTickets->isNotEmpty()
            ? round($verifiedTickets->avg(fn (Gangguan $gangguan) => $gangguan->created_at->diffInHours($gangguan->verified_at)), 2)
            : null;

        return [
            'stats' => [
                'total' => $tickets->count(),
                'pending_final_verification' => $tickets->filter(fn (Gangguan $gangguan) => $gangguan->isAwaitingFinalVerification())->count(),
                'verified_completed' => $tickets->filter(fn (Gangguan $gangguan) => $gangguan->isFinallyVerified())->count(),
                'avg_resolution_hours' => $avgResolution,
            ],
            'technicianRows' => $technicianRows,
            'recentVerifiedTickets' => $verifiedTickets->take(10),
        ];
    }
}
