<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hasil extends Model
{
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
