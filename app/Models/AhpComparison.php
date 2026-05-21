<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AhpComparison extends Model
{
    protected $guarded = ['id'];

    public function kriteriaBaris()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_baris_id');
    }

    public function kriteriaKolom()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_kolom_id');
    }
}
