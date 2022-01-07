<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SasaranStrategis extends Model
{
    protected $table = "perjanjian_kinerja_sasaran_strategis";
    use HasFactory;

    public function Indikator()
    {
    return $this->hasMany(IndikatorSasaranStrategis::class);
    }

    public function Periode()
    {
        return $this->belongsTo(Periode::class);
    }




}
