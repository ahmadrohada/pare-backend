<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SasaranKinerja;
use App\Models\RencanaKinerja;
use App\Models\IndikatorKinerjaIndividu;
use App\Models\SasaranStrategis;
use App\Models\PerjanjianKinerja;
use App\Models\MatriksPeran;
use App\Models\MatriksHasil;
use App\Models\SasaranKinerjaPerilakuKerja;



use App\Http\Resources\SasaranKinerja as SasaranKinerjaResource;

use App\Services\Datatables\SasaranKinerjaDataTable;
use App\Services\Datatables\SasaranKinerjaBawahanDataTable;

use App\Helpers\Pustaka;

use Validator;
use PDF;


use iio\libmergepdf\Merger;
use GuzzleHttp\Client;

class SasaranKinerjaController extends Controller
{


    protected function detail_jabatan($id_jabatan)
    {
        //$token = env('SIMPEG_APP_TOKEN');
        //$token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5M2NlNGNhOS1iNDczLTRmMzctYmQzNC0xYTAzYzVjNjFlNTgiLCJqdGkiOiI1Y2ZmYjcwNDgzNmVkZDMzZGM2MzlmNjYyN2RlNzRhNTkyY2I0OWU0NGJlNjEwZjY5MDBlYzRiZGFiZWZmNjMyNTliMTBkZmY4MjIzMmUxNSIsImlhdCI6MTYyNzgyNDU4NSwibmJmIjoxNjI3ODI0NTg1LCJleHAiOjE2NTkzNjA1ODUsInN1YiI6IiIsInNjb3BlcyI6WyIqIl19.GlE2yf4WJDtYvkThAjLYp37qxzN0NLqcS05YHFaMrQre4sY1izm1mVgO9Y-yCDasTz_0iZNrBOz1vMbw_PxsFxV9cBdPjIhuepcnk4vmN-iSe2fn0NP5rR3l2S-ZQmaT7HmPoABEcgGpTbKx_nOa43I6Y2UIzZsxPxQlDaRqfuSMBAtjm7QreGeD23K0CQQ4BMT3Nxe0iwqOwrYCjYTmu2m4JAhjmWN7amcJ95p_RdyrG0i-L9C-vGK1rO8LGtkAm4JjUmZLpGELjMpYMhIO11h2ok-TcBqaAJH_l92izQ5SHoR4UGmsyUiIygCmr8BD541zEOr4g6ITgk729lmXB_8faR0BQsbiGPA3NKq8YrHiJA63DP-iWmglE_QS4KTgSej6oU_5IM77kSXV90HTgPgzDTGgnDhtxWObcKTvCFsFlMR_aOePYEPTJj5qUIvy2lGgFJODwnr25Fc2m043tRDL_oQqBNXAl5rhTvjQxOWNylTqJw0rceuBCOOQZxPTw5L5OdOciecMfeRw3-E4dhPF7aWHwAdY4U71VAv78ACVG5NO2q4pxAoRBKEtS2pv0TgoAbkM3D-bH-C5b4XgUpylFUzIZlx4CN29FoMlwbuEbqi1hMUPu_GXOGXORXAzGHFoOIQAf8KHlO09RrQ2LUnB6-xPSvAJ-LbYZEwBGHg";
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5M2NlNGNhOS1iNDczLTRmMzctYmQzNC0xYTAzYzVjNjFlNTgiLCJqdGkiOiI3NmJhYjJkZDJjNWI1OGRlMTJiYWI3MWJiY2QxYzNjZTZhNzFmYTE0ZTJmODQ2YjBiNjRmODQyZGI2NTc4Zjc3NDBhY2I3ZTk4Njc3MTllNSIsImlhdCI6MTY1OTUwMTgwNywibmJmIjoxNjU5NTAxODA3LCJleHAiOjE2OTEwMzc4MDcsInN1YiI6IiIsInNjb3BlcyI6WyIqIl19.TQaQf6TEl852YMLYq2sR423EqabCKCPPtt9ZkgSv-DSX1hyZrhXNaRHRLDsIqKuXutenH-j26ncIxkBbVBx3MahAZwrFViw3vwnlOmHPIoOyxbjarm_FvLA2tHRq3MPFwjBoB53nkC6E9dclcLdtnJeNJnutfJldkkq-aQGTZz52GOjtzS4yizqnFvBKlOgEw3R-3UAUm5ieXI0p0msC-3V4IRpX7JNGSu0Sws9tcyCRcyg8I2Xk7RLe8J_3Oo9Ay8EpMUG7mahot1n4i-zRVDWB31OvcPyqPUtjSZ5Zx8b7N-uC9Px1_ShqOn0t6snai0DYbE8fW3AEuObiJ6QSXEc3Z2dnr_FtAwK7-cBvlBtZqpLkiGrFD1JjvsCWMxtPldpUHYn6Ug5fZdKrC5-4EqfxQIB1axb7sEufR95b7ZIcvs7SpU6MMwb3Jcsubi-en-jHRTrCZu0-K_LYEg0TXJHxAnhHNm4ArkppF-i7r6W5cvtx-NQXdkylB3JQMOP7gAXEraOBmLatYF0ELqKA1Bjw8MP9n8J5qosUgmTKbZtDoUR4a5E4v40rGW6_-XfYv59scmqjm4BoafyYDtFK0KidGsbyqVP4J9W6qb0kkdCtn1i3wpQ9SpRCMMZa9iRyaqrmzqzW3hZTJIPRI1IE42-HeaaKCrAL2RumgsznftA";
        $headers = [
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ];

        try {
            $client = new Client([
                'base_uri' => 'https://api.sim-asn.bkpsdm.karawangkab.go.id',
                'verify' => false,
                'timeout' => 10, // Response timeout
                'connect_timeout' => 10, // Connection timeout
                'peer' => false
            ]);
            $response = $client->request('GET', '/api/sotk/jabatan/' . $id_jabatan, [
                'headers' => $headers
            ]);
            $body = $response->getBody();
            $arr_body = json_decode($body, true);
            if (isset($arr_body['data']) && ($arr_body['data'] != null)) {
                return $arr_body['data'];
            } else {
                return null;
            }
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            return null;
        }
    }

