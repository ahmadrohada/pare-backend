<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorSasaranStrategis extends Model
{
    protected $table = "renja_indikator_sasaran_strategis";
    use HasFactory;


    public function SasaranStrategis()
    {
        return $this->belongsTo(SasaranStrategis::class,'id');
    }


}
