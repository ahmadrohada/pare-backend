<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RencanaKinerja;
use App\Models\SasaranKinerja;
use App\Models\RencanaAksi;
use App\Helpers\Pustaka;


use Validator;

class RencanaAksiController extends Controller
{

    public function BulanList(Request $request)
    {

        $sasaran_kinerja_id = $request->sasaran_kinerja_id;


        $bulan = RencanaAksi::
                            SELECT('bulan_pelaksanaan','sasaran_kinerja_id')
                            ->orderBy('bulan_pelaksanaan','asc')
                            ->WHERE('sasaran_kinerja_id','=',$sasaran_kinerja_id)
                            ->distinct()
                            ->get();
                            
        $response['bulan_pelaksanaan'] = array();
        $response['rencana_aksi'] = array();
        foreach ($bulan as $x) {

            //Rencana Aksi
            $rencana_aksi = RencanaAksi:: SELECT( 'id','label')
                                        ->WHERE('sasaran_kinerja_id','=',$x->sasaran_kinerja_id)
                                        //->WHERE('rencana_kinerja_id','=',$x->rencana_kinerja_id)
                                        ->WHERE('bulan_pelaksanaan','=',$x->bulan_pelaksanaan)
                                        ->get();

            foreach ($rencana_aksi as $data) {
                $of['id']            = $data['id'];
                $of['label']         = $data['label'];
                array_push($response['rencana_aksi'], $of);
            } 


            $i['bulan']             = Pustaka::bulan($x->bulan_pelaksanaan);
            $i['rencana_aksi']      = $response['rencana_aksi'];

            array_push($response['bulan_pelaksanaan'], $i);

            //clear array of rencana aksi 
            $response['rencana_aksi'] = array();
        }

        return [
            'bulanList'       => $response['bulan_pelaksanaan'],
            //'tes' => $bulan
        ];
    }

    public function Store(Request $request)
    {
        $messages = [
            'sasaranKinerjaId.required'      => 'Harus diisi',
            'rencanaKinerjaId.required'      => 'Harus diisi',
            'rencanaAksiLabel.required'      => 'Harus diisi',
            'bulanPelaksanaanId.required'    => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'sasaranKinerjaId'     => 'required',
                'rencanaKinerjaId'     => 'required',
                'rencanaAksiLabel'     => 'required',
                'bulanPelaksanaanId'   => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }
        $data = 0 ;
        foreach( $request->bulanPelaksanaanId AS $bulan ){
            $ra    = new RencanaAksi;
            $ra->sasaran_kinerja_id          = $request->sasaranKinerjaId;
            $ra->rencana_kinerja_id          = $request->rencanaKinerjaId;
            $ra->label                       = $request->rencanaAksiLabel;
            $ra->bulan_pelaksanaan           = $bulan;
            $ra->save();
            $data++;
        }

        if ($data >= 1) {
            return \Response::make('sukses', 200);
        } else {
            return \Response::make('error', 500);
        } 
    }


    public function Detail(Request $request)
    {
        $data = RencanaAksi::WHERE('id', '=', $request->id)->first();

        $response = [
                    'id'                                => $data->id,
                    'label'                             => $data->label,
                    'sasaran_kinerja_id'                => $data->sasaran_kinerja_id,
                    'rencana_kinerja_id'                => $data->rencana_kinerja_id,
                    'bulan_pelaksanaan'                 => Pustaka::bulan($data->bulan_pelaksanaan),
                    ];


        return [
            'data'     => $response,
        ];
    }

    



}
