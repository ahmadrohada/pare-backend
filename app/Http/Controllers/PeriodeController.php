<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periode;

use App\Services\Datatables\PeriodeDataTable;

use Validator;

class PeriodeController extends Controller
{

    public function PeriodeList(Request $request)
    {
        $searchDatas = new PeriodeDataTable($request->all());
        $data = $searchDatas->search();
        return $data;
    }


    public function PeriodeDetail(Request $request)
    {

        $query              = Periode::WHERE('id',$request->id)->first();

        return  $query;

    }



}
