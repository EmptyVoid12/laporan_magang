<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class Gangguan extends Model
{
    use HasFactory;

    public const STATUS_OPEN = 'Open';
    public const STATUS_DIVERIFIKASI = 'Diverifikasi';
    public const STATUS_PROSES = 'Proses';
    public const STATUS_MENUNGGU = 'Menunggu';
    public const STATUS_SELESAI = 'Selesai';
    public const STATUS_DITOLAK = 'Ditolak';

    public const STATUS_OPTIONS = [
        self::STATUS_OPEN => self::STATUS_OPEN,
        self::STATUS_DIVERIFIKASI => self::STATUS_DIVERIFIKASI,
        self::STATUS_PROSES => self::STATUS_PROSES,
        self::STATUS_MENUNGGU => self::STATUS_MENUNGGU,
        self::STATUS_SELESAI => self::STATUS_SELESAI,
        self::STATUS_DITOLAK => self::STATUS_DITOLAK,
    ];

    public const PRIORITAS_OPTIONS = [
        'Rendah' => 'Rendah',
        'Sedang' => 'Sedang',
        'Tinggi' => 'Tinggi',
    ];

    protected $fillable = [
        'kode_tiket',
        'perangkat_id',
        'deskripsi',
        'tanggal',
        'status',
        'prioritas',
        'operator_id',
        'teknisi_id',
        'foto',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    protected static function booted(): void
    {
        static::created(function (Gangguan $gangguan): void {
            if (! $gangguan->kode_tiket) {
                $gangguan->updateQuietly([
                    'kode_tiket' => static::generateKodeTiket($gangguan->id, $gangguan->tanggal),
                ]);
            }

            if (! app()->runningInConsole()) {
                $gangguan->logTimeline(
                    'Laporan dibuat dan menunggu verifikasi admin.',
                    Auth::id(),
                    null,
                    LaporanProses::TYPE_REPORT
                );
            }
        });

        static::updated(function (Gangguan $gangguan): void {
            if ($gangguan->wasChanged('teknisi_id')) {
                $teknisiBaru = $gangguan->teknisi?->name;
                $pesan = $teknisiBaru
                    ? "Teknisi {$teknisiBaru} ditugaskan untuk menangani laporan ini."
                    : 'Penugasan teknisi dibatalkan.';

                $gangguan->logTimeline(
                    $pesan,
                    Auth::id(),
                    $gangguan->teknisi_id,
                    LaporanProses::TYPE_ASSIGNMENT
                );
            }

            if ($gangguan->wasChanged('status')) {
                $gangguan->logTimeline(
                    "Status laporan diubah dari {$gangguan->getOriginal('status')} menjadi {$gangguan->status}.",
                    Auth::id(),
                    $gangguan->teknisi_id,
                    LaporanProses::TYPE_STATUS
                );
            }
        });
    }

    public static function generateKodeTiket(int $id, $tanggal): string
    {
        $date = $tanggal ? Carbon::parse($tanggal) : now();

        return sprintf('NOC-%s-%04d', $date->format('Ymd'), $id);
    }

    public function logTimeline(
        string $keterangan,
        ?int $userId = null,
        ?int $teknisiId = null,
        string $tipe = LaporanProses::TYPE_PROGRESS,
        ?string $kendala = null
    ): void {
        $this->proses()->create([
            'user_id' => $userId,
            'teknisi_id' => $teknisiId,
            'tipe_update' => $tipe,
            'keterangan_proses' => $keterangan,
            'kendala' => $kendala,
            'tanggal_update' => now()->toDateString(),
        ]);
    }

    public static function statusColor(string $status): string
    {
        return match ($status) {
            self::STATUS_SELESAI => 'green',
            self::STATUS_PROSES => 'yellow',
            self::STATUS_DIVERIFIKASI => 'blue',
            self::STATUS_MENUNGGU => 'orange',
            self::STATUS_DITOLAK => 'gray',
            default => 'red',
        };
    }

    public function perangkat()
    {
        return $this->belongsTo(Perangkat::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }

    public function proses()
    {
        return $this->hasMany(LaporanProses::class);
    }
}
