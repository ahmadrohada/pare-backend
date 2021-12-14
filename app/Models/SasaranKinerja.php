<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SasaranKinerja extends Model
{
    use SoftDeletes;
    protected $table = "sasaran_kinerja";
    use HasFactory;
    protected $dates = ['deleted_at'];

/*     public function Indikator()
    {
    return $this->hasMany(IndikatorSasaranStrategis::class);
    }

    public function Periode()
    {
        return $this->belongsTo(Periode::class);
    }
 */



}
