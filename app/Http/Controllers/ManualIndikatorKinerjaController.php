<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ManualIndikatorKinerja;


use Validator;

class ManualIndikatorKinerjaController extends Controller
{



    public function Detail(Request $request)
    {
        $x = ManualIndikatorKinerja::with(array('IndikatorKinerjaIndividu' => function($query) {
                $query->select('id','label');
            }))
            ->with(array('RencanaKinerja' => function($query) {
                $query->select('id','label');
            }))
            ->with(array('SasaranKinerja' => function($query) {
                $query->select('id','skpd_id','user_id','jabatan_aktif_id','pegawai_yang_dinilai','pejabat_penilai');
            }))
            ->WHERE('id', $request->id)
            ->first();

        if ($x) {
            $h['manual_indikator_kinerja_id']               = $x->id;
            $h['sasaran_kinerja_id']                        = $x->sasaran_kinerja_id;
            $h['deskripsi_rencana_kinerja']                 = $x->deskripsi_rencana_kinerja;
            $h['definisi']                                  = $x->definisi;
            $h['formula']                                   = $x->formula;
            $h['tujuan']                                    = $x->tujuan;
            $h['satuan_pengukuran']                         = $x->satuan_pengukuran;
            $h['jenis_indikator_kinerja']                   = $x->jenis_indikator_kinerja;
            $h['penanggung_jawab']                          = $x->penanggung_jawab;
            $h['pihak_penyedia_data']                       = $x->pihak_penyedia_data;
            $h['sumber_data']                               = $x->sumber_data;
            $h['periode_pelaporan']                         = $x->periode_pelaporan;

            $h['sasaran_kinerja']                           = json_decode($x->SasaranKinerja);
            $h['rencana_kinerja']                           = json_decode($x->RencanaKinerja);
            $h['indikator_kinerja_individu']                = json_decode($x->IndikatorKinerjaIndividu);

            $h['pegawai_yang_dinilai']                      = json_decode($x->SasaranKinerja->pegawai_yang_dinilai);
            $h['pejabat_penilai']                           = json_decode($x->SasaranKinerja->pejabat_penilai);


        } else {
            $h = null;
        }

        return $h;
    }

    public function Store(Request $request)
    {

        $messages = [
            'sasaranKinerjaId.required'                 => 'Harus diisi',
            'rencanaKinerjaId.required'                 => 'Harus diisi',
            'indikatorKinerjaId.required'               => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'sasaranKinerjaId'                      => 'required',
                'rencanaKinerjaId'                      => 'required',
                'indikatorKinerjaId'                    => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }
        $ah    = new ManualIndikatorKinerja;
        $ah->sasaran_kinerja_id                 = $request->sasaranKinerjaId;
        $ah->rencana_kinerja_id                 = $request->rencanaKinerjaId;
        $ah->indikator_kinerja_individu_id      = $request->indikatorKinerjaId;
        $ah->deskripsi_rencana_kinerja          = $request->deskripsiRencanaKinerja;
        $ah->definisi                           = $request->deskripsiDefinisi;
        $ah->formula                            = $request->deskripsiFormula;
        $ah->tujuan                             = $request->deskripsiTujuan;
        $ah->satuan_pengukuran                  = $request->satuanPengukuran;
        $ah->jenis_indikator_kinerja            = $request->jenisIndikatorKinerjaUtama;
        $ah->penanggung_jawab                   = $request->penanggungJawab;
        $ah->pihak_penyedia_data                = $request->pihakPenyediaData;
        $ah->sumber_data                        = $request->sumberData;
        $ah->periode_pelaporan                  = $request->periodePelaporan;

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
            'manualIndikatorKinerjaId.required'         => 'Harus diisi',
            'sasaranKinerjaId.required'                 => 'Harus diisi',
            'rencanaKinerjaId.required'                 => 'Harus diisi',
            'indikatorKinerjaId.required'               => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'manualIndikatorKinerjaId'              => 'required',
                'sasaranKinerjaId'                      => 'required',
                'rencanaKinerjaId'                      => 'required',
                'indikatorKinerjaId'                    => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $update  = ManualIndikatorKinerja::find($request->manualIndikatorKinerjaId);

        $update->sasaran_kinerja_id                 = $request->sasaranKinerjaId;
        $update->rencana_kinerja_id                 = $request->rencanaKinerjaId;
        $update->indikator_kinerja_individu_id      = $request->indikatorKinerjaId;
        $update->deskripsi_rencana_kinerja          = $request->deskripsiRencanaKinerja;
        $update->definisi                           = $request->deskripsiDefinisi;
        $update->formula                            = $request->deskripsiFormula;
        $update->tujuan                             = $request->deskripsiTujuan;
        $update->satuan_pengukuran                  = $request->satuanPengukuran;
        $update->jenis_indikator_kinerja            = $request->jenisIndikatorKinerjaUtama;
        $update->penanggung_jawab                   = $request->penanggungJawab;
        $update->pihak_penyedia_data                = $request->pihakPenyediaData;
        $update->sumber_data                        = $request->sumberData;
        $update->periode_pelaporan                  = $request->periodePelaporan;

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
