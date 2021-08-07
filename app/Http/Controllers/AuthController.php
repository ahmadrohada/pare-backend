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
    protected function detail_pegawai($token){
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

    //MENDAPATKAN detail atasan from sim-ASN
    protected function detail_atasan($nip){
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
            return $arr_body;

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
                    $detail_pegawai = $this::detail_pegawai($token['access_token']);
                    $detail_pejabat_penilai = $this::detail_atasan($profile['pegawai']['nip']);
                    $detail_atasan_pejabat_penilai = $this::detail_atasan($detail_pejabat_penilai['atasan']['nip']);

                    $pegawai = array(
                        "profile" => array(
                            "id"            => $detail_pegawai['data']['id'],
                            "nip"           => $detail_pegawai['data']['nip'],
                            "nama_lengkap"  => $detail_pegawai['data']['nama_lengkap'],
                            "photo"         => $detail_pegawai['data']['photo']
                        ),
                        "jabatan" => array(
                            "id"            => $detail_pegawai['data']['jabatan'][0]['id'],
                            "nama"          => $detail_pegawai['data']['jabatan'][0]['nama'],
                            "jenis"         => $detail_pegawai['data']['jabatan'][0]['referensi']['jenis'],
                            "golongan"      => $detail_pegawai['data']['golongan']['referensi']['golongan'],
                            "pangkat"       => $detail_pegawai['data']['golongan']['referensi']['pangkat']
                        ),
                        "skpd" => array(
                            "id"            => $detail_pegawai['data']['skpd']['id'],
                            "nama"          => $detail_pegawai['data']['skpd']['nama'],
                            "singkatan"     => $detail_pegawai['data']['skpd']['singkatan'],
                            "logo"          => $detail_pegawai['data']['skpd']['logo']
                        ),
                        "unit_kerja" => array(
                            "id"            => $detail_pegawai['data']['unit_kerja']['id'],
                            "nama"          => $detail_pegawai['data']['unit_kerja']['nama_lengkap']
                        )
                    );

                    $pejabat_penilai = array(
                        "profile" => array(
                            "id"            => $detail_pejabat_penilai['atasan']['id'],
                            "nip"           => $detail_pejabat_penilai['atasan']['nip'],
                            "nama_lengkap"  => $detail_pejabat_penilai['atasan']['nama_lengkap'],
                            "photo"         => $detail_pejabat_penilai['atasan']['photo']
                        ),
                        "jabatan" => array(
                            "id"            => $detail_pejabat_penilai['atasan']['jabatan'][0]['id'],
                            "nama"          => $detail_pejabat_penilai['atasan']['jabatan'][0]['nama'],
                            "jenis"         => $detail_pejabat_penilai['atasan']['jabatan'][0]['referensi']['jenis'],
                            "golongan"      => $detail_pejabat_penilai['atasan']['jabatan'][0]['golongan']['referensi']['golongan'],
                            "pangkat"       => $detail_pejabat_penilai['atasan']['jabatan'][0]['golongan']['referensi']['pangkat']
                        ),
                        "skpd" => array(
                            "id"            => $detail_pejabat_penilai['atasan']['jabatan'][0]['skpd']['id'],
                            "nama"          => $detail_pejabat_penilai['atasan']['jabatan'][0]['skpd']['nama'],
                            "singkatan"     => $detail_pejabat_penilai['atasan']['jabatan'][0]['skpd']['singkatan'],
                            "logo"          => $detail_pejabat_penilai['atasan']['jabatan'][0]['skpd']['logo']
                        ),
                        "unit_kerja" => array(
                            "id"            => $detail_pejabat_penilai['atasan']['jabatan'][0]['unit_kerja']['id'],
                            "nama"          => $detail_pejabat_penilai['atasan']['jabatan'][0]['unit_kerja']['nama_lengkap']
                        )
                    );


                    $atasan_pejabat_penilai = array(
                        "profile" => array(
                            "id"            => $detail_atasan_pejabat_penilai['atasan']['id'],
                            "nip"           => $detail_atasan_pejabat_penilai['atasan']['nip'],
                            "nama_lengkap"  => $detail_atasan_pejabat_penilai['atasan']['nama_lengkap'],
                            "photo"         => $detail_atasan_pejabat_penilai['atasan']['photo']
                        ),
                        "jabatan" => array(
                            "id"            => $detail_atasan_pejabat_penilai['atasan']['jabatan'][0]['id'],
                            "nama"          => $detail_atasan_pejabat_penilai['atasan']['jabatan'][0]['nama'],
                            "jenis"         => $detail_atasan_pejabat_penilai['atasan']['jabatan'][0]['referensi']['jenis'],
                            "golongan"      => $detail_atasan_pejabat_penilai['atasan']['jabatan'][0]['golongan']['referensi']['golongan'],
                            "pangkat"       => $detail_atasan_pejabat_penilai['atasan']['jabatan'][0]['golongan']['referensi']['pangkat']
                        ),
                        "skpd" => array(
                            "id"            => $detail_atasan_pejabat_penilai['atasan']['jabatan'][0]['skpd']['id'],
                            "nama"          => $detail_atasan_pejabat_penilai['atasan']['jabatan'][0]['skpd']['nama'],
                            "singkatan"     => $detail_atasan_pejabat_penilai['atasan']['jabatan'][0]['skpd']['singkatan'],
                            "logo"          => $detail_atasan_pejabat_penilai['atasan']['jabatan'][0]['skpd']['logo']
                        ),
                        "unit_kerja" => array(
                            "id"            => $detail_atasan_pejabat_penilai['atasan']['jabatan'][0]['unit_kerja']['id'],
                            "nama"          => $detail_atasan_pejabat_penilai['atasan']['jabatan'][0]['unit_kerja']['nama_lengkap']
                        )
                    );






                    //UPDATE USER PARE with SIM-ASN PROFILE
                    $update                             = User::find($user->id);
                    $update->pegawai                    = $pegawai;
                    $update->pejabat_penilai            = $pejabat_penilai;
                    $update->atasan_pejabat_penilai     = $atasan_pejabat_penilai;
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
