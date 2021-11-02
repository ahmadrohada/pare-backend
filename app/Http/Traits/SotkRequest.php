<?php
namespace App\Http\Traits;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

trait SotkRequest
{

    //DETAIL SKPD FROM SOTK APP
    public function Skpd($skpd_id){

        $id = $skpd_id;
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

}
