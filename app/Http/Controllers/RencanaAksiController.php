<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RencanaKinerja;
use App\Models\SasaranKinerja;
use App\Models\RencanaAksi;

use App\Services\Datatables\RencanaAksiDataTable;

use Validator;

class RencanaAksiController extends Controller
{

    public function List(Request $request)
    {
        $searchDatas = new RencanaAksiDataTable($request->all());
        $data = $searchDatas->search();
        return $data;
    }



}
