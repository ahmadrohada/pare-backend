<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RenjaPejabat extends Model
{
    protected $table = "renja_pejabat";
    protected $hidden = array('created_at', 'updated_at','deleted_at');
    use HasFactory;




    public function timKerja()
    {
        return $this->belongsTo('App\Models\TimKerja');
    }

    public function rencanaSkp()
    {
        return $this->hasone('App\Models\RencanaSKP');
    }

}
