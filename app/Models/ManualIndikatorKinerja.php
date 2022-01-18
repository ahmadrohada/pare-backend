<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualIndikatorKinerja extends Model
{
    protected $table = "sasaran_kinerja_manual_indikator";
    use HasFactory;


    public function IndikatorKinerjaIndividu()
    {
        return $this->belongsTo(IndikatorKinerjaIndividu::class);
    }

    public function RencanaKinerja()
    {
        return $this->belongsTo(RencanaKinerja::class);
    }

    public function SasaranKinerja()
    {
        return $this->belongsTo(SasaranKinerja::class);
    }


}
