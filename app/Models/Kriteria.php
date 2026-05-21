<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Kriteria extends Model
{
    protected static function booted()
    {
        static::saved(fn() => Cache::flush());
        static::deleted(fn() => Cache::flush());
    }
    protected $fillable = [
        'kode',
        'nama',
        'tipe',
        'bobot',
        'deskripsi',
        'urutan',
    ];

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }

    public function ahpBaris()
    {
        return $this->hasMany(AhpComparison::class, 'kriteria_baris_id');
    }

    public function ahpKolom()
    {
        return $this->hasMany(AhpComparison::class, 'kriteria_kolom_id');
    }

    public function scopeKode($query, $kode)
    {
        return $query->where('kode', $kode);
    }
}
