<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Warga extends Model
{
    use SoftDeletes;

    protected static function booted()
    {
        static::saved(fn() => Cache::flush());
        static::deleted(fn() => Cache::flush());
    }

    protected $fillable = [
        'nama',
        'alamat',
        'rt',
        'rw',
        'kelurahan',
        'status_aktif',
    ];

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }

    public function hasils()
    {
        return $this->hasMany(Hasil::class);
    }

    public function hasilPeriode(string $periode)
    {
        return $this->hasils()->where('periode', $periode)->first();
    }
}
