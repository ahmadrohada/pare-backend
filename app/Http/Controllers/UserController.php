<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\RoleUser;
use App\Models\UserLogging;
use App\Http\Resources\User as UserResource;

use Illuminate\Pagination\Paginator;

use App\Services\Datatables\UserDataTable;

use GuzzleHttp\Client;

class UserController extends Controller
{

    //MENDAPATKAN detail PEGAWAI from sim-ASN
    protected function detail_pegawai($nip){
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
            return $e;
        }
    }

    protected function detail_jabatan($id){
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
            $response = $client->request('GET', '/api/sotk/jabatan/'.$id.'?with=referensi',[
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
            return $e;
        }
    }

    //MENDAPATKAN nip atasan from sim-ASN
    protected function nip_atasan($nip){
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
            $response = $client->request('GET', '/api/pegawai/'.$nip.'/hierarki',[
                'headers' => $headers
            ]);
            $body = $response->getBody();
            $arr_body = json_decode($body,true);
            if ( isset($arr_body['atasan']) && ($arr_body['atasan'] != null) ){
                return $arr_body['atasan']['nip'];
            }else{
                return null;
            }


        }catch(\GuzzleHttp\Exception\GuzzleException $e) {
            return null;
        }
    }

    public function profile_user_aktif(Request $request)
    {
        return new UserResource(auth()->user());
    }


    public function hirarki_user_aktif(Request $request)
    {

        $data = auth()->user();
        return [

            'pegawai'                   => $data->pegawai,
            'pejabat_penilai'           => $data->pejabat_penilai,
            'atasan_pejabat_penilai'    => $data->atasan_pejabat_penilai,

        ];


    }



    public function user_update()
    {
        $data = User::leftjoin('pegawai AS pegawai', function ($join) {
                $join->on('users.pegawai_id', '=', 'pegawai.id');
            })
            ->SELECT(
                'users.id AS user_id',
                'users.pegawai_id AS pegawai_id',
                'users.username AS nip_alias',
                'pegawai.nip AS nip'
            )
            ->get();

        $no = 0;
        foreach ($data as $x) {

            $sr    = User::find($x->user_id);
            $sr->nip   = ( $x->nip != null ) ? $x->nip : $x->nip_alias;

            if ($sr->save()) {
                $no++;
            }
        }
        return "berhasil update data sebanyak : " . $no;
    }

    public function UserList(Request $request){
        $searchDatas = new UserDataTable($request->all());
        $data = $searchDatas->search();
        return $data;
    }
    /* public function user_list(Request $request)
    {

        $page = ($request->page)? $request->page : 1 ;
        $limit = ($request->limit)? $request->limit : 50 ;

        $id_skpd = ($request->id_skpd)? $request->id_skpd : null ;

        Paginator::currentPageResolver(fn() => $page );

        $query =  User::
                        select(
                            'users.username AS username',
                            'users.id AS id_user',
                            'users.nip AS nip',
                            'users.pegawai->nama_lengkap AS nama_lengkap',
                            'users.pegawai->jabatan AS jabatan',

                        )
                        ->orderBY('users.pegawai->jabatan->referensi->id_referensi', 'asc');

        if($id_skpd != null ) {
            $query->where('pegawai->skpd->id','=', $id_skpd );
        }

        if($request->search) {
            $query->where('pegawai->nama_lengkap','LIKE', '%'.$request->search.'%');
        }

        $data = $query->paginate($limit);


        $pagination = array(
            'current_page'  => $data->currentPage(),
            'total_page'    => ( ($data->perPage() != 0 ) && ($data->total() != 0 )  ) ? Floor($data->total()/$data->count()) : 0,
            'data_per_page' => $data->count(),
            'limit'         => (int)$data->perPage(),
            'total'         => $data->total(),
        );

        //pecah  data items untuk nyari is admin atau bukan

        $data = $data->items();


        $response = array();
        foreach( $data AS $x ){
            if ($x->jabatan ){
                //diulang sesuai jumlah jabatan
                foreach( json_decode($x->jabatan) AS $y ){
                    //data pegawai
                    $h['id']			= $x->id_user;
                    $h['username']      = $x->username;
                    $h['nip']           = $x->nip;
                    $h['nama_lengkap']  = $x->nama_lengkap;
                    //data jabatan
                    $h['jabatan']            = $y->nama;
                    $h['jabatan_id']         = $y->id;


                    //mengetahui jenis jabatan
                    $jenis_jabatan = isset($y->referensi->referensi->jenis)?($y->referensi->referensi->jenis):null;
                    $h['jenis_jabatan']     = $jenis_jabatan;
                    $h['jabatan_referensi_id']  = $y->referensi->id_referensi;

                    if ( $jenis_jabatan == 'struktural'){
                        //eselon
                        $h['jabatan_eselon_id']     = $y->referensi->referensi->id;
                        $h['jabatan_eselon']        = $y->referensi->referensi->eselon->eselon;

                    }else if ( $jenis_jabatan == 'pelaksana'){
                        $h['jabatan_eselon_id']     = null;
                        $h['jabatan_eselon']        = null;
                    }

                    //golongan

                    $h['jabatan_golongan_id']           = isset($y->golongan)?$y->golongan->referensi->id:null;
                    $h['jabatan_golongan']              = isset($y->golongan)?$y->golongan->referensi->golongan:null;
                    $h['jabatan_golongan_pangkat']      = isset($y->golongan)?$y->golongan->referensi->pangkat:null;


                    //skpd
                    $h['jabatan_skpd_nama']     = $y->skpd->nama;


                    //is admin
                    $admin = RoleUser::WHERE('user_id',$x->id_user)->WHERE('role_id','2')->exists();
                    $h['is_admin'] = $admin;

                    //push jika skpd nya relavan dengan yang dicari
                    $h['jabatan_skpd_id']       = $y->skpd->id;
                    if( $id_skpd != null ) {
                        if ( $h['jabatan_skpd_id'] ==  $id_skpd){
                            array_push($response, $h);
                        }
                    }else{
                        array_push($response, $h);
                    }


                }
            }

        }

        //$response = collect($response)->sortByDesc('jabatan_golongan_id')->values();
        $response = collect($response)->sortBy('jabatan_referensi_id')->values();

        return [
            'data'          => $response ,
            'pagination'    => $pagination,

        ];


    }*/

    public function select_user_list(Request $request)
    {


        $id_skpd = ($request->id_skpd)? $request->id_skpd : null ;

        $query =  User::
                        select(
                            'users.username AS username',
                            'users.id AS id_user',
                            'users.nip AS nip',
                            'users.pegawai->nama_lengkap AS nama_lengkap',
                            'users.pegawai->jabatan AS jabatan',

                        )
                        ->orderBY('users.pegawai->jabatan->referensi->id_referensi', 'asc');

        if($id_skpd != null ) {
            $query->where('pegawai->skpd->id','=', $id_skpd );
        }

        if($request->search) {
            $query->where('pegawai->nama_lengkap','LIKE', '%'.$request->search.'%');
        }

        $response = array();
        foreach( $query->get() AS $x ){
            $h['value']			= $x->nama_lengkap.' - NIP.'.$x->nip;
            $h['link']          = $x->id_user;
            $h['id']            = $x->id_user;
            array_push($response, $h);
        }

        return $response;


    }

    public function UserJabatanList(Request $request)
    {

        //kita akan coba update user yang tidak ada id sim asn nya
        $query =  User::WHERE('id','=',$request->id)->first();

        $detail_pegawai                 = $this::detail_pegawai($query->nip);

        $response = array();
        if ( $detail_pegawai != null ){
            foreach( $detail_pegawai['jabatan'] AS $x ){
                $h['value']			= $x['id'];
                $h['label']         = $x['nama'];
                array_push($response, $h);
            }

            return $response;
        }else{
            $data = array(
                'message' => 'API SIM-ASN tidak dapat diakses',
            );

            return \Response::make($data, 503);
        }

    }

    public function UserJabatanDetail(Request $request)
    {
        //parameter nya id jabatan aktif dan id user
        $nip_pegawai = $request->nip_pegawai;
        $jabatan_aktif_id = $request->jabatan_aktif_id;


        $detail_pegawai                 = $this::detail_pegawai($nip_pegawai);


        if ( $detail_pegawai != null ){
            foreach( $detail_pegawai['jabatan'] AS $x ){

                if ( $x['id'] == $jabatan_aktif_id ){
                    $data = [

                        'jabatan_aktif_id'          => $x['id'],
                        'jabatan'                   => $x['nama'],
                        'jenis_jabatan'             => $x['referensi']['referensi']['eselon']['tingkat']['tingkat'],
                        'eselon'                    => $x['referensi']['referensi']['eselon']['eselon'],
                        'skpd'                      => $x['skpd']['nama'],
                        'skpd_id'                   => $x['skpd']['id'],
                        'pangkat'                   => $x['golongan']['referensi']['pangkat'],
                        'golongan'                  => $x['golongan']['referensi']['golongan'],

                    ];

                }

            }

            return $data;
        }else{
            $data = array(
                'message' => 'API SIM-ASN tidak dapat diakses',
            );

            return \Response::make($data, 503);
        }

    }



    public function JabatanDetail(Request $request)
    {

        return $this::detail_jabatan($request->jabatan_id);

    }




    public function user_detail(Request $request)
    {

        //kita akan coba update user yang tidak ada id sim asn nya
        $query =  User::WHERE('nip','=',$request->nip)->first();

        if ( $query['pegawai'] == null ){
            $detail_pegawai                 = $this::detail_pegawai($request->nip);
            $nip_pejabat_penilai            = $this::nip_atasan($request->nip);

            if ( $nip_pejabat_penilai != null ){
                //detail pejabat penilai
                $pejabat_penilai                = $this::detail_pegawai($nip_pejabat_penilai);
                if ( $pejabat_penilai != null ){
                    //nip atasan pejabat penilai
                    $nip_atasan_pejabat_penilai  = $this::nip_atasan($pejabat_penilai['nip']);
                    if ( $nip_atasan_pejabat_penilai != null ){
                        $atasan_pejabat_penilai = $this::detail_pegawai($nip_atasan_pejabat_penilai);
                    }
                }
            }

            $update                             = User::find($query['id']);
            $update->pegawai                    = isset($detail_pegawai) ? $detail_pegawai : null;
            $update->pejabat_penilai            = isset($pejabat_penilai) ? $pejabat_penilai : null;
            $update->atasan_pejabat_penilai     = isset($atasan_pejabat_penilai ) ? $atasan_pejabat_penilai : null ;
            $update->save();

            //LOGGER
            $add_log = new UserLogging;
            $add_log->id_user           = $query['id'];
            $add_log->module            = "sinkronisasi data";
            $add_log->action            = "synch";
            $add_log->label             = "Sikronisasi oleh admin";
            $add_log->save();

            return new UserResource(User::WHERE('id','=',$query['id'])->first());

        }else{
            return new UserResource($query);
        }

    }

    public function user_hirarki(Request $request)
    {
        $data = User::WHERE('nip','=',$request->nip)->first();
        return [

            'pegawai'                   => $data->pegawai,
            'pejabat_penilai'           => $data->pejabat_penilai,
            'atasan_pejabat_penilai'    => $data->atasan_pejabat_penilai,

        ];
    }

    public function sync_percentage(Request $request)
    {

        $total_data = User::count();
        $data_fill = User::WHERE('pegawai','!=',null)->count();
       return number_format( ( $data_fill / $total_data ) * 100 , 2);


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