    public function PkMphSkp(Request $request)
    {


        $response['role'] = array();
        $response['outcome'] = array();


        $pk = PerjanjianKinerja::with(array('Periode' => function($query) {
                //$query->select('id','label');
            }))
            ->SELECT(
                'perjanjian_kinerja.periode->tahun AS periodePk',
                'perjanjian_kinerja.skpd->nama AS namaSkpd',
                'perjanjian_kinerja.kepala_skpd->pegawai->nama_lengkap AS namaKepalaSkpd',
                'perjanjian_kinerja.jabatan_kepala_skpd->nama AS jabatanKepalaSkpd',
                'perjanjian_kinerja.admin->pegawai->nama_lengkap AS createdBy',
                'perjanjian_kinerja.created_at AS createdAt',
                'perjanjian_kinerja.status'
            )
            ->WHERE('id', $request->perjanjian_kinerja_id)
            ->first();



        $h['id']                    = $request->perjanjian_kinerja_id;
        $h['periodePk']             = $pk->periodePk;
        $h['namaSkpd']              = $pk->namaSkpd;
        $h['namaKepalaSkpd']        = $pk->namaKepalaSkpd;
        $h['jabatanKepalaSkpd']     = $pk->jabatanKepalaSkpd;
        $h['createdBy']             = $pk->createdBy;
        $h['createdAt']             = $pk->createdAt;
        $h['status']                = $pk->status;
        $h['jumlahSasaranStrategis']= SasaranStrategis::WHERE('perjanjian_kinerja_id','=',$request->perjanjian_kinerja_id)->count();

        //ROle
        $romawi = ["I","II","III","IV","V","VI","VII","VIII","IX","X"];
        $response['role'] = array();
        $role = MatriksPeran::WHERE('pegawai->nip', '=', $request->nip_pegawai_yang_dinilai)
                            ->WHERE('periode', '=', $pk->periodePk)
                            ->get();
        foreach ($role as $x) {
            $i['id']                = $x->id;
            $i['roleName']          = strtoupper($x->role);
            $i['role']              = strtoupper($x->role). ' ' . $romawi[$x->label-1];
            $i['outcome']           = $x->MatriksHasil->count();

            array_push($response['role'], $i);
        }


        return [
            'pk'       => $h,
            'role'     => $response['role'],
        ];

    }


    public function SasaranKinerjaList(Request $request)
    {
        $searchDatas = new SasaranKinerjaDataTable($request->all());
        $data = $searchDatas->search();
        return $data;
    }

    public function SasaranKinerjaBawahanList(Request $request)
    {
        $searchDatas = new SasaranKinerjaBawahanDataTable($request->all());
        $data = $searchDatas->search();
        return $data;
    }

    public function SasaranKinerjaId(Request $request)
    {
        $skp = SasaranKinerja::
                WHERE('periode_penilaian->tahun','=',$request->periode)
                ->WHERE('skpd_id','=',$request->skpd_id)
                ->WHERE('jenis_jabatan_skp','=','JABATAN PIMPINAN TINGGI')
                ->first();

        if ($skp){
            $h['id']   = $skp->id;
        }else{
            $h['id']   = null;
        }
        return $h;
    }

    public function SasaranKinerjaDetail(Request $request)
    {
        $data = SasaranKinerja::WHERE('id',$request->id)->first();

        return new SasaranKinerjaResource($data);
    }

    public function SasaranKinerjaSubmit(Request $request)
    {
        $messages = [
            'id'                                => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'id'                           => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }

        $update  = SasaranKinerja::find($request->id);

        $update->status            = '2';

        if ($update->save()) {
            $data = array(
                        'id'        => $update->id,
                    );
            return \Response::make($data, 200);
        } else {
            return \Response::make('error', 500);
        }
    }

