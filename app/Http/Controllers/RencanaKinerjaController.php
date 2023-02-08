<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RencanaKinerja;
use App\Models\SasaranKinerja;

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


    public function RencanaHasilKerjaPimpinanList(Request $request)
    {

        //SASAran Kinerja
        $data = SasaranKinerja::SELECT("pejabat_penilai")->WHERE('id',$request->sasaran_kinerja_id)->first();

        //PEJABAT PENILAI
        $data_2 = SasaranKinerja::SELECT('id AS sasaran_kinerja_id')->WHERE('pegawai_yang_dinilai',$data->pejabat_penilai)->first();

        $data_3 = RencanaKinerja::WHERE('id',$request->rencana_kinerja_id)->first();

        $response = array();
        $response['rencana_hasil_kerja_pimpinan'] = array();
        $response['rencana_kinerja_detail'] = $data_3;

        $data = RencanaKinerja::WHERE('sasaran_kinerja_id',$data_2->sasaran_kinerja_id)->get();
        foreach( $data AS $y ){
            $r['id']            = $y->id;
            $r['label']         = $y->label;

            array_push($response['rencana_hasil_kerja_pimpinan'], $r);
        }
        return $response;
    }

    public function RencanaHasilKerjaPimpinanStore(Request $request)
    {

        $messages = [
            'rencanaKinerjaPimpinanId.required'         => 'Harus diisi',
            'rencanaKinerjaId.required'                 => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'rencanaKinerjaPimpinanId'          => 'required',
                'rencanaKinerjaId'                  => 'required',
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

        $update->parent_id       = $request->rencanaKinerjaPimpinanId;

        if ($update->save()) {
            return \Response::make("Sukses", 200);
        } else {
            return \Response::make('error', 500);
        }
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


    public function StoreFromOutcome(Request $request)
    {
        $messages = [
            'sasaranKinerjaId.required'         => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'sasaranKinerjaId'                  => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            $messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }


        $selectedOutcome = $request->selectedOutcome;
        $no = 0;
        if ( $selectedOutcome != null ){
            foreach ($selectedOutcome as $x) {

                $rk    = new RencanaKinerja;
                $rk->sasaran_kinerja_id                     = $request->sasaranKinerjaId;
                $rk->label                                  = $x['label'];
                $rk->jenis_rencana_kinerja                  = 'kinerja_utama';
                $rk->matriks_hasil_id                       = $x['id'];
                $rk->save();
    
                if ($rk->save()) {
                    $no++;
                }
            }
        }
        

        if ($no > 0) {
            return \Response::make($no . "data berhasil tersimpan", 200);
        } else {
            return \Response::make("data tidak berhasil tersimpan", 400);
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
