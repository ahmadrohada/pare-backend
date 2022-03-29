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


    protected function list_jabatan($jabatan_atasan_id)
    {
        //$token = env('SIMPEG_APP_TOKEN');
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5M2NlNGNhOS1iNDczLTRmMzctYmQzNC0xYTAzYzVjNjFlNTgiLCJqdGkiOiI1Y2ZmYjcwNDgzNmVkZDMzZGM2MzlmNjYyN2RlNzRhNTkyY2I0OWU0NGJlNjEwZjY5MDBlYzRiZGFiZWZmNjMyNTliMTBkZmY4MjIzMmUxNSIsImlhdCI6MTYyNzgyNDU4NSwibmJmIjoxNjI3ODI0NTg1LCJleHAiOjE2NTkzNjA1ODUsInN1YiI6IiIsInNjb3BlcyI6WyIqIl19.GlE2yf4WJDtYvkThAjLYp37qxzN0NLqcS05YHFaMrQre4sY1izm1mVgO9Y-yCDasTz_0iZNrBOz1vMbw_PxsFxV9cBdPjIhuepcnk4vmN-iSe2fn0NP5rR3l2S-ZQmaT7HmPoABEcgGpTbKx_nOa43I6Y2UIzZsxPxQlDaRqfuSMBAtjm7QreGeD23K0CQQ4BMT3Nxe0iwqOwrYCjYTmu2m4JAhjmWN7amcJ95p_RdyrG0i-L9C-vGK1rO8LGtkAm4JjUmZLpGELjMpYMhIO11h2ok-TcBqaAJH_l92izQ5SHoR4UGmsyUiIygCmr8BD541zEOr4g6ITgk729lmXB_8faR0BQsbiGPA3NKq8YrHiJA63DP-iWmglE_QS4KTgSej6oU_5IM77kSXV90HTgPgzDTGgnDhtxWObcKTvCFsFlMR_aOePYEPTJj5qUIvy2lGgFJODwnr25Fc2m043tRDL_oQqBNXAl5rhTvjQxOWNylTqJw0rceuBCOOQZxPTw5L5OdOciecMfeRw3-E4dhPF7aWHwAdY4U71VAv78ACVG5NO2q4pxAoRBKEtS2pv0TgoAbkM3D-bH-C5b4XgUpylFUzIZlx4CN29FoMlwbuEbqi1hMUPu_GXOGXORXAzGHFoOIQAf8KHlO09RrQ2LUnB6-xPSvAJ-LbYZEwBGHg";
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        try {
            $client = new Client([
                'base_uri' => 'https://api.sim-asn.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('GET', '/api/sotk/jabatan?id_jabatan_atasan=' . $jabatan_atasan_id, [
                'headers' => $headers
            ]);
            $body = $response->getBody();
            $arr_body = json_decode($body, true);
            if (isset($arr_body['data']) && ($arr_body['data'] != null)) {
                return $arr_body['data'];
            } else {
                return null;
            }
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return \Response::make(['message' => "Terjadi Kesalahan , SIM ASN not response"], 500);
        }
    }

    protected function detail_jabatan($id_jabatan)
    {
        //$token = env('SIMPEG_APP_TOKEN');
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5M2NlNGNhOS1iNDczLTRmMzctYmQzNC0xYTAzYzVjNjFlNTgiLCJqdGkiOiI1Y2ZmYjcwNDgzNmVkZDMzZGM2MzlmNjYyN2RlNzRhNTkyY2I0OWU0NGJlNjEwZjY5MDBlYzRiZGFiZWZmNjMyNTliMTBkZmY4MjIzMmUxNSIsImlhdCI6MTYyNzgyNDU4NSwibmJmIjoxNjI3ODI0NTg1LCJleHAiOjE2NTkzNjA1ODUsInN1YiI6IiIsInNjb3BlcyI6WyIqIl19.GlE2yf4WJDtYvkThAjLYp37qxzN0NLqcS05YHFaMrQre4sY1izm1mVgO9Y-yCDasTz_0iZNrBOz1vMbw_PxsFxV9cBdPjIhuepcnk4vmN-iSe2fn0NP5rR3l2S-ZQmaT7HmPoABEcgGpTbKx_nOa43I6Y2UIzZsxPxQlDaRqfuSMBAtjm7QreGeD23K0CQQ4BMT3Nxe0iwqOwrYCjYTmu2m4JAhjmWN7amcJ95p_RdyrG0i-L9C-vGK1rO8LGtkAm4JjUmZLpGELjMpYMhIO11h2ok-TcBqaAJH_l92izQ5SHoR4UGmsyUiIygCmr8BD541zEOr4g6ITgk729lmXB_8faR0BQsbiGPA3NKq8YrHiJA63DP-iWmglE_QS4KTgSej6oU_5IM77kSXV90HTgPgzDTGgnDhtxWObcKTvCFsFlMR_aOePYEPTJj5qUIvy2lGgFJODwnr25Fc2m043tRDL_oQqBNXAl5rhTvjQxOWNylTqJw0rceuBCOOQZxPTw5L5OdOciecMfeRw3-E4dhPF7aWHwAdY4U71VAv78ACVG5NO2q4pxAoRBKEtS2pv0TgoAbkM3D-bH-C5b4XgUpylFUzIZlx4CN29FoMlwbuEbqi1hMUPu_GXOGXORXAzGHFoOIQAf8KHlO09RrQ2LUnB6-xPSvAJ-LbYZEwBGHg";
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        try {
            $client = new Client([
                'base_uri' => 'https://api.sim-asn.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('GET', '/api/sotk/jabatan/' . $id_jabatan, [
                'headers' => $headers
            ]);
            $body = $response->getBody();
            $arr_body = json_decode($body, true);
            if (isset($arr_body['data']) && ($arr_body['data'] != null)) {
                return $arr_body['data'];
            } else {
                return null;
            }
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return null;
        }
    }

    protected function detail_skpd($id_skpd)
    {
        //$token = env('SIMPEG_APP_TOKEN');
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5M2NlNGNhOS1iNDczLTRmMzctYmQzNC0xYTAzYzVjNjFlNTgiLCJqdGkiOiI1Y2ZmYjcwNDgzNmVkZDMzZGM2MzlmNjYyN2RlNzRhNTkyY2I0OWU0NGJlNjEwZjY5MDBlYzRiZGFiZWZmNjMyNTliMTBkZmY4MjIzMmUxNSIsImlhdCI6MTYyNzgyNDU4NSwibmJmIjoxNjI3ODI0NTg1LCJleHAiOjE2NTkzNjA1ODUsInN1YiI6IiIsInNjb3BlcyI6WyIqIl19.GlE2yf4WJDtYvkThAjLYp37qxzN0NLqcS05YHFaMrQre4sY1izm1mVgO9Y-yCDasTz_0iZNrBOz1vMbw_PxsFxV9cBdPjIhuepcnk4vmN-iSe2fn0NP5rR3l2S-ZQmaT7HmPoABEcgGpTbKx_nOa43I6Y2UIzZsxPxQlDaRqfuSMBAtjm7QreGeD23K0CQQ4BMT3Nxe0iwqOwrYCjYTmu2m4JAhjmWN7amcJ95p_RdyrG0i-L9C-vGK1rO8LGtkAm4JjUmZLpGELjMpYMhIO11h2ok-TcBqaAJH_l92izQ5SHoR4UGmsyUiIygCmr8BD541zEOr4g6ITgk729lmXB_8faR0BQsbiGPA3NKq8YrHiJA63DP-iWmglE_QS4KTgSej6oU_5IM77kSXV90HTgPgzDTGgnDhtxWObcKTvCFsFlMR_aOePYEPTJj5qUIvy2lGgFJODwnr25Fc2m043tRDL_oQqBNXAl5rhTvjQxOWNylTqJw0rceuBCOOQZxPTw5L5OdOciecMfeRw3-E4dhPF7aWHwAdY4U71VAv78ACVG5NO2q4pxAoRBKEtS2pv0TgoAbkM3D-bH-C5b4XgUpylFUzIZlx4CN29FoMlwbuEbqi1hMUPu_GXOGXORXAzGHFoOIQAf8KHlO09RrQ2LUnB6-xPSvAJ-LbYZEwBGHg";
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        try {
            $client = new Client([
                'base_uri' => 'https://api.sim-asn.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('GET', '/api/sotk/skpd/' . $id_skpd, [
                'headers' => $headers
            ]);

            $body = $response->getBody();
            $arr_body = json_decode($body, true);
            if (isset($arr_body['data']) && ($arr_body['data'] != null)) {
                return $arr_body['data'];
            } else {
                return null;
            }
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return null;
        }
    }


    public function koordinatorList(Request $request)
    {
        $skpd_id = $request->skpd_id;
        $periode = $request->periode;

        $koordinator = MatriksPeran::WHERE('role', '=', 'koordinator')
            ->WHERE('periode', '=', $periode)
            ->WHERE('skpd_id', '=', $skpd_id)
            ->SELECT(
                'id',
                'role',
                'jabatan->id AS id_jabatan',
                'jabatan->nama_lengkap AS jabatan',
                'level'
            )
            ->ORDERBY('jabatan->id', 'ASC')
            ->get();


        $response['role'] = array();
        $no = 1;
        foreach ($koordinator as $x) {

            //KOORDINATOR
            $i['id']                = $x->id;
            $i['role']              = strtoupper($x->role) . ' ' . $no;
            $i['id_jabatan']        = $x->id_jabatan;
            $i['jabatan']           = $x->jabatan;
            $i['level']             = $x->level;
            array_push($response['role'], $i);
            $no += 1;
        }

        return [
            'koordinatorList'     => $response['role'],
        ];
    }

    public function ListJabatan(Request $request)
    {

        $skpd_id = $request->skpd_id;
        $periode = $request->periode;

        if (!isset($request->jabatan_atasan_id)) {
            //cari id jabatan atasan di sotk
            $skpd = $this::detail_skpd($skpd_id);
            if ($skpd == null) {
                return response()->json(['errors' => "Jabatan tidak ditemukan"], 422);
            } else {
                $jabatan_atasan_id = $skpd['id_jabatan_kepala'];
            }
        } else {
            $jabatan_atasan_id = $request->jabatan_atasan_id;
        }


        if (!isset($request->role)) {
            //nyari jneis jabatan atasan
            $jj_atasan = MatriksPeran::WHERE('jabatan->id', '=', $jabatan_atasan_id)->SELECT('role')->first();
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
                    $role = null;
                    break;
            }
        } else {
            $role = $request->role;
            $level =  "S2";
        }


        //get list jabatan yang sudah terdaftar pada matrik peran
        $existing_list = MatriksPeran::WHERE('jabatan->id_jabatan_atasan', '=', $jabatan_atasan_id)
            ->WHERE('periode', '=', $periode)
            ->WHERE('skpd_id', '=', $skpd_id)
            ->SELECT('jabatan->id AS id')
            ->get();
        $response['existing_list'] = array();
        foreach ($existing_list as $x) {
            array_push($response['existing_list'], $x->id);
        }



        //get list jabatan from SOTK
        $list_jabatan_sotk     = $this::list_jabatan($jabatan_atasan_id);

        //list jabatan dikurangi list jabatan yang sudah ada di role matrik
        $response['role'] = array();
        foreach ($list_jabatan_sotk as $x) {
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
        $existing = MatriksPeran::WHERE('periode', '=', $periode)
            ->WHERE('skpd_id', '=', $skpd_id)
            ->where(function ($query) use ($jabatan_atasan_id) {
                $query->WHERE('id', '=', $jabatan_atasan_id)
                    ->orWhere('parent_id', '=', $jabatan_atasan_id);
            })
            ->SELECT('jabatan->id AS id')
            ->get();
        $response['existing'] = array();
        foreach ($existing as $x) {
            array_push($response['existing'], $x->id);
        }

        $jabatan_atasan_id = MatriksPeran::SELECT('jabatan->id AS jabatan_id')->WHERE('id', '=', $jabatan_atasan_id)->first();
        $jabatan_atasan_id = $jabatan_atasan_id->jabatan_id;


        //get detail jabatan pribadi from SOTK
        $jabatan_self     = $this::detail_jabatan($jabatan_atasan_id);
        //get list bawahan from SOTK
        $list_jabatan_sotk     = $this::list_jabatan($jabatan_atasan_id);
        array_push($list_jabatan_sotk, $jabatan_self);
        $list_jabatan_sotk = collect($list_jabatan_sotk)->sortBy('id')->toArray();

        //list jabatan yang muncul hanya yang sudah ada di matrik peran hasil

        $response['role'] = array();
        foreach ($list_jabatan_sotk as $x) {
            if (($x['is_jabatan_kepala'] === true) & (in_array($x['id'], $response['existing']))) {
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
        $dt = MatriksPeran::WHERE('id', '=', $role_id)
            ->SELECT('parent_id AS id')
            ->first();
        if ($dt) {
            $parent_id = $dt->id;
        }

        $response = array();
        $response['outcomeAtasan'] = array();

        $data = MatriksHasil::WHERE('matriks_peran_id', $parent_id)->get();
        foreach ($data as $y) {
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
        $koordinator_id = $request->koordinator_id ? $request->koordinator_id : null;


        $koordinator = MatriksPeran::WHERE('role', '=', 'koordinator')
            ->WHERE('periode', '=', $periode)
            ->WHERE('skpd_id', '=', $skpd_id)
            ->SELECT(
                'id',
                'role',
                'jabatan->id AS id_jabatan',
                'jabatan->nama_lengkap AS jabatan',
                'level',
                'skpd_id',
                'periode'
            )
            ->ORDERBY('jabatan->id', 'ASC');

        if ($koordinator_id != null) {
            $koordinator->where(function ($query) use ($koordinator_id) {
                $query->where('id', '=', $koordinator_id);
            });
        }
        $koordinator = $koordinator->get();



        //=======================================================================================================//
        //=================================== MATRIK ROLE / PERAN ===============================================//
        //=======================================================================================================//

        $response['role'] = array();
        $no = 1;

        $array_style = array('style4', 'style5', 'style6', 'style1', 'style2', 'style3');
        foreach ($koordinator as $x) {
            $row_style = $array_style[rand(0, count($array_style) - 3)];
            //KOORDINATOR
            $i['id']                = $x->id;
            $i['role']              = ($koordinator_id != null) ? strtoupper($x->role) : strtoupper($x->role) . ' ' . $no;
            $i['id_jabatan']        = $x->id_jabatan;
            $i['jabatan']           = $x->jabatan;
            $i['skpd_id']           = $x->skpd_id;
            $i['periode']           = $x->periode;
            $i['level']             = $x->level;
            $i['row_style']         = $row_style;
            array_push($response['role'], $i);

            $ketua = MatriksPeran::WHERE('parent_id', '=', $x->id)
                ->SELECT(
                    'id',
                    'role',
                    'jabatan->id AS id_jabatan',
                    'jabatan->nama_lengkap AS jabatan',
                    'level',
                    'skpd_id',
                    'periode'
                )
                ->ORDERBY('jabatan->id', 'ASC')
                ->GET();
            $s_no = 1;
            foreach ($ketua as $y) {
                //KETUA
                $i['id']                = $y->id;
                $i['role']              = strtoupper($y->role) . ' ' . $no . '.' . $s_no;
                $i['id_jabatan']        = $y->id_jabatan;
                $i['jabatan']           = $y->jabatan;
                $i['level']             = $y->level;
                $i['periode']           = $y->periode;
                $i['skpd_id']           = $y->skpd_id;
                $i['row_style']         = $row_style;
                array_push($response['role'], $i);
                $s_no += 1;
            }

            //NYARI ANGGOTA
            $ss_no = 1;
            foreach ($ketua as $y) {
                $anggota = MatriksPeran::WHERE('parent_id', '=', $y->id)
                    ->SELECT(
                        'id',
                        'role',
                        'jabatan->id AS id_jabatan',
                        'jabatan->nama_lengkap AS jabatan',
                        'level',
                        'skpd_id',
                        'periode'
                    )
                    ->ORDERBY('jabatan->id', 'ASC')
                    ->GET();
                //ANGGOTA
                $sss_no = 1;
                foreach ($anggota as $z) {
                    //KETUA
                    $i['id']                = $z->id;
                    $i['role']              = strtoupper($z->role) . ' ' . $no . '.' . $ss_no . '.' . $sss_no;
                    $i['id_jabatan']        = $z->id_jabatan;
                    $i['jabatan']           = $z->jabatan;
                    $i['level']             = $z->level;
                    $i['periode']           = $z->periode;
                    $i['skpd_id']           = $z->skpd_id;
                    $i['row_style']         = $row_style;
                    array_push($response['role'], $i);
                    $sss_no += 1;
                }

                $ss_no += 1;
            }

            $no += 1;
        }


        //=======================================================================================================//
        //=============================== END OF MATRIK ROLE / PERAN =============================================//
        //=======================================================================================================//



        //=======================================================================================================//
        //===============================      OUTCOME HEADER      =============================================//
        //=======================================================================================================//

        //for jumlah kolom outcome S2 ( Koordinator )
        $data = MatriksHasil::WHERE('level', '=', 'S2')
            ->WHERE('periode', '=', $periode)
            ->WHERE('skpd_id', '=', $skpd_id)
            ->WHERE('matriks_peran_id', '=', $koordinator_id)
            ->whereNull('parent_id')
            ->SELECT(
                'id',
                'label',
                'parent_id'
            )
            ->WITH('children')
            ->get();

        $response['sasaran_strategis'] = array();
        foreach ($data as $x) {

            //SAAT data S2 tidak memiliki children
            if (count($x->children) == 0) {
                //Bikin 1 kolom OUTCOME LEVEL S2
                $dt_1['id']           = $x['id'];
                $dt_1['label']        = $x['label'];
                array_push($response['sasaran_strategis'], $dt_1);
            }
            foreach ($x->children as $y) {
                //SAAT data S3 tidak memiliki children
                if (count($y->children) == 0) {
                    //Bikin 1 kolom OUTCOME LEVEL S3
                    $dt_1['id']           = $x['id'];
                    $dt_1['label']        = $x['label'];
                    array_push($response['sasaran_strategis'], $dt_1);
                }

                foreach ($y->children as $y) {
                    //yang diinput tetap OUTCOME LEVEL S2
                    $dt_1['id']           = $x['id'];
                    $dt_1['label']        = $x['label'];
                    array_push($response['sasaran_strategis'], $dt_1);
                }
            }
        }

        //return $response['sasaran_strategis'];

        //MATRIKS PERAN DAN HASIL
        $response['data'] = array();
        $response['outcome'] = array();


        //=======================================================================================================//
        //============================    END OF   OUTCOME HEADER      ==========================================//
        //=======================================================================================================//




        $response['outcome']   = $response['sasaran_strategis'];
        $response['last_data'] = $response['outcome'];

        //lakukan pengulangan sesuai bari Role ( peran )
        foreach ($response['role'] as $role) {
            $role_level             = $role['level'];
            $matriks_peran_id       = $role['id'];

            //BARIS PERTAMA
            if ($role_level != "S2") { // S2 nya dilewat, kan udah diatas

                //======================================================================//
                //===================   MENCARAI DATA OUTCOME nYA  =====================//
                //======================================================================//
                $n_data = 0;
                $count = 0;
                $last_id = null ;
                $array_last_id = array();

                foreach ($response['last_data'] as $outcome) { //baris terakhir yang ter record
                    $outcome_id = $outcome['id'];
                    // 1    1    4

                    if ( $n_data == $count ) { //ini akan melewatkan jika hasil outcome berupa lebihdari 1 baris
                        //cari di db outcome pada level ini
                            $new_outcome = MatriksHasil::WHERE('level', '=', $role_level)
                                ->WHERE('parent_id', '=', $outcome_id)
                                ->WHERE('matriks_peran_id', '=', $matriks_peran_id)
                                ->whereNotIn('id', $array_last_id)
                                ->SELECT('id', 'matriks_peran_id','label', 'level', 'parent_id')
                                ->WITH('children')
                                ->get();


                            if ($new_outcome->isEmpty()) {
                                //jika empty ( tidak ada data baru, data diatas/sebelunya diinsert lagi)
                                $oa['id']           = $outcome_id;
                                $oa['label']        = "";
                                array_push($response['outcome'], $oa);
                                $count++;
                            } else {
                                //isi array jika new outcome ada hasilnya
                                foreach ($new_outcome as $xa) {
                                    //JANGAN diinput lagi kalo ID nya masih sama kayak yang terakhir
                                    //DAN PERAN ID ya harus sama seperti yang terakhir
                                    if (  ($last_id != $xa['id']) ){
                                        //KErjakan jika last id nya beda
                                        $last_id = $xa['id'];
                                        array_push($array_last_id, $last_id);
                                        if (count($xa->children) == 0) { //jika child nya kosong
                                            $ob['id']           = $last_id;
                                            $ob['label']        = $xa['label'];
                                            array_push($response['outcome'], $ob);
                                            $count++;

                                        } else {
                                            foreach ($xa->children as $xb) {
                                                //yang diinput tetap OUTCOME LEVEL S3
                                                $oc['id']           = $last_id;
                                                $oc['label']        = $xa['label']." ulang";
                                                array_push($response['outcome'], $oc);
                                                $count++;
                                            }
                                        }

                                    }else{
                                        //jika SAMA ( data sebelunya diinsert lagi)
                                        $od['id']           = $outcome_id;
                                        $od['label']        = "";
                                        array_push($response['outcome'], $od);
                                        $count++;
                                    }

                                }
                            }
                    }
                    $n_data++;

                }
            }


            $k['id']                = $role['id'];
            $k['role']              = $role['role'];
            $k['id_jabatan']        = $role['id_jabatan'];
            $k['jabatan']           = $role['jabatan'];
            $k['level']             = $role['level'];
            $k['skpd_id']           = $role['skpd_id'];
            $k['periode']           = $role['periode'];
            $k['row_style']         = $role['row_style'];

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


    public function hasilDetail(Request $request)
    {
        $indikator = MatriksHasil::with(array('Periode' => function($query) {
                $query->select('id');
                //->with('parent:id,label')
                //->with('renja:id,periode->tahun AS periode,skpd->id AS id_skpd,skpd->nama AS nama_skpd,status');
            }))
            ->SELECT(
                'id',
                'label',

            )
            ->WHERE('id', $request->id)
            ->first();


        if ($indikator) {
            $h['id']                    = $indikator->id;
            $h['label']                 = $indikator->label;

        } else {
            $h = null;
        }

        return $h;
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
            ->WHERE('periode', '=', $request->periode)
            ->WHERE('skpd_id', '=', $request->skpdId)
            ->WHERE('jabatan->id', '=', $request->parentId)
            ->first();
        if ($parent) {
            $parent_id = $parent->id;
        } else {
            $parent_id = null;
        }



        $selectedRoles = $request->selectedRoles;
        $no = 0;

        foreach ($selectedRoles as $x) {
            //cari detail jabatan from simASN
            $jabatan     = $this::detail_jabatan($x['id']);

            $rp    = new MatriksPeran;
            $rp->skpd_id             = $request->skpdId;
            $rp->periode             = $request->periode;
            $rp->role                = $request->role;
            $rp->level               = $request->level;
            $rp->parent_id           = $parent_id;
            $rp->jabatan             = json_encode($jabatan);

            if ($jabatan != null) {
                $rp->save();
                $no++;
            }
        }

        if ($no > 0) {
            return \Response::make($no . "data berhasil tersimpan", 200);
        } else {
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




        if ($rp->save()) {
            return \Response::make(" data berhasil tersimpan", 200);
        } else {
            return \Response::make("data tidak berhasil tersimpan", 400);
        }
    }
}
