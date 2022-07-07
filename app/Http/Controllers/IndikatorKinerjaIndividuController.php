<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\IndikatorKinerjaIndividu;


use Validator;

class IndikatorKinerjaIndividuController extends Controller
{



    public function Detail(Request $request)
    {
        $indikator = IndikatorKinerjaIndividu::with(array('RencanaKinerja' => function($query) {
                $query->select('id','sasaran_kinerja_id','label');
            }))
            ->SELECT(
                'id',
                'rencana_kinerja_id',
                'label',
                'type_target',
                'target_min',
                'target_max',
                'satuan_target',
                'perspektif',
                'aspek'

            )
            ->WHERE('id', $request->id)
            ->first();


        if ($indikator) {
            $h['id']                    = $indikator->id;
            $h['rencana_kinerja_id']    = $indikator->rencana_kinerja_id;
            $h['label']                 = $indikator->label;
            $h['type_target']           = $indikator->type_target;
            $h['target_min']            = $indikator->target_min;
            $h['target_max']            = $indikator->target_max;
            $h['satuan_target']         = $indikator->satuan_target;
            $h['perspektif']            = $indikator->perspektif;
            $h['aspek']                 = $indikator->aspek;
            $h['sasaran_kinerja_id']    = $indikator->RencanaKinerja->sasaran_kinerja_id;

            $h['rencana_kinerja']       = json_decode($indikator->RencanaKinerja);

        } else {
            $h = null;
        }

        return $h;
    }

    public function Store(Request $request)
    {

        $messages = [
            'indikatorKinerjaIndividuLabel.required'       => 'Harus diisi',
            'rencanaKinerjaId.required'                   => 'Harus diisi',
            'typeTarget.required'                           => 'Harus diisi',
            'targetMin.required'                            => 'Harus diisi',
            'targetMax.required'                            => 'Harus diisi',
            'satuanTarget.required'                         => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'rencanaKinerjaId'                    => 'required',
                'indikatorKinerjaIndividuLabel'        => 'required',
                'typeTarget'                            => 'required',
                'targetMin'                             => 'required',
                'targetMax'                             => 'required',
                'satuanTarget'                          => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }
        $ah    = new IndikatorKinerjaIndividu;
        $ah->rencana_kinerja_id              = $request->rencanaKinerjaId;
        $ah->label                           = $request->indikatorKinerjaIndividuLabel;
        $ah->type_target                     = $request->typeTarget;
        $ah->target_min                      = $request->targetMin;
        $ah->target_max                      = $request->targetMax;
        $ah->satuan_target                   = $request->satuanTarget;
        $ah->perspektif                      = $request->perspektif;
        $ah->aspek                           = $request->aspek;

        if ($ah->save()) {
            $data = array(
                        'id'        => $ah->id,
                    );
            return \Response::make($data, 200);
        } else {
            return \Response::make('error', 500);
        }
    }

    public function Update(Request $request)
    {
        $messages = [
            'indikatorId'                                => 'Harus diisi',
            'rencanaKinerjaId.required'                  => 'Harus diisi',
            'indikatorKinerjaIndividuLabel.required'     => 'Harus diisi',
            'typeTarget.required'                        => 'Harus diisi',
            'targetMin.required'                         => 'Harus diisi',
            'targetMax.required'                         => 'Harus diisi',
            'satuanTarget.required'                      => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'indikatorId'                           => 'required',
                'rencanaKinerjaId'                      => 'required',
                'indikatorKinerjaIndividuLabel'         => 'required',
                'typeTarget'                            => 'required',
                'targetMin'                             => 'required',
                'targetMax'                             => 'required',
                'satuanTarget'                          => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $update  = IndikatorKinerjaIndividu::find($request->indikatorId);

        $update->rencana_kinerja_id              = $request->rencanaKinerjaId;
        $update->label                           = $request->indikatorKinerjaIndividuLabel;
        $update->type_target                     = $request->typeTarget;
        $update->target_min                      = $request->targetMin;
        $update->target_max                      = $request->targetMax;
        $update->satuan_target                   = $request->satuanTarget;
        $update->perspektif                      = $request->perspektif;
        $update->aspek                           = $request->aspek;

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


        $iki    = IndikatorKinerjaIndividu::find($request->id);
        if (is_null($iki)) {
            return \Response::make(['message' => "ID IKI tidak ditemukan"], 500);
        }


        if ( $iki->delete()){
            return \Response::make('sukses', 200);
        }else{
            return \Response::make('error', 500);
        }
    }

}