    public function SasaranKinerjaStore(Request $request)
    {

        $messages = [

                    'dateFrom.required'              => 'Harus diisi',
                    'dateTo.required'                => 'Harus diisi',
                    'periodeLabel.required'           => 'Harus diisi',
                    'userId.required'                => 'Harus diisi',
                    'simpegId.required'              => 'Harus diisi',
                    'skpdId.required'                => 'Harus diisi',

                    'instansiPegawaiYangDinilai.required'           => 'Harus diisi',
                    'jabatanAktifPegawaiYangDinilaiId.required'     => 'Harus diisi',
                    'jabatanAktifPejabatPenilaiId.required'         => 'Harus diisi',
                    'jabatanPegawaiYangDinilai.required'            => 'Harus diisi',

                    'jabatanSimAsnPegawaiYangDinilaiId.required'    => 'Harus diisi',
                    'jabatanSimAsnPegawaiYangDinilaiJenis.required' => 'Harus diisi',
                    'nipPegawaiYangDinilai.required'                => 'Harus diisi',
                    'namaLengkapPegawaiYangDinilai.required'        => 'Harus diisi',

        ];



        $validator = Validator::make(
            $request->all(),
            [
                    'dateFrom'                              => 'required',
                    'dateTo'                                => 'required',
                    'periodeLabel'                          => 'required',
                    'userId'                                => 'required',
                    'simpegId'                              => 'required',
                    'skpdId'                                => 'required',

                    'instansiPegawaiYangDinilai'            => 'required',
                    'jabatanAktifPegawaiYangDinilaiId'      => 'required',
                    'jabatanPegawaiYangDinilai'             => 'required',
                    'jabatanSimAsnPegawaiYangDinilaiId'     => 'required',
                    'jabatanSimAsnPegawaiYangDinilaiJenis'  => 'required',
                    'nipPegawaiYangDinilai'                 => 'required',
                    'namaLengkapPegawaiYangDinilai'         => 'required',

            ],
            $messages
        );

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 422);
        }

        //CARI PK ID
        $pk = PerjanjianKinerja::WHERE('periode->tahun','=',$request->periodeLabel)->WHERE('skpd_id','=',$request->skpdId)->first();
        if ($pk){
            $pkId = $pk->id;

        }else{
            return response()->json(['message' => "Perjanjian Kinerja tidak ditemukan"], 422);
        }


        //cek apakah punya sasaran strategis pada PK nta
        $sasaran = SasaranStrategis::WHERE('perjanjian_kinerja_id','=',$pkId )->get();

        if ($sasaran == null ) {
            return response()->json(['message' => "sasaran strategis tidak ditemukan"], 422);
        }





        $periode_penilaian = [
            "periode_pk"        => $pkId,
            "tahun"             => date('Y', strtotime($request->dateFrom)),
            "tgl_mulai"         => date('Y-m-d', strtotime($request->dateFrom)),
            "tgl_selesai"       => date('Y-m-d', strtotime($request->dateTo)),
        ];

        $pegawai_yang_dinilai = [
            "nama"              => $request->namaLengkapPegawaiYangDinilai,
            "nip"               => $request->nipPegawaiYangDinilai,
            "jabatan"           => $request->jabatanPegawaiYangDinilai,
            "jabatan_id"        => $request->jabatanSimAsnPegawaiYangDinilaiId,
            "pangkat"           => $request->pangkatPegawaiYangDinilai,
            "golongan"          => $request->golonganPegawaiYangDinilai,
            "instansi"          => $request->instansiPegawaiYangDinilai,
        ];

        $pejabat_penilai = [
            "nama"              => $request->namaLengkapPejabatPenilai,
            "nip"               => $request->nipPejabatPenilai,
            "jabatan"           => $request->jabatanPejabatPenilai,
            "jabatan_id"        => $request->jabatanSimAsnPejabatPenilaiId,
            "pangkat"           => $request->pangkatPejabatPenilai,
            "golongan"          => $request->golonganPejabatPenilai,
            "instansi"          => $request->instansiPejabatPenilai,
        ];




        $ah    = new SasaranKinerja;
        $ah->user_id                    = $request->userId;
        $ah->jabatan_aktif_id           = $request->jabatanAktifPegawaiYangDinilaiId;
        $ah->skpd_id                    = $request->skpdId;
        $ah->unit_kerja_id              = $request->unitKerjaId;
        $ah->simpeg_id                  = $request->simpegId;
        $ah->pns_id                     = $request->pnsId;
        $ah->perjanjian_kinerja_id      = $pkId;
        $ah->jenis_jabatan_skp          = $request->jenisJabatanSkp;
        $ah->periode_penilaian          = json_encode($periode_penilaian);
        $ah->pegawai_yang_dinilai       = json_encode($pegawai_yang_dinilai);
        $ah->pejabat_penilai            = ( $request->namaLengkapPejabatPenilai != null ) ? json_encode($pejabat_penilai) : null;


        if ($ah->save()) {

            //JIKA JENIS JABATN JPT atau UNIT KERJA,  abuskeun semua PK nya ke SKP tahunan
            if (($ah->jenis_jabatan_skp == "JABATAN PIMPINAN TINGGI")|($ah->jenis_jabatan_skp == "PIMPINAN UNIT KERJA MANDIRI")){
                $pk_id = $ah->perjanjian_kinerja_id;
                //SELECT SEMUA data sasaran strategis nya
                $sasaran = SasaranStrategis::WITH(['Indikator'])->WHERE('perjanjian_kinerja_id','=',$pk_id )->get();
                $peran = "";

                foreach( $sasaran AS $x ){

                    $rk    = new RencanaKinerja;
                    $rk->sasaran_kinerja_id      = $ah->id;
                    $rk->label                   = $x->label;
                    $rk->jenis_rencana_kinerja   = "kinerja_utama";
                    $rk->type_kinerja_utama      = "perjanjian_kinerja";
                    $rk->parent_id               = $x->id;
                    $rk->save();

                    if ( sizeof($x->indikator) ){
                        foreach( $x->indikator AS $y ){
                            $iki   = new IndikatorKinerjaIndividu;
                            $iki->rencana_kinerja_id      = $rk->id;
                            $iki->label                   = $y->label;
                            $iki->type_target             = $y->type_target;
                            $iki->target_min              = $y->target_min;
                            $iki->target_max              = $y->target_max;
                            $iki->satuan_target           = $y->satuan_target;
                            $iki->keterangan_target       = "";
                            $iki->save();
                        }

                    }


                }
            } else  if (($ah->jenis_jabatan_skp == "JABATAN ADMINISTRASI")|($ah->jenis_jabatan_skp == "JABATAN FUNGSIONAL")){
                //mau coba memasukan rencana hasil kerja pada matrik

                $peran = MatriksPeran::with('MatriksHasil')
                                        ->WHERE('pegawai->nip', '=', $request->nipPegawaiYangDinilai)
                                        ->WHERE('skpd_id','=',$request->skpdId)
                                        ->WHERE('periode','=',$request->periodeLabel)
                                        ->first();
                
                if ( $peran ){
                    foreach( $peran->MatriksHasil AS $y ){
                        $rk    = new RencanaKinerja;
                        $rk->sasaran_kinerja_id      = $ah->id;
                        $rk->label                   = $y->label;
                        $rk->jenis_rencana_kinerja   = "kinerja_utama";
                        $rk->matriks_hasil_id        = $y->id;
                        $rk->save();

                    }
                }else{
                    return \Response::make(['message' => "Jabatan tidak ditemukan pada matrik"], 200);
                }




            }




            return \Response::make("Sukses", 200);
        } else {
            return \Response::make(['message' => "Terjadi kesalahan saat menyimpan SKP"], 500);
        }
    }

    public function PejabatPenilaiStore(Request $request)
    {

        $messages = [

                    'sasaranKinerjaId.required'              => 'Harus diisi',


        ];



        $validator = Validator::make(
            $request->all(),
            [
                    'sasaranKinerjaId'    => 'required',

            ],
            $messages
        );

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 422);
        }

        $pejabat_penilai = [
            "nama"              => $request->namaLengkapPejabatPenilai,
            "nip"               => $request->nipPejabatPenilai,
            "jabatan"           => $request->jabatanPejabatPenilai,
            "jabatan_id"        => $request->jabatanSimAsnPejabatPenilaiId,
            "pangkat"           => $request->pangkatPejabatPenilai,
            "golongan"          => $request->golonganPejabatPenilai,
            "instansi"          => $request->instansiPejabatPenilai,
        ];

        $update  = SasaranKinerja::find($request->sasaranKinerjaId);

        $update->pejabat_penilai  =  json_encode($pejabat_penilai);

        if ($update->save()) {
            return \Response::make("sukses", 200);
        } else {
            return \Response::make(['message' => "Terjadi kesalahan saat Update Data"], 500);
        }
    }

    public function AtasanPejabatPenilaiStore(Request $request)
    {

        $messages = [

                    'sasaranKinerjaId.required'              => 'Harus diisi',


        ];



        $validator = Validator::make(
            $request->all(),
            [
                    'sasaranKinerjaId'    => 'required',

            ],
            $messages
        );

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 422);
        }

        $atasan_pejabat_penilai = [
            "nama"              => $request->namaLengkapAtasanPejabatPenilai,
            "nip"               => $request->nipAtasanPejabatPenilai,
            "jabatan"           => $request->jabatanAtasanPejabatPenilai,
            "jabatan_id"        => $request->jabatanSimAsnAtasanPejabatPenilaiId,
            "pangkat"           => $request->pangkatAtasanPejabatPenilai,
            "golongan"          => $request->golonganAtasanPejabatPenilai,
            "instansi"          => $request->instansiAtasanPejabatPenilai,
        ];

        $update  = SasaranKinerja::find($request->sasaranKinerjaId);

        $update->atasan_pejabat_penilai  =  json_encode($atasan_pejabat_penilai);

        if ($update->save()) {
            return \Response::make("sukses", 200);
        } else {
            return \Response::make(['message' => "Terjadi kesalahan saat Update Data"], 500);
        }
    }

    public function PejabatSasaranKinerjaStore(Request $request)
    {

        $messages = [


                    'periodeLabel.required'             => 'Harus diisi',
                    'dateFrom.required'                 => 'Harus diisi',
                    'dateTo.required'                   => 'Harus diisi',
                    'matriksPeranId.required'           => 'Harus diisi',


                    'perjanjianKinerjaId.required'      => 'Harus diisi',
                    'userId.required'                   => 'Harus diisi',
                    'simpegId.required'                 => 'Harus diisi',
                    'jenisJabatanSkp.required'        => 'Harus diisi',
                    'skpdId.required'                   => 'Harus diisi',
                    'unitKerjaId.required'              => 'Harus diisi',
                    'pnsId'                             => 'Harus diisi',

                    'golonganPejabat'                   => 'Harus diisi',
                    'instansiPejabat.required'          => 'Harus diisi',
                    'jabatanAktifId.required'           => 'Harus diisi',
                    'jabatanPejabat.required'           => 'Harus diisi',
                    'jabatanSimAsnPejabatId.required'   => 'Harus diisi',
                    'jabatanSimAsnPejabatJenis.required'=> 'Harus diisi',

                    'nipPejabat.required'               => 'Harus diisi',
                    //'pangkatPejabat'                    => 'Harus diisi',
                    'namaLengkapPejabat.required'       => 'Harus diisi',
                    //'skpPejabatPenilaiId'        => 'Harus diisi',

        ];



        $validator = Validator::make(
            $request->all(),
            [
                    'periodeLabel'                          => 'required',
                    'dateFrom'                              => 'required',
                    'dateTo'                                => 'required',
                    'matriksPeranId'                        => 'required',

                    'perjanjianKinerjaId'                   => 'required',
                    'userId'                                => 'required',
                    'simpegId'                              => 'required',
                    'jenisJabatanSkp'                     => 'required',
                    'skpdId'                                => 'required',
                    'unitKerjaId'                           => 'required',
                    'pnsId'                                 => 'required',

                    'golonganPejabat'                       => 'required',
                    'instansiPejabat'                       => 'required',
                    'jabatanAktifId'                        => 'required',
                    'jabatanPejabat'                        => 'required',
                    'jabatanSimAsnPejabatId'                => 'required',
                    'jabatanSimAsnPejabatJenis'             => 'required',

                    'nipPejabat'                            => 'required',
                    //'pangkatPejabat'                        => 'required',
                    'namaLengkapPejabat'                    => 'required',
                    //'skpPejabatPenilaiId'            => 'required'

            ],
            $messages
        );

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 422);
        }



        $periode_penilaian = [
            "periode_pk"        => $request->perjanjianKinerjaId,
            "tahun"             => date('Y', strtotime($request->periodeLabel)),
            "tgl_mulai"         => date('Y-m-d', strtotime($request->dateFrom)),
            "tgl_selesai"       => date('Y-m-d', strtotime($request->dateTo)),
        ];

        $pegawai_yang_dinilai = [
            "nama"              => $request->namaLengkapPejabat,
            "nip"               => $request->nipPejabat,
            "jabatan"           => $request->jabatanPejabat,
            "jabatan_id"        => $request->jabatanSimAsnPejabatId,
            "pangkat"           => $request->pangkatPejabat,
            "golongan"          => $request->golonganPejabat,
            "instansi"          => $request->instansiPejabat,
        ];

        //SKP atasan
        /* $skp_atasan = SasaranKinerja::WHERE('id',$request->skpPejabatPenilaiId)->first();

        if ( $skp_atasan == null ) {
            return response()->json(['message' => 'SKP Atasan tidak ditemukan'], 422);
        }else{
            $pejabat_penilai = json_decode($skp_atasan->pegawai_yang_dinilai);
        } */





        $ah    = new SasaranKinerja;
        $ah->user_id                    = $request->userId;
        $ah->matriks_peran_id           = $request->matriksPeranId;
        $ah->jabatan_aktif_id           = $request->jabatanAktifId;
        $ah->skpd_id                    = $request->skpdId;
        $ah->unit_kerja_id              = $request->unitKerjaId;
        $ah->simpeg_id                  = $request->simpegId;
        $ah->pns_id                     = $request->pnsId;
        $ah->perjanjian_kinerja_id      = $request->perjanjianKinerjaId;
        $ah->jenis_jabatan_skp          = $request->jenisJabatanSkp;
        $ah->periode_penilaian          = json_encode($periode_penilaian);
        $ah->pegawai_yang_dinilai       = json_encode($pegawai_yang_dinilai);
        //$ah->pejabat_penilai            = json_encode($pejabat_penilai);;


        if ($ah->save()) {

            //masukan Rencana kinerja



            return \Response::make(['message' => "Berhasil menambahkan pejabat"], 200);
        } else {
            return \Response::make(['message' => "Terjadi kesalahan saat menyimpan SKP"], 500);
        }
    }


    public function SasaranKinerjaDestroy(Request $request)
    {

        $messages = [
            'id.required'   => 'Harus diisi',
        ];
        $validator = Validator::make(
                        $request->all(),
                        array(
                            'id'   => 'required',
                        ),
                        $messages
        );
        if ( $validator->fails() ){
            //$messages = $validator->messages();
            return response()->json(['errors'=>$validator->messages()],422);

        }


        $sr    = SasaranKinerja::find($request->id);
        if (is_null($sr)) {
            return \Response::make(['message' => "Sasaran Kinerja tidak ditemukan"], 500);
            //return response('Hello World', 200)->header('Content-Type', 'text/plain');
        }


        if ( $sr->delete()){
            return \Response::make('sukses', 200);
        }else{
            return \Response::make('error', 500);
        }


    }

    public function print(Request $request)
    {
        //kita siapkan data nya dulu kang
        $id_skp = $request->id_skp;
        $skp = SasaranKinerja::WHERE('id',$id_skp)->first();

        if (!$skp){
            return response()->json("SKP tidak ditemukan", 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],JSON_UNESCAPED_UNICODE);
        }

        //PERIODE PENILAIAN
        $pp = json_decode($skp->periode_penilaian);
        $periode_penilaian = Pustaka::fullCapitalDate($pp->tgl_mulai).' s.d '.Pustaka::fullCapitalDate($pp->tgl_selesai);

        //TANGGAL footnote
        $tanggal = 'Karawang, '.Pustaka::CapitalDate($skp->created_at);

        

        //return response()->json($detail_skp, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],JSON_UNESCAPED_UNICODE);
        //PEGAWAI YANG DINILAI 
        $data_a = $skp->pegawai_yang_dinilai;
        $pegawai_yang_dinilai = array(
            "nama"          => ($data_a)?json_decode($data_a)->nama:'-',
            "nip"           => ($data_a)?json_decode($data_a)->nip:'-',
            "pangkat"       => ($data_a)?json_decode($data_a)->pangkat:'-',
            "jabatan"       => ($data_a)?json_decode($data_a)->jabatan:'-',
            "unit_kerja"    => ($data_a)?json_decode($data_a)->instansi:'-',
          );

     

        //PEJABAT PENILAI
        $data_b = $skp->pejabat_penilai;
        $pejabat_penilai = array(
            "nama"          => ($data_b)?json_decode($data_b)->nama:'-',
            "nip"           => ($data_b)?json_decode($data_b)->nip:'-',
            "pangkat"       => ($data_b)?json_decode($data_b)->pangkat:'-',
            "jabatan"       => ($data_b)?json_decode($data_b)->jabatan:'-',
            "unit_kerja"    => ($data_b)?json_decode($data_b)->instansi:'-',
          );
        //ATASAN PEJABAT PENILAI
        $data_c = $skp->atasan_pejabat_penilai;
        $atasan_pejabat_penilai = array(
            "nama"          => ($data_c)?json_decode($data_c)->nama:'-',
            "nip"           => ($data_c)?json_decode($data_c)->nip:'-',
            "pangkat"       => ($data_c)?json_decode($data_c)->pangkat:'-',
            "jabatan"       => ($data_c)?json_decode($data_c)->jabatan:'-',
            "unit_kerja"    => ($data_c)?json_decode($data_c)->instansi:'-',
          );

        //RENCANA KERJA
        $rencana_kerja = array();
        $data = RencanaKinerja::with(['IndikatorKinerjaIndividu','Parent'])
                            ->WHERE('sasaran_kinerja_id',$id_skp)
                            ->get();

        foreach( $data AS $x ){
             //jika kinerja utama
            if ( $x->jenis_rencana_kinerja == "kinerja_utama"){
                if ( $skp->jenisJabatan != "JABATAN PIMPINAN TINGGI"){
                    //JIKA BUKAN JPT
                    if ( $x->MatriksHasil != null  ){
                        if ( $x->MatriksHasil->level == 'S2' ){
                            $ss_data  = SasaranStrategis::WHERE('id',$x->MatriksHasil->pk_ss_id)->first();
                            $rencana_kerja_pimpinan = ( $ss_data != null ) ? $ss_data->label : "";
                        }else{
                            $mh_data  = MatriksHasil::WHERE('id',$x->MatriksHasil->parent_id)->first();
                            $rencana_kerja_pimpinan = ( $mh_data != null ) ? $mh_data->label : "";
                        }
                    }else{
                        $rencana_kerja_pimpinan = null;
                    }


                }else{
                    $rencana_kerja_pimpinan = null;
                }
            }else{
                $rencana_kerja_pimpinan = null;
            }

            if ( sizeof($x->IndikatorKinerjaIndividu) ){
                foreach( $x->IndikatorKinerjaIndividu AS $y ){
                    //type target
                    if ( $y->type_target == '1' ){
                        $target = $y->target_max.' '.$y->satuan_target;
                    }else if ( $y->type_target == '2' ){
                        $target = $y->target_min.' - '.$y->target_max.' '.$y->satuan_target;
                    }

                    $rencana_kerja[] = array(
                        'rencana_hasil_kerja_atasan'   => $rencana_kerja_pimpinan,
                        'rencana_hasil_kerja'          => $x->label,
                        'aspek'                        => ucfirst($y->aspek),
                        'indikator_kinerja_individu'   => $y->label,
                        'target'                       => $target,
                        'perspektif'                   => $y->perspektif,
                        'jenis_jabatan_skp'            => $skp->jenisJabatan,
                    );
                }
            }else{
    
                $rencana_kerja[] = array(
                    'rencana_hasil_kerja_atasan'   => $rencana_kerja_pimpinan,
                    'rencana_hasil_kerja'          => $x->label,
                    'aspek'                        => '',
                    'indikator_kinerja_individu'   => '',
                    'target'                       => '',
                    'perspektif'                   => '',
                    'jenis_jabatan_skp'            => $skp->jenisJabatan,
                );
            }
        }            
        
        

        //===================== PERILAKU KERJA ==========================//
         //Perilaku kerja
        //pengulangan core  value dari 1 s.d 7
        $perilaku_kerja = array();
        for ($i = 1; $i <= 7; $i++) {
            $ekspektasi_pimpinan = SasaranKinerjaPerilakuKerja::SELECT('id','label')
                                ->WHERE('sasaran_kinerja_id','=', $id_skp)
                                ->WHERE('core_value_id','=', $i)
                                ->get();

            
            array_push($perilaku_kerja, $ekspektasi_pimpinan);
        }


        //return response()->json($perilaku_kerja, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],JSON_UNESCAPED_UNICODE);


        //COVER
        $m = new Merger();
        $pdf = PDF::loadView('printouts.skp_cover',
                        [ 
                            'pegawai_yang_dinilai'  => $pegawai_yang_dinilai,
                            'pejabat_penilai'       => $pejabat_penilai,
                            'atasan_pejabat_penilai'=> $atasan_pejabat_penilai,

                            'periode_penilaian'     => $periode_penilaian,
                                                    
                        ])->setPaper('a4', 'potrait');
        

        $m->addRaw($pdf->output());

        $pdf2 = PDF::loadView('printouts.skp_hasil_kerja',
                            [ 
                                'pegawai_yang_dinilai'  => $pegawai_yang_dinilai,
                                'pejabat_penilai'       => $pejabat_penilai,
                                'periode_penilaian'     => $periode_penilaian,
                                'rencana_hasil_kerja'   => $rencana_kerja,
                                'perilaku_kerja'        => $perilaku_kerja,
                                'tanggal'               => $tanggal,
                                                        
                            ])->setPaper('a4', 'landscape');

      
        $nama_file = 'skp_'.json_decode($data_a)->nip.'.pdf';
        $m->addRaw($pdf2->output());
        return response($m->merge())
                ->withHeaders([
                    'Content-Type' => 'application/pdf',
                    'Cache-Control' => 'no-store, no-cache',
                    //untuk download
                    //'Content-Disposition' => 'attachment; filename="'.$nama_file,
                ]); 
    }

    public function print_jpt(Request $request)
    {
        //kita siapkan data nya dulu kang
        $id_skp = $request->id_skp;
        $skp = SasaranKinerja::WHERE('id',$id_skp)->first();

        if (!$skp){
            return response()->json("SKP tidak ditemukan", 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],JSON_UNESCAPED_UNICODE);
        }

        //PERIODE PENILAIAN
        $pp = json_decode($skp->periode_penilaian);
        $periode_penilaian = Pustaka::fullCapitalDate($pp->tgl_mulai).' s.d '.Pustaka::fullCapitalDate($pp->tgl_selesai);

        //TANGGAL footnote
        $tanggal = 'Karawang, '.Pustaka::CapitalDate($skp->created_at);

        

        //return response()->json($detail_skp, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],JSON_UNESCAPED_UNICODE);
        //PEGAWAI YANG DINILAI 
        $data_a = $skp->pegawai_yang_dinilai;
        $pegawai_yang_dinilai = array(
            "nama"          => ($data_a)?json_decode($data_a)->nama:'-',
            "nip"           => ($data_a)?json_decode($data_a)->nip:'-',
            "pangkat"       => ($data_a)?json_decode($data_a)->pangkat:'-',
            "jabatan"       => ($data_a)?json_decode($data_a)->jabatan:'-',
            "unit_kerja"    => ($data_a)?json_decode($data_a)->instansi:'-',
          );

     

        //PEJABAT PENILAI
        $data_b = $skp->pejabat_penilai;
        $pejabat_penilai = array(
            "nama"          => ($data_b)?json_decode($data_b)->nama:'-',
            "nip"           => ($data_b)?json_decode($data_b)->nip:'-',
            "pangkat"       => ($data_b)?json_decode($data_b)->pangkat:'-',
            "jabatan"       => ($data_b)?json_decode($data_b)->jabatan:'-',
            "unit_kerja"    => ($data_b)?json_decode($data_b)->instansi:'-',
          );
        //ATASAN PEJABAT PENILAI
        $data_c = $skp->atasan_pejabat_penilai;
        $atasan_pejabat_penilai = array(
            "nama"          => ($data_c)?json_decode($data_c)->nama:'-',
            "nip"           => ($data_c)?json_decode($data_c)->nip:'-',
            "pangkat"       => ($data_c)?json_decode($data_c)->pangkat:'-',
            "jabatan"       => ($data_c)?json_decode($data_c)->jabatan:'-',
            "unit_kerja"    => ($data_c)?json_decode($data_c)->instansi:'-',
          );

        //RENCANA KERJA
        $rencana_kerja = array();
        $data = RencanaKinerja::with(['IndikatorKinerjaIndividu','Parent'])
                            ->WHERE('sasaran_kinerja_id',$id_skp)
                            ->get();

        foreach( $data AS $x ){
             //jika kinerja utama
            if ( $x->jenis_rencana_kinerja == "kinerja_utama"){
                if ( $skp->jenisJabatan != "JABATAN PIMPINAN TINGGI"){
                    //JIKA BUKAN JPT
                    if ( $x->MatriksHasil != null  ){
                        if ( $x->MatriksHasil->level == 'S2' ){
                            $ss_data  = SasaranStrategis::WHERE('id',$x->MatriksHasil->pk_ss_id)->first();
                            $rencana_kerja_pimpinan = ( $ss_data != null ) ? $ss_data->label : "";
                        }else{
                            $mh_data  = MatriksHasil::WHERE('id',$x->MatriksHasil->parent_id)->first();
                            $rencana_kerja_pimpinan = ( $mh_data != null ) ? $mh_data->label : "";
                        }
                    }else{
                        $rencana_kerja_pimpinan = null;
                    }


                }else{
                    $rencana_kerja_pimpinan = null;
                }
            }else{
                $rencana_kerja_pimpinan = null;
            }

            if ( sizeof($x->IndikatorKinerjaIndividu) ){
                foreach( $x->IndikatorKinerjaIndividu AS $y ){
                    //type target
                    if ( $y->type_target == '1' ){
                        $target = $y->target_max.' '.$y->satuan_target;
                    }else if ( $y->type_target == '2' ){
                        $target = $y->target_min.' - '.$y->target_max.' '.$y->satuan_target;
                    }

                    $rencana_kerja[] = array(
                        'rencana_hasil_kerja_atasan'   => $rencana_kerja_pimpinan,
                        'rencana_hasil_kerja'          => $x->label,
                        'aspek'                        => ucfirst($y->aspek),
                        'indikator_kinerja_individu'   => $y->label,
                        'target'                       => $target,
                        'perspektif'                   => $y->perspektif,
                        'jenis_jabatan_skp'            => $skp->jenisJabatan,
                    );
                }
            }else{
    
                $rencana_kerja[] = array(
                    'rencana_hasil_kerja_atasan'   => $rencana_kerja_pimpinan,
                    'rencana_hasil_kerja'          => $x->label,
                    'aspek'                        => '',
                    'indikator_kinerja_individu'   => '',
                    'target'                       => '',
                    'perspektif'                   => '',
                    'jenis_jabatan_skp'            => $skp->jenisJabatan,
                );
            }
        }            
        
        

        //===================== PERILAKU KERJA ==========================//
         //Perilaku kerja
        //pengulangan core  value dari 1 s.d 7
        $perilaku_kerja = array();
        for ($i = 1; $i <= 7; $i++) {
            $ekspektasi_pimpinan = SasaranKinerjaPerilakuKerja::SELECT('id','label')
                                ->WHERE('sasaran_kinerja_id','=', $id_skp)
                                ->WHERE('core_value_id','=', $i)
                                ->get();

            
            array_push($perilaku_kerja, $ekspektasi_pimpinan);
        }


        //return response()->json($perilaku_kerja, 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],JSON_UNESCAPED_UNICODE);


        //COVER
        $m = new Merger();
        $pdf = PDF::loadView('printouts.skp_cover',
                        [ 
                            'pegawai_yang_dinilai'  => $pegawai_yang_dinilai,
                            'pejabat_penilai'       => $pejabat_penilai,
                            'atasan_pejabat_penilai'=> $atasan_pejabat_penilai,

                            'periode_penilaian'     => $periode_penilaian,
                                                    
                        ])->setPaper('a4', 'potrait');
        

        $m->addRaw($pdf->output());

        $pdf2 = PDF::loadView('printouts.skp_hasil_kerja_jpt',
                            [ 
                                'pegawai_yang_dinilai'  => $pegawai_yang_dinilai,
                                'pejabat_penilai'       => $pejabat_penilai,
                                'periode_penilaian'     => $periode_penilaian,
                                'rencana_hasil_kerja'   => $rencana_kerja,
                                'perilaku_kerja'        => $perilaku_kerja,
                                'tanggal'               => $tanggal,
                                                        
                            ])->setPaper('a4', 'landscape');

      
        $nama_file = 'skp_jpt_'.json_decode($data_a)->nip.'.pdf';
        $m->addRaw($pdf2->output());
        return response($m->merge())
                ->withHeaders([
                    'Content-Type' => 'application/pdf',
                    'Cache-Control' => 'no-store, no-cache',
                    //untuk download
                    //'Content-Disposition' => 'attachment; filename="'.$nama_file,
                ]); 
    }




}
