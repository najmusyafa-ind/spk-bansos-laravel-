<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Penilaian extends Model
{
    protected static function booted()
    {
        static::saved(fn() => Cache::flush());
        static::deleted(fn() => Cache::flush());
    }
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
