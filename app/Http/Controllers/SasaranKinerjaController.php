<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SasaranKinerja;
use App\Models\RencanaKinerja;
use App\Models\IndikatorKinerjaIndividu;
use App\Models\SasaranStrategis;
use App\Models\PerjanjianKinerja;
use App\Models\MatriksPeran;

use App\Http\Resources\SasaranKinerja as SasaranKinerjaResource;


use App\Services\Datatables\SasaranKinerjaDataTable;

use Validator;

use GuzzleHttp\Client;

class SasaranKinerjaController extends Controller
{


    protected function detail_jabatan($id_jabatan)
    {
        //$token = env('SIMPEG_APP_TOKEN');
        $token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5M2NlNGNhOS1iNDczLTRmMzctYmQzNC0xYTAzYzVjNjFlNTgiLCJqdGkiOiI1Y2ZmYjcwNDgzNmVkZDMzZGM2MzlmNjYyN2RlNzRhNTkyY2I0OWU0NGJlNjEwZjY5MDBlYzRiZGFiZWZmNjMyNTliMTBkZmY4MjIzMmUxNSIsImlhdCI6MTYyNzgyNDU4NSwibmJmIjoxNjI3ODI0NTg1LCJleHAiOjE2NTkzNjA1ODUsInN1YiI6IiIsInNjb3BlcyI6WyIqIl19.GlE2yf4WJDtYvkThAjLYp37qxzN0NLqcS05YHFaMrQre4sY1izm1mVgO9Y-yCDasTz_0iZNrBOz1vMbw_PxsFxV9cBdPjIhuepcnk4vmN-iSe2fn0NP5rR3l2S-ZQmaT7HmPoABEcgGpTbKx_nOa43I6Y2UIzZsxPxQlDaRqfuSMBAtjm7QreGeD23K0CQQ4BMT3Nxe0iwqOwrYCjYTmu2m4JAhjmWN7amcJ95p_RdyrG0i-L9C-vGK1rO8LGtkAm4JjUmZLpGELjMpYMhIO11h2ok-TcBqaAJH_l92izQ5SHoR4UGmsyUiIygCmr8BD541zEOr4g6ITgk729lmXB_8faR0BQsbiGPA3NKq8YrHiJA63DP-iWmglE_QS4KTgSej6oU_5IM77kSXV90HTgPgzDTGgnDhtxWObcKTvCFsFlMR_aOePYEPTJj5qUIvy2lGgFJODwnr25Fc2m043tRDL_oQqBNXAl5rhTvjQxOWNylTqJw0rceuBCOOQZxPTw5L5OdOciecMfeRw3-E4dhPF7aWHwAdY4U71VAv78ACVG5NO2q4pxAoRBKEtS2pv0TgoAbkM3D-bH-C5b4XgUpylFUzIZlx4CN29FoMlwbuEbqi1hMUPu_GXOGXORXAzGHFoOIQAf8KHlO09RrQ2LUnB6-xPSvAJ-LbYZEwBGHg";
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


    public function SasaranKinerjaList(Request $request)
    {
        $searchDatas = new SasaranKinerjaDataTable($request->all());
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

                $peran = MatriksPeran::
                                        WHERE('pegawai->nip', '=', $request->nipPegawaiYangDinilai)
                                        ->WHERE('skpd_id','=',$request->skpdId)
                                        ->WHERE('periode','=',$request->periodeLabel)
                                        ->first();

                    foreach( $peran->MatriksHasil AS $y ){
                        $rk    = new RencanaKinerja;
                        $rk->sasaran_kinerja_id      = $ah->id;
                        $rk->label                   = $y->label;
                        $rk->jenis_rencana_kinerja   = "kinerja_utama";
                        $rk->save();

                    }



            }




            return \Response::make($peran->MatriksHasil, 200);
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
        }


        if ( $sr->delete()){
            return \Response::make('sukses', 200);
        }else{
            return \Response::make('error', 500);
        }


    }




}
