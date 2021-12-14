<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\Datatables\SasaranKinerjaDataTable;


class SasaranKinerjaController extends Controller
{

    public function SasaranKinerjaList(Request $request)
    {
        $searchDatas = new SasaranKinerjaDataTable($request->all());
        $data = $searchDatas->search();
        return $data;
    }


    public function SasaranKinerjaStore(Request $request)
    {

        return "tes insert";
    }





}
