<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorRencanaKinerja extends Model
{
    protected $table = "skp_rencana_kinerja_indikator";
    use HasFactory;


    public function RencanaKinerja()
    {
        return $this->belongsTo(RencanaKinerja::class,'id');
    }


}
