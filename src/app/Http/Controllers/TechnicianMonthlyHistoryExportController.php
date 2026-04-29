<?php

namespace App\Http\Controllers;

use App\Models\LaporanProses;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TechnicianMonthlyHistoryExportController extends Controller
{
    public function __invoke(Request $request): StreamedResponse
    {
        // Prioritize the admin panel session so Filament users can export
        // without being shadowed by an unrelated web session.
        $actor = Auth::guard('admin')->user() ?? Auth::guard('web')->user();

        abort_unless($actor, 403);

        $validated = validator($request->query(), [
            'month' => ['required', 'date_format:Y-m'],
            'teknisi_id' => ['nullable', 'integer', 'exists:users,id'],
        ])->validate();

        $requestedTeknisiId = $validated['teknisi_id'] ?? null;
        $selectedTeknisiId = $requestedTeknisiId;

        if ($actor->role === 'teknisi') {
            if ($requestedTeknisiId !== null && (int) $requestedTeknisiId !== (int) $actor->id) {
                abort(403);
            }

            $selectedTeknisiId = $actor->id;
        }

        if (! $selectedTeknisiId) {
            throw ValidationException::withMessages([
                'teknisi_id' => 'Teknisi harus dipilih untuk export riwayat kerja bulanan.',
            ]);
        }

        if (
            $actor->role !== 'teknisi'
            && ! in_array($actor->role, ['admin', 'operator'], true)
            && ! $actor->hasRole('super_admin')
        ) {
            abort(403);
        }

        $teknisi = User::query()
            ->where('role', 'teknisi')
            ->findOrFail($selectedTeknisiId);

        $month = Carbon::createFromFormat('Y-m', $validated['month'])->startOfMonth();
        $monthEnd = $month->copy()->endOfMonth();

        $riwayat = LaporanProses::query()
            ->where('teknisi_id', $teknisi->id)
            ->whereBetween('tanggal_update', [$month->toDateString(), $monthEnd->toDateString()])
            ->with(['gangguan.perangkat', 'gangguan.operator', 'user', 'teknisi'])
            ->orderBy('tanggal_update')
            ->orderBy('id')
            ->get();

        $ringkasanTiket = $riwayat
            ->groupBy('gangguan_id')
            ->map(function ($items) {
                $sortedItems = $items->sortBy([
                    ['tanggal_update', 'asc'],
                    ['id', 'asc'],
                ])->values();
                $terakhir = $sortedItems->last();
                $gangguan = $terakhir?->gangguan;

                return [
                    'kode_tiket' => $gangguan?->kode_tiket,
                    'tanggal_lapor' => optional($gangguan?->tanggal)->format('Y-m-d'),
                    'tanggal_update_terakhir' => optional($terakhir?->tanggal_update)->format('Y-m-d'),
                    'perangkat' => $gangguan?->perangkat?->nama_perangkat,
                    'lokasi' => $gangguan?->perangkat?->lokasi,
                    'pelapor' => $gangguan?->operator?->name,
                    'prioritas' => $gangguan?->prioritas,
                    'status_laporan' => $gangguan?->workflow_status_label ?? $gangguan?->status,
                    'jumlah_update' => $sortedItems->count(),
                    'aktivitas_terakhir' => $terakhir?->keterangan_proses,
                    'kendala_terakhir' => $terakhir?->kendala,
                    'lampiran_terakhir' => $terakhir?->attachment_url,
                ];
            })
            ->sortByDesc('tanggal_update_terakhir')
            ->values();

        $filename = sprintf(
            'riwayat-kerja-%s-%s.csv',
            Str::slug($teknisi->name),
            $month->format('Y-m')
        );

        return response()->streamDownload(function () use ($teknisi, $month, $riwayat, $ringkasanTiket): void {
            $stream = fopen('php://output', 'w');

            if ($stream === false) {
                return;
            }

            $writeCsvRow = static function ($handle, array $row): void {
                fputcsv($handle, $row, ';');
            };

            fwrite($stream, "\xEF\xBB\xBF");
            fwrite($stream, "sep=;\r\n");

            $writeCsvRow($stream, ['Riwayat Kerja Bulanan Teknisi']);
            $writeCsvRow($stream, ['Nama Teknisi', $teknisi->name]);
            $writeCsvRow($stream, ['Bulan', $month->translatedFormat('F Y')]);
            $writeCsvRow($stream, ['Total Riwayat Kerja', $riwayat->count()]);
            $writeCsvRow($stream, ['Total Tiket Ditangani', $ringkasanTiket->count()]);
            $writeCsvRow($stream, []);
            $writeCsvRow($stream, [
                'No',
                'Kode Tiket',
                'Tanggal Lapor',
                'Update Terakhir Bulan Ini',
                'Perangkat',
                'Lokasi',
                'Pelapor',
                'Prioritas',
                'Status Laporan',
                'Jumlah Update Bulan Ini',
                'Aktivitas Terakhir Bulan Ini',
                'Kendala Terakhir',
                'Lampiran Terakhir',
            ]);

            foreach ($ringkasanTiket as $index => $item) {
                $writeCsvRow($stream, [
                    $index + 1,
                    $item['kode_tiket'],
                    $item['tanggal_lapor'],
                    $item['tanggal_update_terakhir'],
                    $item['perangkat'],
                    $item['lokasi'],
                    $item['pelapor'],
                    $item['prioritas'],
                    $item['status_laporan'],
                    $item['jumlah_update'],
                    $item['aktivitas_terakhir'],
                    $item['kendala_terakhir'],
                    $item['lampiran_terakhir'],
                ]);
            }

            fclose($stream);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }
}
