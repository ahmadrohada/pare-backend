<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ValidateUserRegistration;
use App\Http\Requests\ValidateUserLogin;
use App\Models\User;
use App\Models\UserLogging;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use GuzzleHttp\Client;


class AuthController extends Controller
{


    //Redirect after login.
    protected function redirectLoginSimpeg($state = 'login' , string $key, string $message):RedirectResponse{
        $baseUrl = 'login' === $state ? (env('FRONTEND_URL').'/login') : (env('FRONTEND_URL').'/profile');
        return new RedirectResponse($baseUrl."?{$key}={$message}");
    }


    //login with SIM ASN
    protected function get_token($code){


        $headers = [
            'Accept'        => 'application/json',
        ];

        $form_params = [

                'grant_type'    => "authorization_code",
                'client_id'     => "93ce4ca9-b473-4f37-bd34-1a03c5c61e58",
                'client_secret' => "CsNQhGHdecgHjNg6nuKqMVRbeSnIFS9I3sidMjFL7VACQTEjxz",
                'redirect_uri'  => env('BACKEND_URL').'/api/login_simpeg',
                'scope'         => "*",
                'code'          => $code

        ];

        return $form_params;

        //try{
            $client = new Client([
                'base_uri' => 'https://api.sim-asn.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 15, // Response timeout
                'connect_timeout' => 15, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('POST', '/oauth/token',[
                'headers'       => $headers,
                'form_params'   => $form_params
            ]);

            $body = $response->getBody();

            /* return json_decode($body,true);
            if ( isset($body['access_token']) && ($body['access_token'] != null) ){
                return $body;
            }else{
                return null;
            } */
        //}catch(\GuzzleHttp\Exception\GuzzleException $e) {
            //return null;
        //}
    }


    //MENDAPATKAN user profile from sim-ASN
    protected function user_profile($token){
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        try{
            $client = new Client([
                'base_uri' => 'https://api.sim-asn.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 15, // Response timeout
                'connect_timeout' => 15, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('GET', '/api/me/',[
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
            return "error";
        }
    }

    //MENDAPATKAN peagwai detail from sim-ASN
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
                'timeout' => 15, // Response timeout
                'connect_timeout' => 15, // Connection timeout
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
            return "error";
        }
    }

    //MENDAPATKAN detail atasan from sim-ASN
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
                'timeout' => 6, // Response timeout
                'connect_timeout' => 6, // Connection timeout
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
            return "error";
        }
    }



    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','login_simpeg','register' ,'logout','welcome']]);
    }

    public function logout(){
        \Auth::logout();
    }

    public function welcome()
    {
        return \Response::json("Welcome API Pare 2021" , 200);
    }


    public function register(ValidateUserRegistration $request)
    {
        /* $user = User::create([
            //'name' => $request->name,
            'username'      => $request->username,
            'password'      => bcrypt($request->password),
            'pegawai_id'    => $request->pegawai_id,
            'pegawai'       => $request->pegawai,
        ]);
        return new UserResource($user); */
    }

    public function login(ValidateUserLogin $request)
    {

        $credentials = request(['username', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return  response()->json([
                'message' => 'Kesalahan username atau password',
                /* 'errors' => [
                    'message' => ['Kesalahan username atau password']
                ] */
            ], 401);
        }

        $user_data = new UserResource(auth()->user());

        if (  $user_data->pegawai === null ){
            return  response()->json([
                'message' => 'Profile Pegawai tidak ditemukan , silakan login dengan akun SIM-ASN',
                /* 'errors' => [
                    'message' => ['Profile Pegawai tidak ditemukan']
                ] */
            ], 401);
        }else{
            return response()->json([
                'type' => 'success',
                'message' => 'Logged in',
                "token_type"=> "Bearer",
                'token' => $token,
                'data'  => $user_data
            ]);
        }




    }

    public function login_simpeg(Request $request)/* :RedirectResponse */
    {

        $token = $this::get_token($request->code);
        return $token;



        if ( $token != null ){
            if ( isset($token['access_token']) || $token['access_token'] != null  ){
                $profile = $this::user_profile($token['access_token']);

                if ( isset($profile['pegawai']['nip'])){
                    $user = User::WHERE('nip',$profile['pegawai']['nip'])->first();

                    if ($user){
                        if (!$userToken = JWTAuth::fromUser($user)) {
                            return $this->redirectLoginSimpeg($request->state, 'token', '' );
                        }

                        /* if ( $user['pegawai'] === null ){
                            $detail_pegawai                 = $this::detail_pegawai($profile['pegawai']['nip']);
                            //nip pejabat penilai
                            $nip_pejabat_penilai            = $this::nip_atasan($profile['pegawai']['nip']);
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

                            //UPDATE USER PARE with SIM-ASN PROFILE
                            $update                             = User::find($user->id);
                            $update->pegawai                    = isset($detail_pegawai) ? $detail_pegawai : null;
                            $update->pejabat_penilai            = isset($pejabat_penilai) ? $pejabat_penilai : null;
                            $update->atasan_pejabat_penilai     = isset($atasan_pejabat_penilai ) ? $atasan_pejabat_penilai : null ;
                            $update->simpeg_id                  = $profile['id'];
                            $update->simpeg_token               = $token['access_token'];
                            $update->simpeg_refresh_token       = $token['refresh_token'];
                            $update->save();
                            //lOGIN SUKSES

                            //LOGGER
                            $add_log = new UserLogging;
                            $add_log->id_user           = $user->id;
                            $add_log->module            = "sync";
                            $add_log->action            = "sync";
                            $add_log->label             = "sikronisasi data simpeg";
                            $add_log->save();
                        } */

                        //LOGGER
                        $add_log = new UserLogging;
                        $add_log->id_user           = $user->id;
                        $add_log->module            = "authentication";
                        $add_log->action            = "login";
                        $add_log->label             = "Login melalui akun simpeg";
                        $add_log->save();


                        return $this->redirectLoginSimpeg($request->state, 'token', $userToken );
                    }else{
                        return $this->redirectLoginSimpeg($request->state, 'message', 'Login SIM-ASN gagal' );
                    }

                }else{
                    return $this->redirectLoginSimpeg($request->state, 'message', 'NIP tidak ditemukan' );
                }
            }else{
                return $this->redirectLoginSimpeg($request->state, 'message', 'User Profile Error' );
            }
        }else{
            return $this->redirectLoginSimpeg($request->state, 'message', 'gagal Mendapatkan Token SIM-ASN' );
        }


    }


    public function user()
    {
        return new UserResource(auth()->user());
    }



    /* $user           = \Auth::user();
    $userRole       = $user->hasRole('personal');
    $admin_skpdRole = $user->hasRole('admin_skpd');
    $adminRole      = $user->hasRole('administrator');
    $non_pnsRole      = $user->hasRole('non_pns');

    if ($userRole) {
        $access = 'Personal';
        $dashboard = 'personal';
    } elseif ($admin_skpdRole) {
        $access = 'Admin SKPD';
        //$dashboard = 'admin-skpd';
        $dashboard = 'personal';
    } elseif ($adminRole) {
        $access = 'Administrator';
        //$dashboard = 'administrator';
        $dashboard = 'personal';
    }elseif ($non_pnsRole) {
        $access = 'NonPns';
        $dashboard = 'personal';
    }

     */
}
