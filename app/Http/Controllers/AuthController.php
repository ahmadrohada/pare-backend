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
                                        'redirect_uri'  => (env('BACKEND_URL').'/api/login_simpeg'),
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
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('GET', '/api/me/',[
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

    //MENDAPATKAN peagwai detail from sim-ASN
    protected function pegawai_detail($token){
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
            $response = $client->request('GET', '/api/me/pegawai/',[
                'headers' => $headers
            ]);
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
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10, // Connection timeout
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
                /* "expires_in"=> 1577923199, */
                'token' => $token,
                'data'  => $user_data
            ]);
        }




    }

    public function login_simpeg(Request $request):RedirectResponse
    {
        $token = $this::get_token($request->code);

        if ( isset($token['access_token']) || $token['access_token'] != null  ){
            $profile = $this::user_profile($token['access_token']);

            if ( isset($profile['pegawai']['nip'])){
                $user = User::WHERE('nip',$profile['pegawai']['nip'])->first();

                if ($user){
                    if (!$userToken = JWTAuth::fromUser($user)) {
                        return $this->redirectLoginSimpeg($request->state, 'token', '' );
                    }

                    //GET DATA PROFILE PEGAWAI WITH
                    $detail = $this::pegawai_detail($token['access_token']);
                    $pegawai = [
                        "id"            => $detail['data']['id'],
                        "nip"           => $detail['data']['nip'],
                        "nama_lengkap"  => $detail['data']['nama_lengkap'],
                        "photo"         => $detail['data']['photo']
                    ];

                    $jabatan = [
                        "id"            => $detail['data']['jabatan'][0]['id'],
                        "nama"          => $detail['data']['jabatan'][0]['nama'],
                        "jenis"         => $detail['data']['jabatan'][0]['referensi']['jenis'],
                        "golongan"      => $detail['data']['golongan']['referensi']['golongan'],
                        "pangkat"       => $detail['data']['golongan']['referensi']['pangkat']
                    ];

                    $skpd = [
                        "id"            => $detail['data']['skpd']['id'],
                        "nama"          => $detail['data']['skpd']['nama'],
                        "singkatan"     => $detail['data']['skpd']['singkatan'],
                        "logo"          => $detail['data']['skpd']['logo']
                    ];

                    $unit_kerja = [
                        "id"            => $detail['data']['unit_kerja']['id'],
                        "nama"          => $detail['data']['unit_kerja']['nama_lengkap']
                    ];


                    //UPDATE USER PARE with SIM-ASN PROFILE
                    $update                             = User::find($user->id);
                    $update->pegawai                    = $pegawai;
                    $update->jabatan                    = $jabatan;
                    $update->skpd                       = $skpd;
                    $update->unit_kerja                 = $unit_kerja;
                    $update->simpeg_id                  = $profile['id'];
                    $update->simpeg_token               = $token['access_token'];
                    $update->simpeg_refresh_token       = $token['refresh_token'];
                    $update->save();
                    //lOGIN SUKSES

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
