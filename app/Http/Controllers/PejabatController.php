<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RenjaPejabat;

use Illuminate\Pagination\Paginator;

use GuzzleHttp\Client;

class PejabatController extends Controller
{


    public function list(Request $request)
    {
        $tim_kerja_id = ($request->tim_kerja_id)? $request->tim_kerja_id : 1 ;

        $query = RenjaPejabat::SELECT(
                                    'id',
                                    'nama_lengkap',
                                    'jabatan',
                                    'pegawai->nip AS nip',
                                    'pegawai->photo AS photo'
                                )
                                ->WHERE('tim_kerja_id','=',$tim_kerja_id)
                                ->GET();


        $response = array();
        foreach( $query AS $x ){
            $h['id']                = $x->id;
            $h['nama_lengkap']      = $x->nama_lengkap;
            $h['jabatan']           = $x->jabatan;
            $h['nip']               = $x->nip;
            $h['photo']             = $x->photo;



            array_push($response, $h);
        }
        $response = collect($response)->sortBy('id')->values();
        return $response;
    }

    public function child(Request $request)
    {
        $query = TimKerja::
                                    WHERE('parent_id','=',$request->parent_id)
                                    ->SELECT(
                                        'id',
                                        'label',
                                        'parent_id',
                                        'renja_id'
                                    )
                                    ->get();
        $response = array();
        foreach( $query AS $x ){
                $h['id']            = $x->id;
                $h['label']         = $x->label;
                $h['parent_id']     = $x->parent_id;
                $h['renja_id']      = $x->renja_id;
                //cek apakah node ini memiliki child atau tidak
                $child = TimKerja::WHERE('parent_id','=',$x->id)->exists();
                if ( $child ){
                    $h['leaf']      = false;
                }else{
                    $h['leaf']      = true;
                }
                array_push($response, $h);
        }
        $response = collect($response)->sortBy('id')->values();
        return $response;
    }






}
