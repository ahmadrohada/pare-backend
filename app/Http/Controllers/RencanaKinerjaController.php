<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RencanaKinerja;

use App\Services\Datatables\RencanaKinerjaDataTable;

use Validator;

class RencanaKinerjaController extends Controller
{

    public function List(Request $request)
    {
        $searchDatas = new RencanaKinerjaDataTable($request->all());
        $data = $searchDatas->search();
        return $data;
    }

    public function SelectList(Request $request)
    {
        $response = array();
        $response['rencana_kinerja'] = array();

        $data = RencanaKinerja::WHERE('sasaran_kinerja_id',$request->sasaran_kinerja_id)->get();
        foreach( $data AS $y ){
            $r['id']            = $y->id;
            $r['label']         = $y->label;

            array_push($response['rencana_kinerja'], $r);
        }
        return $response;
    }


    public function Store(Request $request)
    {
        $messages = [
            'sasaranKinerjaId.required'         => 'Harus diisi',
            'rencanaKinerjaLabel.required'      => 'Harus diisi',
            'jenisRencanaKinerja.required'      => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'sasaranKinerjaId'                  => 'required',
                'rencanaKinerjaLabel'               => 'required',
                'jenisRencanaKinerja'               => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $rk    = new RencanaKinerja;
        $rk->sasaran_kinerja_id                                 = $request->sasaranKinerjaId;
        $rk->label                                  = $request->rencanaKinerjaLabel;
        $rk->jenis_rencana_kinerja                  = $request->jenisRencanaKinerja;
        $rk->lingkup_penugasan_kinerja_tambahan     = $request->lingkupPenugasan;
        $rk->type_kinerja_utama                     = $request->typeKinerjaUtama;
        $rk->save();


        if ($rk->save()) {
            $data = array(
                        'id'        => $rk->id,
                    );
            return \Response::make($data, 200);
        } else {
            return \Response::make('error', 500);
        }
    }

    public function Detail(Request $request)
    {
        $sk = RencanaKinerja::SELECT(
                                                        'id',
                                                        'label',
                                                        'jenis_rencana_kinerja',
                                                        'type_kinerja_utama',
                                                        'lingkup_penugasan_kinerja_tambahan'
                                                    )
                                                    ->WHERE('id', $request->id)
                                                    ->first();


        if ($sk) {
            $h['id']                                    = $sk->id;
            $h['label']                                 = $sk->label;
            $h['jenis_rencana_kinerja']                 = $sk->jenis_rencana_kinerja;
            $h['type_kinerja_utama']                    = $sk->type_kinerja_utama;
            $h['lingkup_penugasan_kinerja_tambahan']    = $sk->lingkup_penugasan_kinerja_tambahan;



        } else {
            $h = null;
        }

        return $h;
    }

    public function Update(Request $request)
    {
        $messages = [
            'rencanaKinerjaId.required'         => 'Harus diisi',
            'rencanaKinerjaLabel.required'      => 'Harus diisi',
            'jenisRencanaKinerja.required'      => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'rencanaKinerjaId'         => 'required',
                'rencanaKinerjaLabel'      => 'required',
                'jenisRencanaKinerja'      => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $update  = RencanaKinerja::find($request->rencanaKinerjaId);
        if (is_null($update)) {
            return \Response::make(['message' => "ID Rencana Kinerja tidak ditemukan"], 500);
        }

        $update->label                                  = $request->rencanaKinerjaLabel;
        $update->jenis_rencana_kinerja                  = $request->jenisRencanaKinerja;
        $update->type_kinerja_utama                     = $request->typeKinerjaUtama;
        $update->lingkup_penugasan_kinerja_tambahan     = $request->lingkupPenugasan;

        if ($update->save()) {
            $data = array(
                        'id'        => $update->id,
                    );
            return \Response::make($data, 200);
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


        $sr    = RencanaKinerja::find($request->id);
        if (is_null($sr)) {
            return \Response::make(['message' => "ID Sasaran Kinerja tidak ditemukan"], 500);
        }


        if ( $sr->delete()){
            return \Response::make('sukses', 200);
        }else{
            return \Response::make('error', 500);
        }
    }




}
