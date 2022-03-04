<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\PerjanjianKinerja;


use Validator;

class ManajemenKinerjaController extends Controller
{



    public function List(Request $request)
    {

        $periode_tahun  = $request->periode_tahun;
        $skpd_id        = $request->skpd_id;
         $response['data'] = array();

        $data = PerjanjianKinerja::WHERE('periode->tahun','=',$periode_tahun)->WHERE('skpd_id','=',$skpd_id)->get();

        return $data;

        //Data 1 => PERJANJIAN KINERJA
        $i['content']       = "Perjanjian Kinerja";
        $i['timestamp']     = "2018-04-12 20:46";
        $i['type']          = "primary";

        array_push($response['data'], $i);


        /* //Data 2
        $i['content']       = "Matrik Peran Hasil";
        $i['timestamp']     = "2018-04-12 20:46";
        $i['type']          = "default";

        array_push($response['data'], $i); */

        return $response['data'];
    }

}
