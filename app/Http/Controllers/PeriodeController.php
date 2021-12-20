<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periode;

use Illuminate\Pagination\Paginator;

use Validator;

class PeriodeController extends Controller
{

    public function PeriodeDetail(Request $request)
    {

        $query              = Periode::WHERE('id',$request->id)->first();

        return  $query;

    }

}
