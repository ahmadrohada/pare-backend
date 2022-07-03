<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerilakuKerja extends Model
{
    protected $table = "master_perilaku_kerja";


    /* public function child()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
    public function children()
    {
        return $this->child()->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(self::class,'parent_id');
    }

    // all ascendants
    public function parent_rec()
    {
        return $this->parent()->with('parent_rec');
    } */


}
