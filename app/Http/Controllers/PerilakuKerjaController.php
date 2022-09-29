<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SasaranKinerja;
use App\Models\RencanaKinerja;
use App\Models\PerilakuKerjaCoreValues;
use App\Models\PerilakuKerjaPanduan;
use App\Models\PerilakuKerjaPerwujudan;
use App\Models\SasaranKinerjaPerilakuKerja;



use Validator;

class PerilakuKerjaController extends Controller
{


    public function ListPerwujudanPerilaku(Request $request)
    {

        $perwujudan_perilaku =  PerilakuKerjaPerwujudan::WHERE('core_value_id','=',$request->core_value_id)->get();

        $response['list_perwujudan_perilaku'] = array();
        $no = 1;

        foreach ($perwujudan_perilaku as $data) {
            $dt['no']                   = $no;
            $dt['label']                = $data['label'];
            array_push($response['list_perwujudan_perilaku'], $dt);
        }

        return [
            'list_perwujudan_perilaku'  => $response['list_perwujudan_perilaku']
        ];


    }



    public function List(Request $request)
    {


        $perilaku_kerja = PerilakuKerjaCoreValues::
                SELECT(
                        'id',
                        'label'
                        )
                ->get();


        $response['perilaku_kerja'] = array();
        $no = 1;

        foreach ($perilaku_kerja as $data) {

            $perilaku_kerja_id = $data['id'];

            $dt['no']                   = $no;
            $dt['label']                = $data['label'];
            $dt['point_penilaian']      = null;
            $dt['ekspektasi_pimpinan']  = null;
            $dt['id']                   = $data['id'];
            $dt['skp_id']               = $request->sasaran_kinerja_id;

            //point penilaian objet
            $point = PerilakuKerjaPanduan::SELECT('label')->WHERE('core_value_id','=', $perilaku_kerja_id)->get();

            $dt2['label']                    = null;
            $dt2['point_penilaian']          = $point;
            $dt2['id']                       = $data['id'];
            $dt2['skp_id']                   = $request->sasaran_kinerja_id;

            //Perilaku kerja
            $ekspektasi_pimpinan = SasaranKinerjaPerilakuKerja::SELECT('id','label')
                                                ->WHERE('sasaran_kinerja_id','=', $request->sasaran_kinerja_id)
                                                ->WHERE('core_value_id','=', $perilaku_kerja_id)
                                                ->get();


            $dt2['ekspektasi_pimpinan']      = $ekspektasi_pimpinan;
            //$dt2['ekspektasi_pimpinan']      = "Ekspektasi Pimpinan :";


            array_push($response['perilaku_kerja'], $dt);
            array_push($response['perilaku_kerja'], $dt2);

            $no++;
        }

        return [
            'data'       =>$response['perilaku_kerja']
        ];
    }


    public function Store(Request $request)
    {
        $messages = [
            'perilakuKerjaLabel.required'    => 'Harus diisi',
            'sasaranKinerjaId.required'      => 'Harus diisi',
            'coreValueId.required'           => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'perilakuKerjaLabel'   => 'required',
                'sasaranKinerjaId'     => 'required',
                'coreValueId'          => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        //jika array
        $arrayPerilakuKerja = $request->perilakuKerjaLabel;
        $no = 0 ;
        if($request->option == 'pilihan') {
            foreach ($arrayPerilakuKerja as $x) {
                $ah    = new SasaranKinerjaPerilakuKerja;
                $ah->sasaran_kinerja_id            = $request->sasaranKinerjaId;
                $ah->core_value_id                 = $request->coreValueId;
                $ah->label                         = $x['label'];

                if ($x != null) {
                    $ah->save();
                    $no++;
                }
            }

        }else {
            $ah    = new SasaranKinerjaPerilakuKerja;
            $ah->sasaran_kinerja_id            = $request->sasaranKinerjaId;
            $ah->core_value_id                 = $request->coreValueId;
            $ah->label                         = $request->perilakuKerjaLabel;
            $ah->save();
            $no++;


        }
        if ($no > 0) {
            return \Response::make("Sukses", 200);
        } else {
            return \Response::make('error', 500);
        }


    }


    public function Update(Request $request)
    {
        $messages = [
            'perilakuKerjaId.required'       => 'Harus diisi',
            'perilakuKerjaLabel.required'    => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'perilakuKerjaId'         => 'required',
                'perilakuKerjaLabel'      => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $update  = SasaranKinerjaPerilakuKerja::find($request->perilakuKerjaId);

        $update->label                           = $request->perilakuKerjaLabel;

        if ($update->save()) {
            return \Response::make("Sukses", 200);
        } else {
            return \Response::make('error', 500);
        }
    }

    public function Destroy(Request $request)
    {
        $messages = [
            'id.required'   => 'Harus diisi',
        ];
        $validator = Validator::make(
                        $request->all(),
                        array(
                            'id'   => 'required',
                        ),
                        $messages
        );
        if ( $validator->fails() ){
            //$messages = $validator->messages();
            return response()->json(['errors'=>$validator->messages()],422);

        }


        $sr    = SasaranKinerjaPerilakuKerja::find($request->id);
        if (is_null($sr)) {
            return \Response::make(['message' => "ID tidak ditemukan"], 500);
        }


        if ( $sr->delete()){
            return \Response::make('sukses', 200);
        }else{
            return \Response::make('error', 500);
        }
    }



}
