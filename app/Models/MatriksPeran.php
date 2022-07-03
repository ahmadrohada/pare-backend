<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MatriksPeran extends Model
{
    use SoftDeletes;
    protected $table = "matriks_peran";
    use HasFactory;
    protected $dates = ['deleted_at'];


    public function SasaranKinerja()
    {
        return $this->hasMany(SasaranKinerja::class,'matriks_peran_id');
    }

    public function MatriksHasil()
    {
        return $this->hasMany(MatriksHasil::class,'matriks_peran_id');
    }



}
