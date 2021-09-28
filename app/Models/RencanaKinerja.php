<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RencanaKinerja extends Model
{
    protected $table = "renja_rencana_kinerja";
    protected $hidden = array('created_at', 'updated_at','deleted_at');
    use HasFactory;
}
