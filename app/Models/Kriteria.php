<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
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
