<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimKerja;
use App\Models\RenjaPejabat;
use App\Models\RencanaKinerja;

use Illuminate\Pagination\Paginator;

use GuzzleHttp\Client;
use Validator;

class TimKerjaController extends Controller
{
    protected function change_romawi($number){

        switch ($number) {
            case '0': $data = "I" ;
                break;
            case '1': $data = "II" ;
                break;
            case '2': $data = "III" ;
                break;
            case '3': $data = "IV" ;
                break;
            case '4': $data = "V" ;
                break;
            case '5': $data = "VI" ;
                break;
            case '6': $data = "VII" ;
                break;
            case '7': $data = "VIII" ;
                break;
            case '8': $data = "IX" ;
                break;
            case '9': $data = "X" ;
                break;
            default : $data = "";
        }
        return $data;

    }

    public function tim_kerja(Request $request)
    {

        $response = array();
        $response['pejabat'] = array();
        $response['rencana_kinerja'] = array();


        $tim_kerja = TimKerja::
                                    WHERE('id','=',$request->id)
                                    ->SELECT(
                                                'id',
                                                'label',
                                                'parent_id',
                                                'renja_id'
                                            )
                                    ->first();
        $response['tim_kerja'] = $tim_kerja;

        $query = RenjaPejabat::SELECT(
                                        'id',
                                        'user_id',
                                        'nama_lengkap',
                                        'jabatan',
                                        'nip',
                                        'pegawai_detail->photo AS photo'
                                    )
                                    ->WHERE('tim_kerja_id','=',$request->id)
                                    ->GET();



        foreach( $query AS $x ){
            $h['id']                = $x->id;
            $h['nama_lengkap']      = $x->nama_lengkap;
            $h['jabatan']           = $x->jabatan;
            $h['nip']               = $x->nip;
            $h['photo']             = $x->photo;

            array_push($response['pejabat'], $h);
        }

        $query2 = RencanaKinerja::with('TimKerja')
                                    ->SELECT(
                                        'id',
                                        'label',
                                        'parent_id',
                                        'added_by',
                                        'tim_kerja_id'
                                    )
                                    ->WHERE('tim_kerja_id','=',$request->id)
                                    ->GET();



        foreach( $query2 AS $y ){
            $r['id']            = $y->id;
            $r['label']         = $y->label;
            $r['parent_id']     = $y->parent_id;
            $r['child_count']   = RencanaKinerja::WHERE('parent_id','=',$y->id)->count();
            $r['added_by']      = $y->added_by;
            $r['tim_kerja']     = $y->TimKerja;

            array_push($response['rencana_kinerja'], $r);
        }


        return $response;

    }

    public function tim_kerja_level_0(Request $request)
    {
        $renja_id = ($request->renja_id)? $request->renja_id : null ;

        $query = TimKerja::
                                    WHERE('parent_id','=','0')
                                    ->SELECT(
                                                'id',
                                                'label',
                                                'parent_id',
                                                'renja_id'
                                            );
        if($renja_id != null ) {
            $query->where('renja_id','=', $renja_id );
        }

        $response = array();
        foreach( $query->get() AS $x ){
            $h['id']            = $x->id;
            $h['label']         = $x->label;
            $h['parent_id']     = $x->parent_id;
            $h['renja_id']      = $x->renja_id;
            $h['anggota']       = TimKerja::WHERE('id','=',$x->id)->WHERE('label','LIKE','ANGGOTA%')->exists();

            //cek apakah node ini memiliki child atau tidak
            $child = TimKerja::WHERE('parent_id','=',$x->id)->exists();
            if ( $child ){
                $h['leaf']      = false;
            }else{
                $h['leaf']      = true;
            }


            array_push($response, $h);
        }

        $response = collect($response)->sortBy('id')->values();

        return $response;
    }

    public function child(Request $request)
    {
        $query = TimKerja::
                                    WHERE('parent_id','=',$request->parent_id)
                                    ->SELECT(
                                        'id',
                                        'label',
                                        'parent_id',
                                        'renja_id'
                                    )
                                    ->get();
        $response = array();
        foreach( $query AS $x ){
                $h['id']            = $x->id;
                $h['label']         = $x->label;
                $h['renja_id']      = $x->renja_id;
                $h['parent_id']     = $x->parent_id;
                $h['anggota']       = TimKerja::WHERE('id','=',$x->id)->WHERE('label','LIKE','ANGGOTA%')->exists();
                //cek apakah node ini memiliki child atau tidak
                $child = TimKerja::WHERE('parent_id','=',$x->id)->exists();
                if ( $child ){
                    $h['leaf']      = false;
                }else{
                    $h['leaf']      = true;
                }
                array_push($response, $h);
        }
        $response = collect($response)->sortBy('label')->values();
        return $response;
    }


    public function add_tim_kerja_referensi(Request $request)
    {
        $query = TimKerja::
                                    WHERE('id','=',$request->id)
                                    ->SELECT(
                                        'id',
                                        'label',
                                        'parent_id',
                                        'renja_id'
                                    )
                                    ->first();

        $child_koordinator = TimKerja::WHERE('parent_id','=',$request->id)->WHERE('label','LIKE',"KOORDINATOR%")->count();
        $child_sub_koordinator = TimKerja::WHERE('parent_id','=',$request->id)->WHERE('label','LIKE',"SUB KOORDINATOR%")->count();
        $child_anggota = TimKerja::WHERE('parent_id','=',$request->id)->WHERE('label','LIKE',"ANGGOTA%")->count();

        $child_ref = array();
        $h['value']         = "KOORDINATOR ".$this::change_romawi($child_koordinator);
        $h['label']         = "KOORDINATOR ".$this::change_romawi($child_koordinator);
        array_push($child_ref, $h);
        $h['value']         = "SUB KOORDINATOR ".$this::change_romawi($child_sub_koordinator);
        $h['label']         = "SUB KOORDINATOR ".$this::change_romawi($child_sub_koordinator);
        array_push($child_ref, $h);
        $h['value']         = "ANGGOTA ".$this::change_romawi($child_anggota);
        $h['label']         = "ANGGOTA ".$this::change_romawi($child_anggota);
        array_push($child_ref, $h);

        return [
            'tim_kerja' => $query,
            'child_ref' => $child_ref,
        ];
    }


    public function TimKerjaRencanaKinerjaParent(Request $request)
    {


        $data = TimKerja::with('RencanaKinerja')->WHERE('id',$request->parent_id)->first();
        return $data;


    }


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
