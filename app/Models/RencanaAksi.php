<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RencanaAksi extends Model
{
    use SoftDeletes;
    protected $table = "sasaran_kinerja_rencana_aksi";
    use HasFactory;
    protected $dates = ['deleted_at'];


   /*  public function IndikatorKinerjaIndividu()
    {
    return $this->hasMany(IndikatorKinerjaIndividu::class,'rencana_kinerja_id');
    } */

 

    public function RencanaKinerja()
    {
        return $this->belongsTo(RencanaKinerja::class,'rencana_kinerja_id');
    }


}
