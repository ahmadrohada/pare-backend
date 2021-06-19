<?php

namespace App\Http\Controllers;

use App\Http\Resources\User as UserResource;
use Illuminate\Http\Request;
use App\Http\Requests\ValidateUserRegistration;
use App\Http\Requests\ValidateUserLogin;
use App\Models\User;

use GuzzleHttp\Client;


class AuthController extends Controller
{
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
        $this->middleware('auth:api', ['except' => ['login', 'register' ,'logout','welcome']]);
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
            'message' => 'Logged in.',
            'token' => $token,
            'data'  => $user_data
        ]);
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
