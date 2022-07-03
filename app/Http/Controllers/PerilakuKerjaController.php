<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SasaranKinerja;
use App\Models\RencanaKinerja;
use App\Models\PerilakuKerja;
use App\Models\PointPerilakuKerja;



use Validator;

class PerilakuKerjaController extends Controller
{


    public function List(Request $request)
    {


        $perilaku_kerja = PerilakuKerja::
                SELECT(
                        'id',
                        'aspek'
                        )
                ->get();


        $response['perilaku_kerja'] = array();
        $no = 1;

        foreach ($perilaku_kerja as $data) {

            $perilaku_kerja_id = $data['id'];

            $dt['no']                   = $no;
            $dt['aspek']                = $data['aspek'];
            $dt['point_penilaian']      = null;
            $dt['ekspektasi_pimpinan']  = null;

            //point penilaian objet
            $point = PointPerilakuKerja::SELECT('label')->WHERE('perilaku_kerja_id','=', $perilaku_kerja_id)->get();

            $dt2['aspek']                   = null;
            $dt2['point_penilaian']         = $point;
            $dt2['ekspektasi_pimpinan']      = "Ekspektasi Pimpinan :";


            array_push($response['perilaku_kerja'], $dt);
            array_push($response['perilaku_kerja'], $dt2);

            $no++;
        }

        return [
            'data'       =>$response['perilaku_kerja']
        ];
    }

}
