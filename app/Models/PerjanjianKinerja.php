<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerjanjianKinerja extends Model
{
    use SoftDeletes;
    protected $table = "perjanjian_kinerja";
    use HasFactory;

    protected $dates = ['deleted_at'];

    public function Periode()
    {
        return $this->belongsTo(Periode::class);
    }


}
