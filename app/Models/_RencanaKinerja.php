<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaKinerja extends Model
{
    protected $table = "skp_rencana_kinerja";
    protected $hidden = array('created_at', 'updated_at','deleted_at');
    use HasFactory;

    public function Indikator()
    {
    return $this->hasMany(IndikatorRencanaKinerja::class);
    }



    public function child()
    {
    return $this->hasMany(self::class, 'parent_id');
    }
    public function children()
    {
    return $this->child()->with('children');
    }
    // parent
    public function parent()
    {
    return $this->belongsTo(self::class,'parent_id');
    }

    // all ascendants
    public function parent_rec()
    {
    return $this->parent()->with('parent_rec');
    }

    public function TimKerja()
    {
    return $this->belongsTo(TimKerja::class);
    }


}
