<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SasaranStrategis extends Model
{
    protected $table = "renja_sasaran_strategis";
    use HasFactory;

    public function Indikator()
    {
    return $this->hasMany(IndikatorSasaranStrategis::class);
    }

    public function indikator_sasaran()
    {
        return $this->hasMany('App\Models\IndikatorSasaran');
    }



}
