<?php

namespace App\Http\Controllers;

use App\Models\Gangguan;
use App\Models\LaporanProses;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class AdminReportExportController extends Controller
{
    public function __invoke(string $type): Response
    {
        $user = Auth::user();

        abort_unless(
            $user && (in_array($user->role, ['admin', 'operator'], true) || $user->hasRole('super_admin')),
            403
        );

        return match ($type) {
            'tickets' => $this->exportTickets(),
            'technicians' => $this->exportTechnicians(),
            'timeline' => $this->exportTimeline(),
            default => abort(404),
        };
    }

    protected function exportTickets(): Response
    {
        $rows = Gangguan::query()
            ->with(['perangkat', 'operator', 'teknisi', 'verifier'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn (Gangguan $gangguan) => [
                'Kode Tiket' => $gangguan->kode_tiket,
                'Tanggal Lapor' => optional($gangguan->tanggal)->format('Y-m-d'),
                'Perangkat' => $gangguan->perangkat?->nama_perangkat,
                'Jenis' => $gangguan->perangkat?->jenis,
                'Wilayah' => $gangguan->perangkat?->wilayah,
                'Pelapor' => $gangguan->operator?->name,
                'Teknisi' => $gangguan->teknisi?->name,
                'Prioritas' => $gangguan->prioritas,
                'Status Tampilan' => $gangguan->workflow_status_label,
                'Diajukan Verifikasi' => optional($gangguan->submitted_for_verification_at)->format('Y-m-d H:i:s'),
                'Diverifikasi Final' => optional($gangguan->verified_at)->format('Y-m-d H:i:s'),
                'Diverifikasi Oleh' => $gangguan->verifier?->name,
                'Catatan Verifikasi' => $gangguan->verification_notes,
            ]);

        return $this->csvResponse('tickets-report.csv', $rows);
    }

    protected function exportTechnicians(): Response
    {
        $rows = User::query()
            ->where('role', 'teknisi')
            ->with(['assignedGangguans', 'completedGangguans'])
            ->get()
            ->map(function (User $teknisi) {
                $verified = $teknisi->completedGangguans->filter(fn (Gangguan $gangguan) => $gangguan->verified_at);
                $averageHours = $verified->isNotEmpty()
                    ? round($verified->avg(fn (Gangguan $gangguan) => $gangguan->created_at->diffInHours($gangguan->verified_at)), 2)
                    : null;

                return [
                    'Nama Teknisi' => $teknisi->name,
                    'Email' => $teknisi->email,
                    'Tiket Aktif' => $teknisi->assignedGangguans->whereNotIn('status', [Gangguan::STATUS_SELESAI, Gangguan::STATUS_DITOLAK])->count(),
                    'Menunggu Verifikasi Akhir' => $teknisi->assignedGangguans->filter(fn (Gangguan $gangguan) => $gangguan->isAwaitingFinalVerification())->count(),
                    'Tiket Selesai Terverifikasi' => $verified->count(),
                    'Rata-rata Waktu Penyelesaian Laporan' => $averageHours,
                ];
            });

        return $this->csvResponse('technicians-report.csv', $rows);
    }

    protected function exportTimeline(): Response
    {
        $rows = LaporanProses::query()
            ->with(['gangguan', 'user', 'teknisi'])
            ->orderByDesc('id')
            ->get()
            ->map(fn (LaporanProses $item) => [
                'Kode Tiket' => $item->gangguan?->kode_tiket,
                'Tanggal Update' => optional($item->tanggal_update)->format('Y-m-d'),
                'Jenis Update' => $item->tipe_update,
                'Aktor' => $item->actor_name,
                'Teknisi' => $item->teknisi?->name,
                'Keterangan' => $item->keterangan_proses,
                'Kendala' => $item->kendala,
                'Ada Lampiran' => $item->has_attachment ? 'Ya' : 'Tidak',
            ]);

        return $this->csvResponse('timeline-report.csv', $rows);
    }

    protected function csvResponse(string $filename, Collection $rows): Response
    {
        $headers = $rows->first() ? array_keys($rows->first()) : [];
        $stream = fopen('php://temp', 'r+');

        if ($headers !== []) {
            fputcsv($stream, $headers);
        }

        foreach ($rows as $row) {
            fputcsv($stream, $row);
        }

        rewind($stream);
        $content = stream_get_contents($stream) ?: '';
        fclose($stream);

        return response($content, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
