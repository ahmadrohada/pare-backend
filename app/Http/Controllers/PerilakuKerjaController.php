<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SasaranKinerja;
use App\Models\RencanaKinerja;
use App\Models\PerilakuKerja;
use App\Models\PointPerilakuKerja;
use App\Models\SasaranKinerjaPerilakuKerja;



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
            $dt['id']                   = $data['id'];
            $dt['skp_id']               = $request->sasaran_kinerja_id;

            //point penilaian objet
            $point = PointPerilakuKerja::SELECT('label')->WHERE('perilaku_kerja_id','=', $perilaku_kerja_id)->get();

            $dt2['aspek']                    = null;
            $dt2['point_penilaian']          = $point;
            $dt2['id']                       = $data['id'];
            $dt2['skp_id']                   = $request->sasaran_kinerja_id;

            //Perilaku kerja
            $ekspektasi_pimpinan = SasaranKinerjaPerilakuKerja::SELECT('id','label')
                                                ->WHERE('sasaran_kinerja_id','=', $request->sasaran_kinerja_id)
                                                ->WHERE('perilaku_kerja_id','=', $perilaku_kerja_id)
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
        $ah    = new SasaranKinerjaPerilakuKerja;
        $ah->sasaran_kinerja_id            = $request->sasaranKinerjaId;
        $ah->perilaku_kerja_id             = $request->coreValueId;
        $ah->label                         = $request->perilakuKerjaLabel;

        if ($ah->save()) {
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
