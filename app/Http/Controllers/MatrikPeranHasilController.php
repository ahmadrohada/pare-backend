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

        $jabatan_atasan_id = 1931;
        $skpd_id = $request->skpd_id;
        $periode = $request->periode;

        //get list jabatan yang sudah terdaftar pada matrik peran
        $koordinator_list = MatriksPeran::WHERE('role','=','koordinator')
                                    ->WHERE('periode','=',$periode)
                                    ->WHERE('skpd_id','=',$skpd_id)
                                    ->SELECT(   'jabatan->id AS id')
                                    ->get();
        $response['koordinator_list'] = array();
        foreach( $koordinator_list AS $x ){
            array_push($response['koordinator_list'], $x->id);
        }




        //get list jabatan from SOTK
        $list_jabatan_sotk     = $this::list_jabatan($jabatan_atasan_id);

        //list jabatan dikurangi list jabatan yang sudah ada di role matrik
        $response['role'] = array();
        foreach( $list_jabatan_sotk AS $x ){
            if (!in_array($x['id'], $response['koordinator_list'])) {
                //KOORDINATOR
                $i['id']                = $x['id'];
                $i['singkatan']         = $x['singkatan'];
                $i['nama_lengkap']      = $x['nama_lengkap'];
                array_push($response['role'], $i);
            }

        }


        return [
            'list_jabatan'          => $response['role'],
            'koordinator_list'      => $response['koordinator_list'],
        ];
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
                                            'level'
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

        $array_style = array('style1','style2','style3','style4','style5','style6');
        foreach( $koordinator AS $x ){
            $row_style = $array_style[rand(0, count($array_style) - 2)];
            //KOORDINATOR
            $i['id']                = $x->id;
            $i['role']              = strtoupper($x->role).' '.$no;
            $i['id_jabatan']        = $x->id_jabatan;
            $i['jabatan']           = $x->jabatan;
            $i['level']             = $x->level;
            $i['row_style']         = $row_style;
            array_push($response['role'], $i);

            $ketua = MatriksPeran::WHERE('parent_id','=',$x->id)
                                ->SELECT(   'id',
                                            'role',
                                            'jabatan->id AS id_jabatan',
                                            'jabatan->nama_lengkap AS jabatan',
                                            'level'
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
                                            'level'
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

        //for jumlah kolom outcome
        $s_0 = MatriksHasil::WHERE('level','=','S0')
                                ->WHERE('periode','=',$periode)
                                ->WHERE('skpd_id','=',$skpd_id)
                                ->whereNull('matriks_peran_id')
                                ->whereNull('parent_id')
                                ->SELECT(   'id',
                                            'label',
                                            'jumlah_kolom'
                                        )
                                ->GET();

        $response['sasaran_strategis_header'] = array();
        $response['sasaran_strategis'] = array();
        $response['sasaran_strategis_header_2'] = array();


        $no_ss_jpt = 1;
        foreach( $s_0 AS $m ){

            for ($x = 1; $x <= $m->jumlah_kolom; $x++) {

                //HEADER ATAS
                $kl['id']           = $m->id;
                $kl['label']        = "SASARAN STRATEGIS JPT  ". $no_ss_jpt;
                array_push($response['sasaran_strategis_header'], $kl);

                //HEADER TENGAH
                $k['id']           = $m->id;
                $k['label']        = $m->label;
                array_push($response['sasaran_strategis'], $k);

                //HEADER BAWAH
                $km['id']           = $m->id;
                $km['label']        = "INTERMEDIATE OUTCOME";
                array_push($response['sasaran_strategis_header_2'], $km);

            }
            $no_ss_jpt+=1;
        }



        //MATRIKS PERAN DAN HASIL
        $response['data'] = array();
        $response['outcome'] = array();


        //======================= HEADER MATRIKS PERAN HASIL ============================//
        $k['id']                = null;
        $k['role']              = "";
        $k['id_jabatan']        = "NAMA";
        $k['jabatan']           = "JABATAN";
        $k['row_style']         = "style0";
        $k['outcome']           = $response['sasaran_strategis_header'];
        array_push($response['data'], $k);
        $k['id']                = null;
        $k['role']              = "";
        $k['id_jabatan']        = "NAMA";
        $k['jabatan']           = "JABATAN";
        $k['row_style']         = "style0";
        $k['outcome']           = $response['sasaran_strategis'];
        array_push($response['data'], $k);
        $k['id']                = null;
        $k['role']              = "";
        $k['id_jabatan']        = "NAMA";
        $k['jabatan']           = "JABATAN";
        $k['row_style']         = "style0";
        $k['outcome']           = $response['sasaran_strategis_header_2'];
        array_push($response['data'], $k);
        //===============================================================================//


//=======================================================================================================//
//============================    END OF   OUTCOME HEADER      ==========================================//
//=======================================================================================================//



        $response['last_data'] = $response['sasaran_strategis'];


        foreach( $response['role'] AS $a ){
            $role_level = $a['level'];

            //MENCARAI DATA OUTCOME nYA
            $last_id = null ;
            foreach( $response['last_data'] AS $ot ){

                if ( $last_id != $ot['id']){
                    $outcome = MatriksHasil::WHERE('level','=',$role_level)
                                        ->WHERE('parent_id','=',$ot['id'])
                                        ->WHERE('matriks_peran_id','=',$a['id'])
                                        ->SELECT('id','label','level')
                                        ->get();
                    if (!$outcome->isEmpty()){
                        foreach( $outcome AS $od ){
                            $j['id']           = $od->id;
                            $j['label']        = $od->label;
                            array_push($response['outcome'], $j);
                        }
                    }else{
                        $j['id']           = $ot['id'];
                        $j['label']        = '-';
                        array_push($response['outcome'], $j);
                    }

                }else{
                    $j['id']           = $ot['id'];
                    $j['label']        = '-';
                    array_push($response['outcome'], $j);
                }

                $last_id = $ot['id'];


            }


            $k['id']                = $a['id'];
            $k['role']              = $a['role'];
            $k['id_jabatan']        = $a['id_jabatan'];
            $k['jabatan']           = $role_level.'-'.$a['jabatan'];
            $k['level']             = $a['level'];
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


        $selectedRoles = $request->selectedRoles ;
        $no = 0 ;

        //cari jabatannya kepala SKPD
        foreach( $selectedRoles AS $x ){
            //cari detail jabatan from simASN
            $jabatan     = $this::detail_jabatan($x['id']);

            $rp    = new MatriksPeran;
            $rp->skpd_id             = $request->skpdId;
            $rp->periode             = $request->periode;
            $rp->role                = "koordinator";
            $rp->level               = "S1";
            $rp->parent_id           = null;
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


}
