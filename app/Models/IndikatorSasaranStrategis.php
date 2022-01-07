<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorSasaranStrategis extends Model
{
    protected $table = "perjanjian_kinerja_indikator_kinerja_utama";
    use HasFactory;


    public function SasaranStrategis()
    {
        return $this->belongsTo(SasaranStrategis::class);
    }


}
