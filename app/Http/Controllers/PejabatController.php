<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RenjaPejabat;
use App\Models\User;

use Illuminate\Pagination\Paginator;

use GuzzleHttp\Client;
use Validator;

class PejabatController extends Controller
{

     //MENDAPATKAN detail PEGAWAI from sim-ASN
     protected function detail_pegawai($nip){
        $token = env('SIMPEG_APP_TOKEN');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        try{
            $client = new Client([
                'base_uri' => 'https://api.sim-asn.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('GET', '/api/pegawai/'.$nip,[
                'headers' => $headers
            ]);
            $body = $response->getBody();
            $arr_body = json_decode($body,true);
            if ( isset($arr_body['data']) && ($arr_body['data'] != null) ){
                return $arr_body['data'];
            }else{
                return null;
            }

        }catch(\GuzzleHttp\Exception\GuzzleException $e) {
            return null;
        }
    }



    public function list(Request $request)
    {
        $tim_kerja_id = ($request->tim_kerja_id)? $request->tim_kerja_id : 1 ;

        $query = RenjaPejabat::SELECT(
                                    'id',
                                    'nama_lengkap',
                                    'jabatan',
                                    'pegawai->nip AS nip',
                                    'pegawai->photo AS photo'
                                )
                                ->WHERE('tim_kerja_id','=',$tim_kerja_id)
                                ->GET();


        $response = array();
        foreach( $query AS $x ){
            $h['id']                = $x->id;
            $h['nama_lengkap']      = $x->nama_lengkap;
            $h['jabatan']           = $x->jabatan;
            $h['nip']               = $x->nip;
            $h['photo']             = $x->photo;



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
                $h['parent_id']     = $x->parent_id;
                $h['renja_id']      = $x->renja_id;
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


    public function store(Request $request)
    {

        $messages = [
            'timKerjaLabel.required'    => 'Harus diisi',
            'timKerjaId.required'       => 'Harus diisi',
            'renjaId.required'          => 'Harus diisi',
            'userId.required'           => 'Harus diisi',
            'jabatanId.required'        => 'Harus diisi',
            'namaPejabat.required'      => 'Harus diisi',

        ];



        $validator = Validator::make(
            $request->all(),
            [
                'timKerjaLabel'     => 'required',
                'timKerjaId'        => 'required',
                'renjaId'           => 'required',
                'renjaId'           => 'required',
                'userId'            => 'required',
                'jabatanId'         => 'required',
                'namaPejabat'       => 'required',

            ],
            $messages
        );

        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }

        //cari  detail pejabat dan jabatan pada SIM-ASN
        $query =  User::WHERE('id','=',$request->userId)->first();

        $simpeg = $this::detail_pegawai($query->nip);

        //DETAIL PEGAWAI
        $pegawai_detail = array(
            'user_id'       => $request->userId,
            'simpeg_id'     => $simpeg['nip'],
            'jenis'         => $simpeg['jenis'],
            'pns_id'        => $simpeg['pns_id'],
            'nip_lama'      => $simpeg['nip_lama'],
            'nip'           => $simpeg['nip'],
            'photo'         => $simpeg['photo'],

            'nama_lengkap'  => $simpeg['nama_lengkap'],

        );

        //DETAIL JABATAN
        $jabatan_detail = array();
        $jabatan = "";
        foreach( $simpeg['jabatan'] AS $x ){
            if($x['id'] == $request->jabatanId){
                $jabatan_detail = $x;
                $jabatan = $x['nama'];
            }
        }


        $rp    = new RenjaPejabat;
        $rp->user_id             = $request->userId;
        $rp->tim_kerja_id        = $request->timKerjaId;
        $rp->nama_lengkap        = $simpeg['nama_lengkap'];
        $rp->nip                 = $simpeg['nip'];
        $rp->jabatan             = $jabatan;
        $rp->pegawai_detail      = json_encode($pegawai_detail);
        $rp->jabatan_detail      = json_encode($jabatan_detail);

        if ($rp->save()) {
            return \Response::make('sukses', 200);
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


        $sr    = RenjaPejabat::find($request->id);
        $tim_kerja_id = $sr->tim_kerja_id;
        $data = array(
            'tim_kerja_id' => $tim_kerja_id,
        );

        if (is_null($sr)) {
            return $this->sendError('Pejabat Tim Kerja tidak ditemukan.');
        }
        if ( $sr->delete()){
            return \Response::make($data, 200);
        }else{
            return \Response::make('error', 500);
        }


    }


}
