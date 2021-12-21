<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SasaranKinerja;

use App\Services\Datatables\SasaranKinerjaDataTable;

use Validator;

class SasaranKinerjaController extends Controller
{

    public function SasaranKinerjaList(Request $request)
    {
        $searchDatas = new SasaranKinerjaDataTable($request->all());
        $data = $searchDatas->search();
        return $data;
    }


    public function SasaranKinerjaStore(Request $request)
    {

        $messages = [

                    'dateFrom.required'              => 'Harus diisi',
                    'dateTo.required'                => 'Harus diisi',
                    'periodePkId.required'           => 'Harus diisi',
                    'userId.required'                => 'Harus diisi',
                    'simpegId.required'              => 'Harus diisi',
                    'jenisJabatanSkp.required'       => 'Harus diisi',
                    //'skpdId.required'                => 'Harus diisi',
                    //'unitKerjaId.required'           => 'Harus diisi',
                    //'pnsId'                 => 'Harus diisi',

                    //'golonganPegawaiYangDinilai'  => 'Harus diisi',
                    //'golonganPejabatPenilaiKinerja'  => 'Harus diisi',
                    'instansiPegawaiYangDinilai.required'           => 'Harus diisi',
                    //'instansiPejabatPenilaiKinerja.required'        => 'Harus diisi',
                    'jabatanAktifPegawaiYangDinilaiId.required'     => 'Harus diisi',
                    'jabatanAktifPejabatPenilaiKinerjaId.required'  => 'Harus diisi',
                    'jabatanPegawaiYangDinilai.required'            => 'Harus diisi',
                    'jabatanPejabatPenilaiKinerja.required'         => 'Harus diisi',
                    'jabatanSimAsnPegawaiYangDinilaiId.required'    => 'Harus diisi',
                    'jabatanSimAsnPegawaiYangDinilaiJenis.required' => 'Harus diisi',
                    'jabatanSimAsnPejabatPenilaiKinerjaId.required' => 'Harus diisi',
                    'jabatanSimAsnPejabatPenilaiKinerjaJenis.required'  => 'Harus diisi',

                    'nipPegawaiYangDinilai.required'                => 'Harus diisi',
                    //'nipPejabatPenilaiKinerja.required'             => 'Harus diisi',
                    //'pangkatPegawaiYangDinilai'  => 'Harus diisi',
                    //'pangkatPejabatPenilaiKinerja'  => 'Harus diisi',
                    'namaLengkapPegawaiYangDinilai.required'        => 'Harus diisi',
                    'namaLengkapPejabatPenilaiKinerja.required'     => 'Harus diisi',

        ];



        $validator = Validator::make(
            $request->all(),
            [
                    'dateFrom'                              => 'required',
                    'dateTo'                                => 'required',
                    'periodePkId'                           => 'required',
                    'userId'                                => 'required',
                    'simpegId'                              => 'required',
                    'jenisJabatanSkp'                       => 'required',
                    //'skpdId'                                => 'required',
                    //'unitKerjaId'                           => 'required',
                    //'pnsId'                               => 'required',

                    //'golonganPegawaiYangDinilai'          => 'required',
                    //'golonganPejabatPenilaiKinerja'       => 'required',
                    'instansiPegawaiYangDinilai'            => 'required',
                    //'instansiPejabatPenilaiKinerja'         => 'required',
                    'jabatanAktifPegawaiYangDinilaiId'      => 'required',
                    'jabatanAktifPejabatPenilaiKinerjaId'  => 'required',
                    'jabatanPegawaiYangDinilai'             => 'required',
                    'jabatanPejabatPenilaiKinerja'          => 'required',
                    'jabatanSimAsnPegawaiYangDinilaiId'     => 'required',
                    'jabatanSimAsnPegawaiYangDinilaiJenis'  => 'required',
                    'jabatanSimAsnPejabatPenilaiKinerjaId'  => 'required',
                    'jabatanSimAsnPejabatPenilaiKinerjaJenis'  => 'required',

                    'nipPegawaiYangDinilai'                 => 'required',
                    //'nipPejabatPenilaiKinerja'              => 'required',
                    //'pangkatPegawaiYangDinilai'           => 'required',
                    //'pangkatPejabatPenilaiKinerja'        => 'required',
                    'namaLengkapPegawaiYangDinilai'         => 'required',
                    'namaLengkapPejabatPenilaiKinerja'      => 'required',

            ],
            $messages
        );

        if ($validator->fails()) {
            return response()->json(['message' => $validator->messages()], 422);
        }

        $periode_penilaian = [
            "periode_pk"        => $request->periodePkId,
            "tahun"             => $request->periodeLabel,
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

        $pejabat_penilai_kinerja = [
            "nama"              => $request->namaLengkapPejabatPenilaiKinerja,
            "nip"               => $request->nipPejabatPenilaiKinerja,
            "jabatan"           => $request->jabatanPejabatPenilaiKinerja,
            "jabatan_id"        => $request->jabatanSimAsnPejabatPenilaiKinerjaId,
            "pangkat"           => $request->pangkatPejabatPenilaiKinerja,
            "golongan"          => $request->golonganPejabatPenilaiKinerja,
            "instansi"          => $request->instansiPejabatPenilaiKinerja,
        ];




        $ah    = new SasaranKinerja;
        $ah->user_id                    = $request->userId;
        $ah->jabatan_aktif_id           = $request->jabatanAktifPegawaiYangDinilaiId;
        $ah->skpd_id                    = $request->skpdId;
        $ah->unit_kerja_id              = $request->unitKerjaId;
        $ah->simpeg_id                  = $request->simpegId;
        $ah->pns_id                     = $request->pnsId;
        $ah->periode_pk_id              = $request->periodePkId;
        $ah->jenis_jabatan_skp          = $request->jenisJabatanSkp;
        $ah->periode_penilaian          = json_encode($periode_penilaian);
        $ah->pegawai_yang_dinilai       = json_encode($pegawai_yang_dinilai);
        $ah->pejabat_penilai_kinerja    = json_encode($pejabat_penilai_kinerja);


        if ($ah->save()) {

            return \Response::make($ah, 200);
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
