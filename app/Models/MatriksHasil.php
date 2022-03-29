<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatriksHasil extends Model
{
    use SoftDeletes;
    protected $table = "matriks_hasil";
    use HasFactory;
    protected $dates = ['deleted_at'];


    public function child()
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
    }


}
