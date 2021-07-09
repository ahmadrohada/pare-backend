<?php

namespace App\Http\Controllers;

use App\Models\DailyReport;
use App\Models\DailyActivity;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Boolean;

use GuzzleHttp\Client;

use Validator;


class SimAsnController extends Controller
{

    protected function get_token($code){
        try{
            $client = new Client([
                'base_uri' => 'https://api.sim-asn.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 3, // Response timeout
                'connect_timeout' => 3, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('POST', '/oauth/token',
                ['form_params' => [
                    'grant_type'    => "authorization_code",
                    'client_id'     => "93ce4ca9-b473-4f37-bd34-1a03c5c61e58",
                    'client_secret' => "SoA6lCpauKqXWPsgfAgUecKJlEpRruAcPAFi8jmEZGpLLS1f7x",
                    'redirect_uri'  => 'https://api-pare-v3.bkpsdm.karawangkab.go.id/api/login_simpeg',
                    'code'          => $code
                ],
            ]);
            $body = $response->getBody();
            $arr_body = json_decode($body,true);
            return $arr_body['access_token'];
        }catch(\GuzzleHttp\Exception\GuzzleException $e) {
            return "error";
        }
    }

    protected function user_profile($token){

        $headers = [
            'Authorization' => 'Bearer ' . $token,        
            'Accept'        => 'application/json',
        ];

        try{
            $client = new Client([
                'base_uri' => 'https://api.sim-asn.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 3, // Response timeout
                'connect_timeout' => 3, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('GET', '/api/profile',[
                'headers' => $headers 
            ]);
            //$body = $response->getBody()->getContents();

            $body = $response->getBody();
            $arr_body = json_decode($body,true);
            return $arr_body;

        }catch(\GuzzleHttp\Exception\GuzzleException $e) {
            return "error";
        }
    }


    public function tes(Request $request)
    {
        $token = $this::get_token($request->code);
        if ( $token ){
            $profile = $this::user_profile($token);
            return "Welcome ".$profile['name'];
        }else{
            return "error_profil";
        }
    }



}
