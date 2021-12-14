<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PerjanjianKinerja;
use App\Models\SasaranStrategis;
use App\Models\IndikatorSasaranStrategis;
use App\Models\Periode;
use App\Models\User;

use App\Http\Traits\PerjanjianKinerjaTrait;
use App\Http\Traits\SotkRequest;

use App\Services\Datatables\PerjanjianKinerjaDataTable;
use App\Services\Datatables\SasaranStrategisDataTable;

use Validator;

class PerjanjianKinerjaController extends Controller
{

    use PerjanjianKinerjaTrait;
    use SotkRequest;

    public function SasaranStrategis(Request $request)
    {
        $searchDatas = new SasaranStrategisDataTable($request->all());
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

    public function SasaranStrategisDetail(Request $request)
    {
        $indikator = SasaranStrategis::with(array('Periode' => function($query) {
                $query->select('id');
                //->with('parent:id,label')
                //->with('renja:id,periode->tahun AS periode,skpd->id AS id_skpd,skpd->nama AS nama_skpd,status');
            }))
            ->SELECT(
                'id',
                'label',

            )
            ->WHERE('id', $request->id)
            ->first();


        if ($indikator) {
            $h['id']                    = $indikator->id;
            $h['label']                 = $indikator->label;

        } else {
            $h = null;
        }

        return $h;
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
    public function SasaranStrategisUpdate(Request $request)
    {
        $messages = [
            'sasaranStrategisId'                => 'Harus diisi',
            'sasaranStrategisLabel.required'    => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'sasaranStrategisId'         => 'required',
                'sasaranStrategisLabel'      => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $update  = SasaranStrategis::find($request->sasaranStrategisId);

        $update->label                           = $request->sasaranStrategisLabel;

        if ($update->save()) {
            $data = array(
                        'id'        => $update->id,
                    );
            return \Response::make($data, 200);
        } else {
            return \Response::make('error', 500);
        }
    }

    public function SasaranStrategisDestroy(Request $request)
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


        $sr    = SasaranStrategis::find($request->id);
        if (is_null($sr)) {
            return $this->sendError('Sasaran Strategis tidak ditemukan.');
        }


        if ( $sr->delete()){
            return \Response::make('sukses', 200);
        }else{
            return \Response::make('error', 500);
        }
    }

    public function IndikatorSasaranStrategisDetail(Request $request)
    {
        $indikator = IndikatorSasaranStrategis::with(array('SasaranStrategis' => function($query) {
                $query->select('id','renja_id','label');
                //->with('parent:id,label')
                //->with('renja:id,periode->tahun AS periode,skpd->id AS id_skpd,skpd->nama AS nama_skpd,status');
            }))
            ->SELECT(
                'id',
                'sasaran_strategis_id',
                'label',
                'target',
                'satuan_target',

            )
            ->WHERE('id', $request->id)
            ->first();


        if ($indikator) {
            $h['id']                    = $indikator->id;
            $h['sasaran_strategis_id']  = $indikator->sasaran_strategis_id;
            $h['label']                 = $indikator->label;
            $h['target']                = $indikator->target;
            $h['satuan_target']         = $indikator->satuan_target;
            $h['renja_id']              = $indikator->SasaranStrategis->renja_id;

        } else {
            $h = null;
        }

        return $h;
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

    public function IndikatorSasaranStrategisUpdate(Request $request)
    {
        $messages = [
            'indikatorId'                                => 'Harus diisi',
            'sasaranStrategisId.required'                => 'Harus diisi',
            'indikatorSasaranStrategisLabel.required'    => 'Harus diisi',
            'target.required'                            => 'Harus diisi',
            'satuanTarget.required'                      => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'indikatorId'                           => 'required',
                'sasaranStrategisId'                    => 'required',
                'indikatorSasaranStrategisLabel'        => 'required',
                'target'                                => 'required',
                'satuanTarget'                          => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $update  = IndikatorSasaranStrategis::find($request->indikatorId);

        $update->sasaran_strategis_id            = $request->sasaranStrategisId;
        $update->label                           = $request->indikatorSasaranStrategisLabel;
        $update->target                          = $request->target;
        $update->satuan_target                   = $request->satuanTarget;

        if ($update->save()) {
            $data = array(
                        'id'        => $update->id,
                    );
            return \Response::make($data, 200);
        } else {
            return \Response::make('error', 500);
        }
    }

    public function IndikatorSasaranStrategisDestroy(Request $request)
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


        $sr    = IndikatorSasaranStrategis::find($request->id);
        if (is_null($sr)) {
            return $this->sendError('Indikator Sasaran Strategis tidak ditemukan.');
        }


        if ( $sr->delete()){
            return \Response::make('sukses', 200);
        }else{
            return \Response::make('error', 500);
        }
    }




    //==================================  PERJANJIAN KINERJA ========================================================//

    public function PerjanjianKinerjaList(Request $request)
    {
        $searchDatas = new PerjanjianKinerjaDataTable($request->all());
        $data = $searchDatas->search();
        //return PerjanjianKinerjaResource::collection($data);
        return $data;
    }

    public function PerjanjianKinerjaCreate(Request $request)
    {
        //get active periode
        $periodes = Periode::SELECT('id AS id','tahun AS label')->get()->toArray();
        $periode_active = Periode::WHERE('status','=','1')->SELECT('id')->first();

        $detailRenja = [
            ['title' => 'SKPD', 'label' => $this->Skpd($request->skpd_id)['nama'] ],
        ];

        return [
            'skpdId'             => $request->skpd_id,
            'periodeList'        => $periodes,
            'detailRenja'        => $detailRenja,
            'periodeAktifId'     => $periode_active->id,
            'userId'             => auth()->user()->id

        ];
    }

    public function PerjanjianKinerjaStore(Request $request)
    {
        $messages = [
            'periodeId.required'    => 'Harus diisi',
            'skpdId.required'       => 'Harus diisi',
            'userId.required'       => 'Harus diisi',

        ];



        $validator = Validator::make(
            $request->all(),
            [
                'periodeId'         => 'required',
                'skpdId'            => 'required',
                'userId'            => 'required',

            ],
            $messages
        );

        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }

        //cek apakah pernah ada id skpd dan id periode yang sama pada db
        if (PerjanjianKinerja::WHERE('periode_id','=',$request->periodeId)->WHERE('skpd_id','=',$request->skpdId)->exists()) {
            return \Response::make(['message'=>'Renja pada periode ini sudah dibuat'],422);
        }

        //cari  detail pejabat dan jabatan pada SIM-ASN
        $admin  =  User::WHERE('id','=',$request->userId)->first();
        $skpd   =  $this->Skpd($request->skpdId);

        $rp    = new PerjanjianKinerja;
        $rp->skpd_id             = $request->skpdId;
        $rp->periode_id          = $request->periodeId;
        $rp->periode             = json_encode(Periode::WHERE('id',$request->periodeId)->first());
        $rp->skpd                = json_encode($skpd);
        $rp->kepala_skpd         = null;
        $rp->admin               = json_encode($admin);

        if ($rp->save()) {
            return \Response::make($rp->id, 200);
        } else {
            return \Response::make('error', 500);
        }



    }
    public function PerjanjianKinerjaDestroy(Request $request)
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


        $sr    = PerjanjianKinerja::find($request->id);
        if (is_null($sr)) {
            return $this->sendError('Perjanjian Kinerja tidak ditemukan.');
        }

        /* if (TimKerja::where('pk_id', '=',$request->id)->exists()) {
            return \Response::make(['message'=>'Tidak dapat menghapus PerjanjianKinerja yang sudah memiliki Tim Kerja'],422);
        } */


        if ( $sr->delete()){
            return \Response::make('sukses', 200);
        }else{
            return \Response::make('error', 500);
        }
    }

}
