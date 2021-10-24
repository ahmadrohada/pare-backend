<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RenjaPejabat;
use App\Models\User;
use App\Models\RencanaSkp;

use Illuminate\Pagination\Paginator;

use GuzzleHttp\Client;
use Validator;

class RencanaSKPController extends Controller
{

    public function create(Request $request)
    {

        $data_1 = RenjaPejabat::with(array('timKerja' => function($query) {
                                $query->select('id','renja_id','label','parent_id')
                                ->with('parent:id,label')
                                ->with('renja:id,periode->tahun AS periode,skpd->id AS id_skpd,skpd->nama AS nama_skpd,status');
                            }))
                            ->WHERE('id',$request->renja_pejabat_id)
                            ->SELECT(   'id',
                                        'user_id',
                                        'tim_kerja_id',
                                        'pegawai_detail->nama_lengkap as nama_lengkap',
                                        'pegawai_detail->nip as nip',
                                        'jabatan_detail->nama as jabatan',
                                        'jabatan_detail->skpd->nama AS skpd'
                                    )
                            ->first();

        $parent_id = ( $data_1->timKerja->parent->id )? $data_1->timKerja->parent->id : 0 ;
        $data_2 = RenjaPejabat::WHERE('tim_kerja_id',$parent_id)
                            ->SELECT(
                                    'id',
                                    'tim_kerja_id',
                                    'pegawai_detail->nama_lengkap as nama_lengkap',
                                    'pegawai_detail->nip as nip',
                                    'jabatan_detail->nama as jabatan',
                                    'jabatan_detail->skpd->nama AS skpd'

                                )
                            ->first();



        $detailRencanaSKP = [
            ['title' => 'RENJA', 'label' => 'Periode '.$data_1->timKerja->renja->periode],
            ['title' => 'PERAN PADA TIM KERJA', 'label' => $data_1->timKerja->label],
            ['title' => 'SKPD', 'label' => $data_1->timKerja->renja->nama_skpd],
        ];

        $pegawai = [
            ['title' => 'NAMA LENGKAP', 'label' => $data_1->nama_lengkap],
            ['title' => 'NIP', 'label' => $data_1->nip],
            ['title' => 'JABATAN', 'label' => $data_1->jabatan],
            ['title' => 'SKPD', 'label' => $data_1->skpd],
        ];

        $pejabatPenilai = [
            ['title' => 'NAMA LENGKAP', 'label' => $data_2->nama_lengkap],
            ['title' => 'NIP', 'label' => $data_2->nip],
            ['title' => 'JABATAN', 'label' => $data_2->jabatan],
            ['title' => 'SKPD', 'label' => $data_2->skpd],
        ];




        return [
            'userId'             => $data_1->user_id,
            'renjaPejabatId'     => $request->renja_pejabat_id,
            'detailRencanaSKP'   => $detailRencanaSKP,
            'pegawai'            => $pegawai,
            'pejabatPenilai'     => $pejabatPenilai,

        ];
    }

    public function store(Request $request)
    {

        $messages = [
            'renjaPejabatId.required'    => 'Harus diisi',
            'userId.required'            => 'Harus diisi',

        ];



        $validator = Validator::make(
            $request->all(),
            [
                'renjaPejabatId'     => 'required',
                'userId'             => 'required',

            ],
            $messages
        );

        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }

        //cari  detail pejabat dan jabatan pada SIM-ASN
        $query =  User::WHERE('id','=',$request->userId)->first();

        return $query;

        /* //DETAIL PEGAWAI
        $pegawai_detail = array(
            'user_id'       => $request->userId,
            'simpeg_id'     => $simpeg['nip'],
            'jenis'         => $simpeg['jenis'],
            'pns_id'        => $simpeg['pns_id'],
            'nip_lama'      => $simpeg['nip_lama'],
            'nip'           => $simpeg['nip'],
            'photo'         => $simpeg['photo'],

            'nama_lengkap'  => $simpeg['nama_lengkap'],

        );

        //DETAIL JABATAN
        $jabatan_detail = array();
        $jabatan = "";
        foreach( $simpeg['jabatan'] AS $x ){
            if($x['id'] == $request->jabatanId){
                $jabatan_detail = $x;
                $jabatan = $x['nama'];
            }
        }


        $rp    = new RenjaPejabat;
        $rp->user_id             = $request->userId;
        $rp->tim_kerja_id        = $request->timKerjaId;
        $rp->nama_lengkap        = $simpeg['nama_lengkap'];
        $rp->nip                 = $simpeg['nip'];
        $rp->jabatan             = $jabatan;
        $rp->pegawai_detail      = json_encode($pegawai_detail);
        $rp->jabatan_detail      = json_encode($jabatan_detail);

        if ($rp->save()) {
            return \Response::make('sukses', 200);
        } else {
            return \Response::make('error', 500);
        } */



    }

}
