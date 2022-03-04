<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatriksPeran extends Model
{
    use SoftDeletes;
    protected $table = "matriks_peran";
    use HasFactory;
    protected $dates = ['deleted_at'];


   /*  public function IndikatorKinerjaIndividu()
    {
    return $this->hasMany(IndikatorKinerjaIndividu::class,'rencana_kinerja_id');
    } */


}
