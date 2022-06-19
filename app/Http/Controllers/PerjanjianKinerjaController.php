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

        $data = SasaranStrategis::WHERE('perjanjian_kinerja_id',$request->perjanjian_kinerja_id)->get();
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
            'perjanjianKinerjaId.required'                  => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'perjanjianKinerjaId'                   => 'required',
                'sasaranStrategisLabel'     => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }
        $ah    = new SasaranStrategis;
        $ah->perjanjian_kinerja_id            = $request->perjanjianKinerjaId;
        $ah->label                            = $request->sasaranStrategisLabel;

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
            return \Response::make(['message' => "ID Sasaran Strategis tidak ditemukan"], 500);
        }


        if ( $sr->delete()){
            return \Response::make('sukses', 200);
        }else{
            return \Response::make('error', 500);
        }
    }

    public function IndikatorSasaranStrategisSelectList(Request $request)
    {
        $response = array();
        $response['indikator_kinerja_utama'] = array();

        $data = IndikatorSasaranStrategis::WHERE('sasaran_strategis_id',$request->sasaran_strategis_id)->get();
        foreach( $data AS $y ){
            $r['id']            = $y->id;
            $r['label']         = $y->label;

            array_push($response['indikator_kinerja_utama'], $r);
        }


        return $response;
    }

    public function IndikatorSasaranStrategisDetail(Request $request)
    {
        $indikator = IndikatorSasaranStrategis::with(array('SasaranStrategis' => function($query) {
                $query->select('id','perjanjian_kinerja_id','label');
                //->with('parent:id,label')
                //->with('renja:id,periode->tahun AS periode,skpd->id AS id_skpd,skpd->nama AS nama_skpd,status');
            }))
            ->SELECT(
                'id',
                'sasaran_strategis_id',
                'label',
                'type_target',
                'target_min',
                'target_max',
                'satuan_target',

            )
            ->WHERE('id', $request->id)
            ->first();


        if ($indikator) {
            $h['id']                    = $indikator->id;
            $h['sasaran_strategis_id']  = $indikator->sasaran_strategis_id;
            $h['label']                 = $indikator->label;
            $h['type_target']           = $indikator->type_target;
            $h['target_min']            = $indikator->target_min;
            $h['target_max']            = $indikator->target_max;
            $h['satuan_target']         = $indikator->satuan_target;
            $h['perjanjian_kinerja_id'] = $indikator->SasaranStrategis->perjanjian_kinerja_id;

        } else {
            $h = null;
        }

        return $h;
    }


    public function IndikatorSasaranStrategisStore(Request $request)
    {
        $messages = [
            'indikatorSasaranStrategisLabel.required'       => 'Harus diisi',
            'sasaranStrategisId.required'                   => 'Harus diisi',
            'typeTarget.required'                           => 'Harus diisi',
            'targetMin.required'                            => 'Harus diisi',
            'targetMax.required'                            => 'Harus diisi',
            'satuanTarget.required'                         => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'sasaranStrategisId'                    => 'required',
                'indikatorSasaranStrategisLabel'        => 'required',
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
        $ah    = new IndikatorSasaranStrategis;
        $ah->sasaran_strategis_id            = $request->sasaranStrategisId;
        $ah->label                           = $request->indikatorSasaranStrategisLabel;
        $ah->type_target                     = $request->typeTarget;
        $ah->target_min                      = $request->targetMin;
        $ah->target_max                      = $request->targetMax;
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
            'typeTarget.required'                        => 'Harus diisi',
            'targetMin.required'                         => 'Harus diisi',
            'targetMax.required'                         => 'Harus diisi',
            'satuanTarget.required'                      => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'indikatorId'                           => 'required',
                'sasaranStrategisId'                    => 'required',
                'indikatorSasaranStrategisLabel'        => 'required',
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

        $update  = IndikatorSasaranStrategis::find($request->indikatorId);

        $update->sasaran_strategis_id            = $request->sasaranStrategisId;
        $update->label                           = $request->indikatorSasaranStrategisLabel;
        $update->type_target                     = $request->typeTarget;
        $update->target_min                      = $request->targetMin;
        $update->target_max                      = $request->targetMax;
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

    public function PerjanjianKinerjaSubmit(Request $request)
    {
        $messages = [
            'id'                                => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'id'                           => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $update  = PerjanjianKinerja::find($request->id);

        $update->status            = 'close';

        if ($update->save()) {
            $data = array(
                        'id'        => $update->id,
                    );
            return \Response::make($data, 200);
        } else {
            return \Response::make('error', 500);
        }
    }

    public function PerjanjianKinerjaDetail(Request $request)
    {
        $pk = PerjanjianKinerja::with(array('Periode' => function($query) {
                //$query->select('id','label');
            }))
            ->SELECT(
                'perjanjian_kinerja.periode->tahun AS periodePk',
                'perjanjian_kinerja.skpd->nama AS namaSkpd',
                'perjanjian_kinerja.kepala_skpd->pegawai->nama_lengkap AS namaKepalaSkpd',
                'perjanjian_kinerja.jabatan_kepala_skpd->nama AS jabatanKepalaSkpd',
                'perjanjian_kinerja.admin->pegawai->nama_lengkap AS createdBy',
                'perjanjian_kinerja.created_at AS createdAt',
                'perjanjian_kinerja.status'
            )
            ->WHERE('id', $request->id)
            ->first();



        $h['id']                    = $request->id;
        $h['periodePk']             = $pk->periodePk;
        $h['namaSkpd']              = $pk->namaSkpd;
        $h['namaKepalaSkpd']        = $pk->namaKepalaSkpd;
        $h['jabatanKepalaSkpd']     = $pk->jabatanKepalaSkpd;
        $h['createdBy']             = $pk->createdBy;
        $h['createdAt']             = $pk->createdAt;
        $h['status']                = $pk->status;
        $h['jumlahSasaranStrategis']= SasaranStrategis::WHERE('perjanjian_kinerja_id','=',$request->id)->count();

        return $h;

    }

    public function PerjanjianKinerjaId(Request $request)
    {
        $pk = PerjanjianKinerja::
            SELECT(
                'perjanjian_kinerja.id AS pkId',
                'perjanjian_kinerja.periode->tahun AS periodePk',
                'perjanjian_kinerja.skpd->nama AS namaSkpd',
                'perjanjian_kinerja.kepala_skpd->id AS kepala_skpd_id',
                'perjanjian_kinerja.jabatan_kepala_skpd->nama AS jabatanKepalaSkpd',
                'perjanjian_kinerja.admin->pegawai->nama_lengkap AS createdBy',
                'perjanjian_kinerja.created_at AS createdAt',
                'perjanjian_kinerja.status'
            )
            ->WHERE('skpd_id', $request->skpd_id)
            ->WHERE('periode->tahun', $request->periode)
            ->first();

        if ($pk){
            $h['id']                = $pk->pkId;
            $h['kepala_skpd_id']    = $pk->kepala_skpd_id;
        }else{
            $h['id']   = null;
        }
        return $h;
    }

    public function PerjanjianKinerjaStore(Request $request)
    {
        $messages = [
            'periode.required'    => 'Harus diisi',
            'skpdId.required'       => 'Harus diisi',
            'userId.required'       => 'Harus diisi',
            'kepalaSkpdId.required' => 'Harus diisi',

        ];



        $validator = Validator::make(
            $request->all(),
            [
                'periode'         => 'required',
                'skpdId'            => 'required',
                'userId'            => 'required',
                'kepalaSkpdId'      => 'required',

            ],
            $messages
        );

        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }



        //cek apakah pernah ada id skpd dan id periode yang sama pada db
        if (PerjanjianKinerja::WHERE('periode->tahun','=',$request->periode)->WHERE('skpd_id','=',$request->skpdId)->exists()) {
            return \Response::make(['message'=>'PK pada periode ini sudah dibuat'],422);
        }

        //cari  detail pejabat dan jabatan pada SIM-ASN
        $admin  =  User::WHERE('id','=',$request->userId)->first();
        $kepala_skpd  =  User::WHERE('id','=',$request->kepalaSkpdId)->first();
        $skpd   =  $this->Skpd($request->skpdId);

        $jabatanKepalaSKPD = null ;
        //cari jabatannya kepala SKPD
        if ( $this->Pegawai($kepala_skpd->nip) != null ){
            foreach( $this->Pegawai($kepala_skpd->nip)['jabatan'] AS $x ){
                //jika jabatan id nya jabatanId
                if ( $x['id'] == $request->jabatanId ){
                    $jabatanKepalaSKPD = json_encode($x);
                }
            }
        }

        //PERIODE
        $periode_data = Periode::WHERE('tahun',$request->periode)->first();


        $rp    = new PerjanjianKinerja;
        $rp->skpd_id             = $request->skpdId;
        $rp->periode_id          = $periode_data->id;
        $rp->periode             = json_encode(Periode::WHERE('tahun',$request->periode)->first());
        $rp->skpd                = json_encode($skpd);
        $rp->kepala_skpd         = json_encode($kepala_skpd);
        $rp->jabatan_kepala_skpd = $jabatanKepalaSKPD;
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
