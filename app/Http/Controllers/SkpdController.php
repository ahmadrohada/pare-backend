<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Pagination\Paginator;

use GuzzleHttp\Client;

class SkpdController extends Controller
{


    //LIST SKPD FROM SOTK APP
    protected function skpd_list($page,$limit){
        $token = env('SIMPEG_APP_TOKEN');
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        $query = [
               'page' => $page,
               'limit' => $limit
        ];


        try{
            $client = new Client([
                'base_uri' => 'https://api.sim-asn.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('GET', '/api/sotk/skpd',[
                'headers'   => $headers,
                'query'     => $query,
            ]);
            $body = $response->getBody();
            $arr_body = json_decode($body,true);
            return $arr_body;
           /*  if ( isset($arr_body['data']) && ($arr_body['data'] != null) ){
                return $arr_body['data'];
            }else{
                return null;
            } */


        }catch(\GuzzleHttp\Exception\GuzzleException $e) {
            return null;
        }
    }

    //DETAIL SKPD FROM SOTK APP
    protected function skpd_detail($id){

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
            $response = $client->request('GET', '/api/sotk/skpd/'.$id,[
                'headers'   => $headers
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
        $page   = ($request->page)? $request->page : 1 ;
        $limit  = ($request->limit)? $request->limit : 10 ;

        $skpd_list = $this::skpd_list($page,$limit);

        return $skpd_list;

        $data = array();
        foreach ($skpd_list['data'] as $x) {

            $dt = array(
                'id'            => $x['id'],
                'nama'          => $x['nama'],
                'singkatan'     => $x['singkatan'],
                'total_user'    => User::WHERE('pegawai->skpd->id','=',$x['id'])->count()
            );
            array_push($data,$dt);
        }

        return [

            'data'                 => $data,
            'pagination'           => $skpd_list['pagination']

        ];
    }

    public function detail(Request $request)
    {

        return $this::skpd_detail($request->id);
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
