<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Hasil extends Model
{
    protected static function booted()
    {
        static::saved(fn() => Cache::flush());
        static::deleted(fn() => Cache::flush());
    }
    protected $fillable = [
        'warga_id',
        'periode',
        'skor_akhir',
        'ranking',
        'status',
    ];

    public function warga()
    {
        return $this->belongsTo(Warga::class);
    }
}
