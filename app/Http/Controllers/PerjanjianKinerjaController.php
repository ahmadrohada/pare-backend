<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Traits\PerjanjianKinerja;
use App\Models\SasaranStrategis;
use App\Models\IndikatorSasaranStrategis;

use App\Http\Resources\PerjanjianKinerja as PerjanjianKinerjaResource;
use App\Services\Datatables\PerjanjianKinerjaDataTable;

use Illuminate\Pagination\Paginator;
use Validator;

class PerjanjianKinerjaController extends Controller
{

    use PerjanjianKinerja;

    public function SasaranStrategisSKPD(Request $request)
    {
        $searchDatas = new PerjanjianKinerjaDataTable($request->all());
        $data = $searchDatas->search();
        return $data;
    }

    public function SasaranStrategisSelectList(Request $request)
    {
        $response = array();
        $response['sasaran_strategis'] = array();

        $data = SasaranStrategis::WHERE('renja_id',$request->renja_id)->get();
        foreach( $data AS $y ){
            $r['id']            = $y->id;
            $r['label']         = $y->label;

            array_push($response['sasaran_strategis'], $r);
        }


        return $response;
    }

    public function SasaranStrategisStore(Request $request)
    {
        $messages = [
            'sasaranStrategisLabel.required'    => 'Harus diisi',
            'renjaId.required'                  => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'renjaId'                   => 'required',
                'sasaranStrategisLabel'     => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }
        $ah    = new SasaranStrategis;
        $ah->renja_id            = $request->renjaId;
        $ah->label               = $request->sasaranStrategisLabel;

        if ($ah->save()) {
            $data = array(
                        'id'        => $ah->id,
                    );
            return \Response::make($data, 200);
        } else {
            return \Response::make('error', 500);
        }
    }

    public function IndikatorSasaranStrategisStore(Request $request)
    {
        $messages = [
            'indikatorSasaranStrategisLabel.required'    => 'Harus diisi',
            'sasaranStrategisId.required'                => 'Harus diisi',
            'target.required'                            => 'Harus diisi',
            'satuanTarget.required'                      => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'sasaranStrategisId'                    => 'required',
                'indikatorSasaranStrategisLabel'        => 'required',
                'target'                    => 'required',
                'satuanTarget'                    => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }
        $ah    = new IndikatorSasaranStrategis;
        $ah->sasaran_strategis_id            = $request->sasaranStrategisId;
        $ah->label                           = $request->indikatorSasaranStrategisLabel;
        $ah->target                          = $request->target;
        $ah->satuan_target                   = $request->satuanTarget;

        if ($ah->save()) {
            $data = array(
                        'id'        => $ah->id,
                    );
            return \Response::make($data, 200);
        } else {
            return \Response::make('error', 500);
        }
    }


}
