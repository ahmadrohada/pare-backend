<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimKerja extends Model
{
    protected $table = "perjanjian_kinerja_tim_kerja";
    protected $hidden = array('created_at', 'updated_at','deleted_at');
    use HasFactory;

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

    //RENCANA KINERJA
    public function rencanaKinerja()
    {
    return $this->hasMany(RencanaKinerja::class, 'tim_kerja_id');
    }

    public function pernjanjianKinerja()
    {
        return $this->belongsTo('App\Models\PerjanjianKinerja');
    }


}
