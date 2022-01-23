<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SasaranKinerja;
use App\Models\SasaranKinerjaReviu;

use Validator;

class SasaranKinerjaReviuController extends Controller
{



    public function Store(Request $request)
    {

        $messages = [

                    'dateFrom.required'              => 'Harus diisi',
                    'dateTo.required'                => 'Harus diisi',
                    'periodePkId.required'           => 'Harus diisi',
                    'userId.required'                => 'Harus diisi',
                    'simpegId.required'              => 'Harus diisi',
                    'jenisJabatanSkp.required'       => 'Harus diisi',

        ];



        $validator = Validator::make(
            $request->all(),
            [
                    'dateFrom'                              => 'required',
                    'dateTo'                                => 'required',
                    'periodePkId'                           => 'required',
                    'userId'                                => 'required',
                    'simpegId'                              => 'required',
                    'jenisJabatanSkp'                       => 'required',

            ],
            $messages
        );

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 422);
        }



        $ah    = new SasaranKinerjaReviu;
        $ah->user_id                    = $request->userId;
        $ah->jabatan_aktif_id           = $request->jabatanAktifPegawaiYangDinilaiId;


        if ($ah->save()) {
            $skp    = SasaranKinerja::find($request->sasaran_kinerja_id);
            $skp->status      = '3';
            $skp->reviu       = $ah->created_at;

            $iki->save();

            return \Response::make($ah, 200);
        } else {
            return \Response::make(['message' => "Terjadi kesalahan saat menyimpan SKP"], 500);
        }
    }


    public function SasaranKinerjaDestroy(Request $request)
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


        $sr    = SasaranKinerja::find($request->id);
        if (is_null($sr)) {
            return \Response::make(['message' => "Sasaran Kinerja tidak ditemukan"], 500);
        }


        if ( $sr->delete()){
            return \Response::make('sukses', 200);
        }else{
            return \Response::make('error', 500);
        }


    }




}
