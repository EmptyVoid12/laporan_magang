<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Perangkat extends Model
{
    use HasFactory;

    public const JENIS_OPTIONS = [
        'CCTV' => 'CCTV',
        'Traffic Light' => 'Traffic Light',
        'Lainnya' => 'Lainnya',
    ];

    public const WILAYAH_OPTIONS = [
        'Jakarta Barat' => 'Jakarta Barat',
        'Jakarta Pusat' => 'Jakarta Pusat',
        'Jakarta Utara' => 'Jakarta Utara',
        'Jakarta Selatan' => 'Jakarta Selatan',
        'Jakarta Timur' => 'Jakarta Timur',
    ];

    protected $fillable = [
        'nama_perangkat',
        'jenis',
        'wilayah',
        'lokasi',
        'latitude',
        'longitude',
        'deskripsi',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function gangguans()
    {
        return $this->hasMany(Gangguan::class);
    }
}
