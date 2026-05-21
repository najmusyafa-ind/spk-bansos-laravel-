<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $fillable = [
        'warga_id',
        'kriteria_id',
        'nilai_raw',
        'nilai_numerik',
        'periode',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}
