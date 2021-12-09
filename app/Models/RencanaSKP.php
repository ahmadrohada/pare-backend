<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaSKP extends Model
{
    protected $table = "skp";
    protected $hidden = array('created_at', 'updated_at','deleted_at');
    use HasFactory;


    public function TimKerja()
    {
    return $this->belongsTo(TimKerja::class);
    }


}
