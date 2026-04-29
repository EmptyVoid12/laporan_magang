<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanProses extends Model
{
    use HasFactory;

    public const TYPE_REPORT = 'report';
    public const TYPE_ASSIGNMENT = 'assignment';
    public const TYPE_STATUS = 'status';
    public const TYPE_PROGRESS = 'progress';
    public const TYPE_COMPLETION = 'completion';
    public const TYPE_VERIFICATION = 'verification';

    protected $table = 'laporan_proses';

    protected $fillable = [
        'gangguan_id',
        'teknisi_id',
        'user_id',
        'tipe_update',
        'keterangan_proses',
        'kendala',
        'attachment_path',
        'attachment_name',
        'attachment_mime',
        'tanggal_update',
    ];

    protected $casts = [
        'tanggal_update' => 'date',
    ];

    public function gangguan()
    {
        return $this->belongsTo(Gangguan::class);
    }

    public function teknisi()
    {
        return $this->belongsTo(User::class, 'teknisi_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getActorNameAttribute(): string
    {
        return $this->user?->name ?? $this->teknisi?->name ?? 'Sistem';
    }

    public function getAttachmentUrlAttribute(): ?string
    {
        return $this->attachment_path ? asset('storage/' . $this->attachment_path) : null;
    }

    public function getHasAttachmentAttribute(): bool
    {
        return filled($this->attachment_path);
    }
}
