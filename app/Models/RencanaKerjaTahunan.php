<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaKerjaTahunan extends Model
{
    protected $table = "rencana_kerja_tahunan";
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


}
