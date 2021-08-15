<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserLogging;
use App\Http\Resources\User as UserResource;

use Illuminate\Pagination\Paginator;

use GuzzleHttp\Client;

class UserController extends Controller
{

    //MENDAPATKAN detail atasan from sim-ASN
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
            return $arr_body['data'];

        }catch(\GuzzleHttp\Exception\GuzzleException $e) {
            return "error";
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
            if ( $arr_body['atasan'] != null ){
                return $arr_body['atasan']['nip'];
            }else{
                return null;
            }


        }catch(\GuzzleHttp\Exception\GuzzleException $e) {
            return "error";
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


    public function user_list(Request $request)
    {

        $page = ($request->page)? $request->page : 1 ;
        $length = ($request->length)? $request->length : 10 ;

        Paginator::currentPageResolver(fn() => $page );

        $query =  User::select(
            'username',
            'id',
            'nip',
            'pegawai->nama_lengkap AS nama_lengkap',
        );

        if($request->search) {
            $query->where('pegawai->nama_lengkap','LIKE', '%'.$request->search.'%');
        }

        $data = $query->paginate($length);


        $pagination = array(
            'current_page'  => $data->currentPage(),
            'total_page'    => ( ($data->perPage() != 0 ) && ($data->total() != 0 )  ) ? Floor($data->total()/$data->count()) : 0,
            'data_per_page' => $data->count(),
            'limit'         => (int)$data->perPage(),
            'total'         => $data->total(),
        );

        return [
            'data'          => $data->items(),
            'pagination'    => $pagination,

        ];


    }

    public function user_detail(Request $request)
    {

        //kita akan coba update user yang tidak ada id sim asn nya
        $query =  User::WHERE('nip','=',$request->nip)->first();

        if ( $query['pegawai'] == null ){
            $detail_pegawai                 = $this::detail_pegawai($request->nip);
            //nip pejabat penilai
            $nip_pejabat_penilai            = $this::nip_atasan($request->nip);
            if ( $nip_pejabat_penilai != null ){
                //detail pejabata penilai
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
