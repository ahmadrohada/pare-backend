<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\MatriksPeran;
use App\Models\MatriksHasil;
use Psy\Command\WhereamiCommand;
use Validator;

use GuzzleHttp\Client;

class MatrikPeranHasilController extends Controller
{


    protected function list_jabatan($jabatan_atasan_id){
        //$token = env('SIMPEG_APP_TOKEN');
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5M2NlNGNhOS1iNDczLTRmMzctYmQzNC0xYTAzYzVjNjFlNTgiLCJqdGkiOiI1Y2ZmYjcwNDgzNmVkZDMzZGM2MzlmNjYyN2RlNzRhNTkyY2I0OWU0NGJlNjEwZjY5MDBlYzRiZGFiZWZmNjMyNTliMTBkZmY4MjIzMmUxNSIsImlhdCI6MTYyNzgyNDU4NSwibmJmIjoxNjI3ODI0NTg1LCJleHAiOjE2NTkzNjA1ODUsInN1YiI6IiIsInNjb3BlcyI6WyIqIl19.GlE2yf4WJDtYvkThAjLYp37qxzN0NLqcS05YHFaMrQre4sY1izm1mVgO9Y-yCDasTz_0iZNrBOz1vMbw_PxsFxV9cBdPjIhuepcnk4vmN-iSe2fn0NP5rR3l2S-ZQmaT7HmPoABEcgGpTbKx_nOa43I6Y2UIzZsxPxQlDaRqfuSMBAtjm7QreGeD23K0CQQ4BMT3Nxe0iwqOwrYCjYTmu2m4JAhjmWN7amcJ95p_RdyrG0i-L9C-vGK1rO8LGtkAm4JjUmZLpGELjMpYMhIO11h2ok-TcBqaAJH_l92izQ5SHoR4UGmsyUiIygCmr8BD541zEOr4g6ITgk729lmXB_8faR0BQsbiGPA3NKq8YrHiJA63DP-iWmglE_QS4KTgSej6oU_5IM77kSXV90HTgPgzDTGgnDhtxWObcKTvCFsFlMR_aOePYEPTJj5qUIvy2lGgFJODwnr25Fc2m043tRDL_oQqBNXAl5rhTvjQxOWNylTqJw0rceuBCOOQZxPTw5L5OdOciecMfeRw3-E4dhPF7aWHwAdY4U71VAv78ACVG5NO2q4pxAoRBKEtS2pv0TgoAbkM3D-bH-C5b4XgUpylFUzIZlx4CN29FoMlwbuEbqi1hMUPu_GXOGXORXAzGHFoOIQAf8KHlO09RrQ2LUnB6-xPSvAJ-LbYZEwBGHg";
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
            $response = $client->request('GET', '/api/sotk/jabatan?id_jabatan_atasan='.$jabatan_atasan_id,[
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
            return \Response::make(['message' => "Terjadi Kesalahan , SIM ASN not response"], 500);
        }
    }

    protected function detail_jabatan($id_jabatan){
        //$token = env('SIMPEG_APP_TOKEN');
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5M2NlNGNhOS1iNDczLTRmMzctYmQzNC0xYTAzYzVjNjFlNTgiLCJqdGkiOiI1Y2ZmYjcwNDgzNmVkZDMzZGM2MzlmNjYyN2RlNzRhNTkyY2I0OWU0NGJlNjEwZjY5MDBlYzRiZGFiZWZmNjMyNTliMTBkZmY4MjIzMmUxNSIsImlhdCI6MTYyNzgyNDU4NSwibmJmIjoxNjI3ODI0NTg1LCJleHAiOjE2NTkzNjA1ODUsInN1YiI6IiIsInNjb3BlcyI6WyIqIl19.GlE2yf4WJDtYvkThAjLYp37qxzN0NLqcS05YHFaMrQre4sY1izm1mVgO9Y-yCDasTz_0iZNrBOz1vMbw_PxsFxV9cBdPjIhuepcnk4vmN-iSe2fn0NP5rR3l2S-ZQmaT7HmPoABEcgGpTbKx_nOa43I6Y2UIzZsxPxQlDaRqfuSMBAtjm7QreGeD23K0CQQ4BMT3Nxe0iwqOwrYCjYTmu2m4JAhjmWN7amcJ95p_RdyrG0i-L9C-vGK1rO8LGtkAm4JjUmZLpGELjMpYMhIO11h2ok-TcBqaAJH_l92izQ5SHoR4UGmsyUiIygCmr8BD541zEOr4g6ITgk729lmXB_8faR0BQsbiGPA3NKq8YrHiJA63DP-iWmglE_QS4KTgSej6oU_5IM77kSXV90HTgPgzDTGgnDhtxWObcKTvCFsFlMR_aOePYEPTJj5qUIvy2lGgFJODwnr25Fc2m043tRDL_oQqBNXAl5rhTvjQxOWNylTqJw0rceuBCOOQZxPTw5L5OdOciecMfeRw3-E4dhPF7aWHwAdY4U71VAv78ACVG5NO2q4pxAoRBKEtS2pv0TgoAbkM3D-bH-C5b4XgUpylFUzIZlx4CN29FoMlwbuEbqi1hMUPu_GXOGXORXAzGHFoOIQAf8KHlO09RrQ2LUnB6-xPSvAJ-LbYZEwBGHg";
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
            $response = $client->request('GET', '/api/sotk/jabatan/'.$id_jabatan,[
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

    protected function detail_skpd($id_skpd){
        //$token = env('SIMPEG_APP_TOKEN');
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5M2NlNGNhOS1iNDczLTRmMzctYmQzNC0xYTAzYzVjNjFlNTgiLCJqdGkiOiI1Y2ZmYjcwNDgzNmVkZDMzZGM2MzlmNjYyN2RlNzRhNTkyY2I0OWU0NGJlNjEwZjY5MDBlYzRiZGFiZWZmNjMyNTliMTBkZmY4MjIzMmUxNSIsImlhdCI6MTYyNzgyNDU4NSwibmJmIjoxNjI3ODI0NTg1LCJleHAiOjE2NTkzNjA1ODUsInN1YiI6IiIsInNjb3BlcyI6WyIqIl19.GlE2yf4WJDtYvkThAjLYp37qxzN0NLqcS05YHFaMrQre4sY1izm1mVgO9Y-yCDasTz_0iZNrBOz1vMbw_PxsFxV9cBdPjIhuepcnk4vmN-iSe2fn0NP5rR3l2S-ZQmaT7HmPoABEcgGpTbKx_nOa43I6Y2UIzZsxPxQlDaRqfuSMBAtjm7QreGeD23K0CQQ4BMT3Nxe0iwqOwrYCjYTmu2m4JAhjmWN7amcJ95p_RdyrG0i-L9C-vGK1rO8LGtkAm4JjUmZLpGELjMpYMhIO11h2ok-TcBqaAJH_l92izQ5SHoR4UGmsyUiIygCmr8BD541zEOr4g6ITgk729lmXB_8faR0BQsbiGPA3NKq8YrHiJA63DP-iWmglE_QS4KTgSej6oU_5IM77kSXV90HTgPgzDTGgnDhtxWObcKTvCFsFlMR_aOePYEPTJj5qUIvy2lGgFJODwnr25Fc2m043tRDL_oQqBNXAl5rhTvjQxOWNylTqJw0rceuBCOOQZxPTw5L5OdOciecMfeRw3-E4dhPF7aWHwAdY4U71VAv78ACVG5NO2q4pxAoRBKEtS2pv0TgoAbkM3D-bH-C5b4XgUpylFUzIZlx4CN29FoMlwbuEbqi1hMUPu_GXOGXORXAzGHFoOIQAf8KHlO09RrQ2LUnB6-xPSvAJ-LbYZEwBGHg";
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
            $response = $client->request('GET', '/api/sotk/skpd/'.$id_skpd,[
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


    public function koordinatorList(Request $request)
    {
        $skpd_id = $request->skpd_id;
        $periode = $request->periode;

        $koordinator = MatriksPeran::WHERE('role','=','koordinator')
                                    ->WHERE('periode','=',$periode)
                                    ->WHERE('skpd_id','=',$skpd_id)
                                    ->SELECT(   'id',
                                                'role',
                                                'jabatan->id AS id_jabatan',
                                                'jabatan->nama_lengkap AS jabatan',
                                                'level'
                                            )
                                    ->ORDERBY('jabatan->id','ASC')
                                    ->get();


        $response['role'] = array();
        $no = 1 ;
        foreach( $koordinator AS $x ){

            //KOORDINATOR
            $i['id']                = $x->id;
            $i['role']              = strtoupper($x->role).' '.$no;
            $i['id_jabatan']        = $x->id_jabatan;
            $i['jabatan']           = $x->jabatan;
            $i['level']             = $x->level;
            array_push($response['role'], $i);
            $no+=1;
        }

        return [
            'koordinatorList'     => $response['role'],
        ];

    }

    public function ListJabatan(Request $request)
    {

        $skpd_id = $request->skpd_id;
        $periode = $request->periode;

        //cari id jabatan atasan di sotk
        $skpd = $this::detail_skpd($skpd_id);
        if ( $skpd == null ) {
            return response()->json(['errors' => "Jabatan tidak ditemukan"], 422);
        }else{
            $jabatan_atasan_id = $skpd['id_jabatan_kepala'];
        }

        if ( !isset($request->role) ){
            //nyari jneis jabatan atasan
            $jj_atasan = MatriksPeran::WHERE('jabatan->id','=',$jabatan_atasan_id)->SELECT('role')->first();

            switch ($jj_atasan->role) {
                case "koordinator":
                    $role =  "ketua";
                    $level =  "S3";
                    break;
                case "ketua":
                    $role =  "anggota";
                    $level =  "S4";
                    break;
                case "anggota":
                    $role = null ;
                    break;
            }
        }else{
            $role = $request->role;
            $level =  "S2";
        }


         //get list jabatan yang sudah terdaftar pada matrik peran
         $existing_list = MatriksPeran:: WHERE('jabatan->id_jabatan_atasan','=',$jabatan_atasan_id)
                                        ->WHERE('periode','=',$periode)
                                        ->WHERE('skpd_id','=',$skpd_id)
                                        ->SELECT('jabatan->id AS id')
                                        ->get();
                                $response['existing_list'] = array();
                                foreach( $existing_list AS $x ){
                                array_push($response['existing_list'], $x->id);
                                }



        //get list jabatan from SOTK
        $list_jabatan_sotk     = $this::list_jabatan($jabatan_atasan_id);

        //list jabatan dikurangi list jabatan yang sudah ada di role matrik
        $response['role'] = array();
        foreach( $list_jabatan_sotk AS $x ){
            if (!in_array($x['id'], $response['existing_list'])) {
                //KOORDINATOR
                $i['id']                = $x['id'];
                $i['singkatan']         = $x['singkatan'];
                $i['nama_lengkap']      = $x['nama_lengkap'];
                array_push($response['role'], $i);
            }

        }


        return [
            'list_jabatan'          => $response['role'],
            'existing_list'         => $response['existing_list'],
            'role'                  => $role,
            'level'                 => $level
        ];
    }

    public function ListJabatanAtasan(Request $request)
    {

        $jabatan_atasan_id = $request->role_id;
        $skpd_id = $request->skpd_id;
        $periode = $request->periode;

        //existing jabatan
        //get list jabatan yang sudah terdaftar pada matrik peran
        $existing = MatriksPeran::WHERE('periode','=',$periode)
                                    ->WHERE('skpd_id','=',$skpd_id)
                                    ->where(function ($query) use($jabatan_atasan_id){
                                        $query->WHERE('id','=',$jabatan_atasan_id)
                                              ->orWhere('parent_id', '=', $jabatan_atasan_id);
                                    })
                                    ->SELECT(   'jabatan->id AS id')
                                    ->get();
        $response['existing'] = array();
        foreach( $existing AS $x ){
            array_push($response['existing'], $x->id);
        }

        $jabatan_atasan_id = MatriksPeran::SELECT('jabatan->id AS jabatan_id')->WHERE('id','=',$jabatan_atasan_id)->first();
        $jabatan_atasan_id = $jabatan_atasan_id->jabatan_id;


        //get detail jabatan pribadi from SOTK
        $jabatan_self     = $this::detail_jabatan($jabatan_atasan_id);
        //get list bawahan from SOTK
        $list_jabatan_sotk     = $this::list_jabatan($jabatan_atasan_id);
        array_push($list_jabatan_sotk, $jabatan_self);
        $list_jabatan_sotk = collect($list_jabatan_sotk)->sortBy('id')->toArray();

        //list jabatan yang muncul hanya yang sudah ada di matrik peran hasil

        $response['role'] = array();
        foreach( $list_jabatan_sotk AS $x ){
            if ( ($x['is_jabatan_kepala'] === true) & (in_array($x['id'], $response['existing'])) ) {
                //JABATAN LIST
                $i['id']                = $x['id'];
                $i['singkatan']         = $x['singkatan'];
                $i['nama_lengkap']      = $x['nama_lengkap'];
                array_push($response['role'], $i);
            }
        }


        return [
            'list_jabatan'          => $response['role'],
        ];
    }


    public function ListOutcomeAtasan(Request $request)
    {

        $role_id = $request->role_id;

        //cari parent nya
        $dt = MatriksPeran::WHERE('id','=',$role_id)
                                    ->SELECT('parent_id AS id')
                                    ->first();
        if ($dt){
            $parent_id = $dt->id;
        }

        $response = array();
        $response['outcomeAtasan'] = array();

        $data = MatriksHasil::WHERE('matriks_peran_id',$parent_id)->get();
        foreach( $data AS $y ){
            $r['id']            = $y->id;
            $r['label']         = $y->label;

            array_push($response['outcomeAtasan'], $r);
        }
        return $response;


    }


    public function List(Request $request)
    {

        $skpd_id = $request->skpd_id;
        $periode = $request->periode;
        $koordinator_id = $request->koordinator_id ? $request->koordinator_id : null ;


        $koordinator = MatriksPeran::WHERE('role','=','koordinator')
                                ->WHERE('periode','=',$periode)
                                ->WHERE('skpd_id','=',$skpd_id)
                                ->SELECT(   'id',
                                            'role',
                                            'jabatan->id AS id_jabatan',
                                            'jabatan->nama_lengkap AS jabatan',
                                            'level',
                                            'skpd_id',
                                            'periode'
                                        )
                                ->ORDERBY('jabatan->id','ASC');

        if( $koordinator_id != null ){
            $koordinator->where(function( $query ) use ( $koordinator_id ){
                    $query->where('id', '=', $koordinator_id );
            });
        }
        $koordinator = $koordinator->get();



//=======================================================================================================//
//=================================== MATRIK ROLE / PERAN ===============================================//
//=======================================================================================================//

        $response['role'] = array();
        $no = 1 ;

        $array_style = array('style4','style5','style6','style1','style2','style3');
        foreach( $koordinator AS $x ){
            $row_style = $array_style[rand(0, count($array_style) - 3)];
            //KOORDINATOR
            $i['id']                = $x->id;
            $i['role']              = ( $koordinator_id != null ) ? strtoupper($x->role) : strtoupper($x->role).' '.$no;
            $i['id_jabatan']        = $x->id_jabatan;
            $i['jabatan']           = $x->jabatan;
            $i['skpd_id']           = $x->skpd_id;
            $i['periode']           = $x->periode;
            $i['level']             = $x->level;
            $i['row_style']         = $row_style;
            array_push($response['role'], $i);

            $ketua = MatriksPeran::WHERE('parent_id','=',$x->id)
                                ->SELECT(   'id',
                                            'role',
                                            'jabatan->id AS id_jabatan',
                                            'jabatan->nama_lengkap AS jabatan',
                                            'level',
                                            'skpd_id',
                                            'periode'
                                        )
                                ->ORDERBY('jabatan->id','ASC')
                                ->GET();
            $s_no = 1 ;
            foreach( $ketua AS $y ){
                //KETUA
                $i['id']                = $y->id;
                $i['role']              = strtoupper($y->role).' '.$no.'.'.$s_no;
                $i['id_jabatan']        = $y->id_jabatan;
                $i['jabatan']           = $y->jabatan;
                $i['level']             = $y->level;
                $i['periode']           = $y->periode;
                $i['skpd_id']           = $y->skpd_id;
                $i['row_style']         = $row_style;
                array_push($response['role'], $i);
                $s_no+=1;
            }

            //NYARI ANGGOTA
            $ss_no = 1 ;
            foreach( $ketua AS $y ){
                $anggota = MatriksPeran::WHERE('parent_id','=',$y->id)
                                ->SELECT(   'id',
                                            'role',
                                            'jabatan->id AS id_jabatan',
                                            'jabatan->nama_lengkap AS jabatan',
                                            'level',
                                            'skpd_id',
                                            'periode'
                                        )
                                ->ORDERBY('jabatan->id','ASC')
                                ->GET();
                //ANGGOTA
                $sss_no = 1 ;
                foreach( $anggota AS $z ){
                    //KETUA
                    $i['id']                = $z->id;
                    $i['role']              = strtoupper($z->role).' '.$no.'.'.$ss_no.'.'.$sss_no;
                    $i['id_jabatan']        = $z->id_jabatan;
                    $i['jabatan']           = $z->jabatan;
                    $i['level']             = $z->level;
                    $i['periode']           = $z->periode;
                    $i['skpd_id']           = $z->skpd_id;
                    $i['row_style']         = $row_style;
                    array_push($response['role'], $i);
                    $sss_no+=1;
                }

                $ss_no+=1;
            }

            $no+=1;
        }

//=======================================================================================================//
//=============================== END OF MATRIK ROLE / PERAN =============================================//
//=======================================================================================================//



//=======================================================================================================//
//===============================      OUTCOME HEADER      =============================================//
//=======================================================================================================//

        //for jumlah kolom outcome S2 ( Koordinator )
        $s_2 = MatriksHasil::WHERE('level','=','S2')
                                ->WHERE('periode','=',$periode)
                                ->WHERE('skpd_id','=',$skpd_id)
                                ->WHERE('matriks_peran_id','=',$koordinator_id)
                                ->whereNull('parent_id')
                                ->SELECT(   'id',
                                            'label',
                                            'jumlah_kolom'
                                        )
                                ->GET();

        $response['sasaran_strategis'] = array();
        //S2
        foreach( $s_2 AS $m ){
            for ($x = 1; $x <= $m->jumlah_kolom; $x++) {
                $k['id']           = $m->id;
                $k['label']        = $m->label;
                array_push($response['sasaran_strategis'], $k);
            }
        }



        //MATRIKS PERAN DAN HASIL
        $response['data'] = array();
        $response['outcome'] = array();


//=======================================================================================================//
//============================    END OF   OUTCOME HEADER      ==========================================//
//=======================================================================================================//



        $response['last_data'] = $response['sasaran_strategis'];

        foreach( $response['role'] AS $a ){
            $role_level = $a['level'];

            //BARIS PERTAMA
            if ($role_level == "S2"){
                $response['outcome'] = $response['sasaran_strategis'];
            }else{


            //BARIS SELANJUTNYA
            //MENCARAI DATA OUTCOME nYA
            $last_id = null ;
            foreach( $response['last_data'] AS $ot ){

                if ( $last_id != $ot['id']){
                    $outcome = MatriksHasil::WHERE('level','=',$role_level)
                                        ->WHERE('parent_id','=',$ot['id'])
                                        ->WHERE('matriks_peran_id','=',$a['id'])
                                        ->SELECT('id','label','level','jumlah_kolom')
                                        ->get();
                    if (!$outcome->isEmpty()){
                        foreach( $outcome AS $od ){
                            for ($x = 1; $x <= $od->jumlah_kolom; $x++) {
                                $j['id']           = $od->id;
                                $j['label']        = $od->label;
                                array_push($response['outcome'], $j);
                            }

                        }
                    }else{
                        $j['id']           = $ot['id'];
                        $j['label']        = "";
                        array_push($response['outcome'], $j);
                    }
                    $last_id = $ot['id'];
                }else{
                    $j['id']           = $ot['id'];
                    $j['label']        = "";
                    array_push($response['outcome'], $j);
                    $last_id = $ot['id'];
                }




            }

        }


            $k['id']                = $a['id'];
            $k['role']              = $a['role'];
            $k['id_jabatan']        = $a['id_jabatan'];
            $k['jabatan']           = $a['jabatan'];
            $k['level']             = $a['level'];
            $k['skpd_id']           = $a['skpd_id'];
            $k['periode']           = $a['periode'];
            $k['row_style']         = $a['row_style'];
            $k['outcome']           = $response['outcome'];

            array_push($response['data'], $k);

            $response['last_data'] = $response['outcome'];
            $response['outcome'] = array();
        }



        return [

            'sasaran_strategis'     => $response['sasaran_strategis'],
            'matriks'               => $response['data'],
        ];

    }

    public function jabatanStore(Request $request)
    {
        $messages = [

            'skpdId.required'               => 'Harus diisi',
            'periode.required'              => 'Harus diisi',

        ];



        $validator = Validator::make(
            $request->all(),
            [

                'skpdId'                => 'required',
                'periode'               => 'required',
            ],
            $messages
        );

        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }

        //nyari ID parent nya
        $parent = MatriksPeran::SELECT('id')
                                ->WHERE('periode','=',$request->periode)
                                ->WHERE('skpd_id','=',$request->skpdId)
                                ->WHERE('jabatan->id','=',$request->parentId)
                                ->first();
        if ($parent){
            $parent_id = $parent->id;
        }else{
            $parent_id = null;
        }



        $selectedRoles = $request->selectedRoles ;
        $no = 0 ;

        foreach( $selectedRoles AS $x ){
            //cari detail jabatan from simASN
            $jabatan     = $this::detail_jabatan($x['id']);

            $rp    = new MatriksPeran;
            $rp->skpd_id             = $request->skpdId;
            $rp->periode             = $request->periode;
            $rp->role                = $request->role;
            $rp->level               = $request->level;
            $rp->parent_id           = $parent_id;
            $rp->jabatan             = json_encode($jabatan);

            if ( $jabatan != null ){
                $rp->save();
                $no++;
            }
        }

        if ( $no > 0 ){
            return \Response::make($no. "data berhasil tersimpan", 200);
        }else{
            return \Response::make("data tidak berhasil tersimpan", 400);
        }


    }


    public function hasilStore(Request $request)
    {
        $messages = [

            'skpdId.required'             => 'Harus diisi',
            'periode.required'            => 'Harus diisi',
            'roleId.required'             => 'Harus diisi',
            'level.required'              => 'Harus diisi',
            'outcomeLabel.required'       => 'Harus diisi',

        ];



        $validator = Validator::make(
            $request->all(),
            [

                'skpdId'              => 'required',
                'periode'             => 'required',
                'roleId'              => 'required',
                'level'               => 'required',
                'outcomeLabel'        => 'required',
            ],
            $messages
        );

        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }


        $rp    = new MatriksHasil;
        $rp->skpd_id             = $request->skpdId;
        $rp->periode             = $request->periode;
        $rp->matriks_peran_id    = $request->roleId;
        $rp->level               = $request->level;
        $rp->label               = $request->outcomeLabel;
        $rp->parent_id           = $request->outcomeAtasanId;
        $rp->jumlah_kolom        = 1;




        if ( $rp->save() ){

            if ( $request->level != "S2"){
                //AUPDATE jumlah kolom parent nya
                $count = MatriksHasil::WHERE('parent_id','=',$request->outcomeAtasanId)->count();
                $update  = MatriksHasil::find($request->outcomeAtasanId);
                $update->jumlah_kolom   = $count;
                $update->save();
            }

            return \Response::make(" data berhasil tersimpan", 200);
        }else{
            return \Response::make("data tidak berhasil tersimpan", 400);
        }


    }


}
