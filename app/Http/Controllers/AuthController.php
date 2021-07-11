<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\ValidateUserRegistration;
use App\Http\Requests\ValidateUserLogin;
use App\Models\User;

use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use GuzzleHttp\Client;


class AuthController extends Controller
{


    //Redirect after login.

    protected function redirectLoginSimpeg($state = 'login' , string $key, string $message):RedirectResponse{
        $baseUrl = 'login' === $state ? 'http://localhost:3000/auth/login' : 'http://localhost:3000/profile';
        return new RedirectResponse($baseUrl."?{$key}={$message}");
    }


    //login with SIM ASN
    protected function get_token($code){
        try{
            $client = new Client([
                'base_uri' => 'https://api.sim-asn.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 6, // Response timeout
                'connect_timeout' => 6, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('POST', '/oauth/token',
                ['form_params' =>   [
                                        'grant_type'    => "authorization_code",
                                        'client_id'     => "93ce4ca9-b473-4f37-bd34-1a03c5c61e58",
                                        'client_secret' => "SoA6lCpauKqXWPsgfAgUecKJlEpRruAcPAFi8jmEZGpLLS1f7x",
                                        //'redirect_uri'  => 'https://api-pare-v3.bkpsdm.karawangkab.go.id/api/login_simpeg',
                                        'redirect_uri'  => 'http://localhost:8000/api/login_simpeg',
                                        'code'          => $code
                                    ],
                  'header'      =>  [
                                        'Accept'        => 'application/json',
                                    ],
                ]);
            $body = $response->getBody();
            return json_decode($body,true);

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
                'timeout' => 6, // Response timeout
                'connect_timeout' => 6, // Connection timeout
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

    //tes
    protected function user_detail($nip){
        $work_date = date('Y-m-d');
        try{
            $client = new Client([
                'base_uri' => 'https://api-siap.silk.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 3, // Response timeout
                'connect_timeout' => 3, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('GET', '/absensi/summary/'.$nip.'/'.$work_date, [
                'form_params' => [
                    'access_token'  => 'MjIzNTZmZjItNTJmOS00NjA1LTk5YWEtOGQwN2VhNmIwNjVm',
                ],
            ]);
            $body = $response->getBody();
            $arr_body = json_decode($body);
            $data = $arr_body->dailyAbsensis;
            return $data;
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
        $user = User::create([
            //'name' => $request->name,
            'username'      => $request->username,
            'password'      => bcrypt($request->password),
            'pegawai_id'    => $request->pegawai_id,
            'pegawai'       => $request->pegawai,
        ]);
        return new UserResource($user);
    }

    public function login(ValidateUserLogin $request)
    {

        $credentials = request(['username', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return  response()->json([
                'errors' => [
                    'msg' => ['Kesalahan username atau password']
                ]
            ], 401);
        }

        //cek apakah pegawai detail kosong atau tidak, jika kosong ambil data dari SIAP API
        $user_data = new UserResource(auth()->user());

        //return $user_data->pegawai;

        if ( $user_data->pegawai === null ){

            //lakukan pengambilan data dari API SIAP
            $d_peg =  $this::user_detail($user_data->nip);

            foreach ($d_peg as $x) {
                $data['name']       = $x->user->name;
                $data['nip']        = $x->user->nip;
                $data['gender']     = $x->user->gender;
                $data['jabatan']    = $x->user->jabatan;
                $data['golongan']   = $x->user->golongan;
                $data['eselon']     = $x->user->eselon;
                $data['skpd']       = $x->user->skpd;
                $data['unit']       = $x->user->unit;
            }

            $update  = User::find($user_data->id);
            $update->pegawai         = $data;
            $update->save();

        }

        return response()->json([
            'type' => 'success',
            'message' => 'Logged in',
            "token_type"=> "Bearer",
            "expires_in"=> 1577923199,
            'token' => $token,
            'data'  => $user_data
        ]);
    }

    public function login_simpeg(Request $request):RedirectResponse
    {
        $token = $this::get_token($request->code);
        //return $token;
        if ( isset($token['access_token']) || $token['access_token'] != null  ){
            $profile = $this::user_profile($token['access_token']);

            if ( isset($profile['pegawai']['nip'])){
                $nip = $profile['pegawai']['nip'];
                $user = User::WHERE('nip',$nip)->first();

                if (!$userToken=JWTAuth::fromUser($user)) {
                    return  response()->json([
                        'errors' => [
                            'msg' => ['Kesalahan username atau password']
                        ]
                    ], 401);
                }

               /*  $user_data = new UserResource($user);
                $data = [
                        'type' => 'success',
                        'message' => 'Logged in.',
                        'token' => $userToken,
                        'data'  => $user_data
                ]; */
                return $this->redirectLoginSimpeg($request->state, 'token', $userToken );




            }else{
                return "nip tidak ditemukan";
            }


        }else{
            return "error_profil";
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
