<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimKerja;
use App\Models\RenjaPejabat;
use App\Models\RencanaKinerja;

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
                                                'perjanjian_kinerja_id'
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
                                    ->get();



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
                                    WHERE('label','=','KETUA')
                                    ->SELECT(
                                                'id',
                                                'label',
                                                'parent_id',
                                                'perjanjian_kinerja_id'
                                            );
        if($renja_id != null ) {
            $query->where('perjanjian_kinerja_id','=', $renja_id );
        }

        $response = array();
        foreach( $query->get() AS $x ){
            $h['id']            = $x->id;
            $h['label']         = $x->label;
            $h['parent_id']     = $x->parent_id;
            $h['perjanjian_kinerja_id']      = $x->renja_id;
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

    public function self(Request $request)
    {
        $renja_id = ($request->renja_id)? $request->renja_id : null ;

        $query = TimKerja::
                                    WHERE('id','=',$request->id)
                                    ->SELECT(
                                                'id',
                                                'label',
                                                'parent_id',
                                                'perjanjian_kinerja_id'
                                            );
        if($renja_id != null ) {
            $query->where('perjanjian_kinerja_id','=', $renja_id );
        }

        $response = array();
        foreach( $query->get() AS $x ){
            $h['id']            = $x->id;
            $h['label']         = $x->label;
            $h['parent_id']     = $x->parent_id;
            $h['perjanjian_kinerja_id']      = $x->renja_id;
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
                                        'perjanjian_kinerja_id'
                                    )
                                    ->get();
        $response = array();
        foreach( $query AS $x ){
                $h['id']            = $x->id;
                $h['label']         = $x->label;
                $h['perjanjian_kinerja_id']      = $x->renja_id;
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
                                        'perjanjian_kinerja_id'
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
            'perjanjianKinerjaId.required'         => 'Harus diisi',
            'label.required'            => 'Harus diisi',
            'parentId.required'        => 'Harus diisi',

        ];



        $validator = Validator::make(
            $request->all(),
            [
                'perjanjianKinerjaId'              => 'required',
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
        $ah->perjanjian_kinerja_id        = $request->perjanjianKinerjaId;
        $ah->label           = $request->label;
        $ah->parent_id       = $request->parentId;

        if ($ah->save()) {
            $data = array(
                        'id'        => $ah->id,
                        'label'     => $ah->label,
                        'child'     => null,
                        'perjanjian_kinerja_id'  => $ah->perjanjian_kinerja_id,
                        'anggota'   => TimKerja::WHERE('id','=',$ah->id)->WHERE('label','LIKE','ANGGOTA%')->exists(),
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
            return response()->json(['message'=>'Tim Kerja tidak ditemukan'],422);
        }
        //tim kerja yg bisa dihapus adalah yg tidak punya child dan tidak memiliki rencana kinerja
        if (TimKerja::where('parent_id', '=',$request->id)->exists()) {
            return \Response::make(['message'=>'Tidak dapat menghapus tim kerja yang masih memiliki bawahan'],422);
        }
        if (RencanaKinerja::where('tim_kerja_id', '=',$request->id)->exists()) {
            return \Response::make(['message'=>'Tidak dapat menghapus tim kerja yang sudah memiliki Rencana Kinerja'],422);
        }



        if ( $sr->delete()){
            return \Response::make(['message'=>'Sukses'], 200);
        }else{
            return \Response::make(['message'=>'Error'], 500);
        }


    }





}
