<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorKinerjaIndividu extends Model
{
    protected $table = "sasaran_kinerja_indikator_kinerja_individu";
    use HasFactory;


    public function RencanaKinerja()
    {
        return $this->belongsTo(RencanaKinerja::class);
    }


}
