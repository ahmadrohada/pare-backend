<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SasaranKinerja;
use App\Models\SasaranKinerjaReviu;

use App\Http\Resources\User as UserResource;

use Validator;

class SasaranKinerjaReviuController extends Controller
{



    public function Store(Request $request)
    {

        $messages = [

                    'id.required'              => 'Harus diisi',

        ];



        $validator = Validator::make(
            $request->all(),
            [
                    'id'                              => 'required',

            ],
            $messages
        );

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 422);
        }

        //cek apakah punya sasaran strategis pada PK nta
        $sasaran = SasaranKinerjaReviu::WHERE('sasaran_kinerja_id','=',$request->id )->first();

        if ($sasaran != null ) {
            return response()->json(['message' => "dalam Proses Reviu"], 403);
        }


        $skr    = new SasaranKinerjaReviu;
        $skr->sasaran_kinerja_id      = $request->id;
        $skr->pengelola_kinerja       = json_encode(new UserResource(auth()->user()) );


        if ($skr->save()) {

            $update  = SasaranKinerja::find($request->id);
            $update->status            = '3';
            $update->save();

            return \Response::make($request->id, 200);
        } else {
            return \Response::make(['message' => "Terjadi kesalahan saat menyimpan SKP"], 500);
        }
    }

}
