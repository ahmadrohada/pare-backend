<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimKerja;
use App\Models\RenjaPejabat;
use App\Models\RencanaKinerja;

use Illuminate\Pagination\Paginator;

use GuzzleHttp\Client;
use Validator;

class RencanaKinerjaController extends Controller
{














    public function store(Request $request)
    {



        $messages = [
            'renjaId.required'         => 'Harus diisi',
            'label.required'            => 'Harus diisi',
            'parentId.required'        => 'Harus diisi',

        ];



        $validator = Validator::make(
            $request->all(),
            [
                'renjaId'              => 'required',
                'label'                 => 'required',
                'parentId'             => 'required',

            ],
            $messages
        );

        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }


        $ah    = new TimKerja;
        $ah->renja_id        = $request->renjaId;
        $ah->label           = $request->label;
        $ah->parent_id       = $request->parentId;

        if ($ah->save()) {
            $data = array(
                        'id'        => $ah->id,
                        'label'     => $ah->label,
                        'child'     => "[]",
                        'renja_id'  => $ah->renja_id,
                    );

            return \Response::make($data, 200);
        } else {
            return \Response::make('error', 500);
        }
    }


    public function destroy(Request $request)
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


        $sr    = TimKerja::find($request->id);
        if (is_null($sr)) {
            return $this->sendError('Tim Kerja tidak ditemukan.');
        }
        if ( $sr->delete()){
            return \Response::make('sukses', 200);
        }else{
            return \Response::make('error', 500);
        }


    }





}
