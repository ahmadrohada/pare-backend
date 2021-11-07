<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RenjaPejabat;
use App\Models\User;
use App\Models\RencanaSKP;

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



        $rp    = new RencanaSKP;
        $rp->renja_pejabat_id    = $request->renjaPejabatId;

        if ($rp->save()) {
            return \Response::make( $rp->id, 200);
        } else {
            return \Response::make('error', 500);
        }



    }

}
