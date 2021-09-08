<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimKerja;
use App\Models\RenjaPejabat;
use App\Models\RencanaKinerja;

use Illuminate\Pagination\Paginator;

use GuzzleHttp\Client;

class TimKerjaController extends Controller
{

    public function tim_kerja(Request $request)
    {

        $response = array();
        $response['pejabat'] = array();
        $response['rencana_kinerja'] = array();



        $tim_kerja = TimKerja::
                                    WHERE('id','=',$request->id)
                                    ->SELECT(
                                                'id',
                                                'label',
                                                'parent_id',
                                                'renja_id'
                                            )
                                    ->first();
        $response['tim_kerja'] = $tim_kerja;

        $query = RenjaPejabat::SELECT(
                                        'id',
                                        'nama_lengkap',
                                        'jabatan',
                                        'pegawai->nip AS nip',
                                        'pegawai->photo AS photo'
                                    )
                                    ->WHERE('tim_kerja_id','=',$request->id)
                                    ->GET();



        foreach( $query AS $x ){
            $h['id']                = $x->id;
            $h['nama_lengkap']      = $x->nama_lengkap;
            $h['jabatan']           = $x->jabatan;
            $h['nip']               = $x->nip;
            $h['photo']             = $x->photo;

            array_push($response['pejabat'], $h);
        }

        $query2 = RencanaKinerja::SELECT(
                                        'id',
                                        'label',
                                        'parent_id',
                                        'added_by'
                                    )
                                    ->WHERE('tim_kerja_id','=',$request->id)
                                    ->GET();



        foreach( $query2 AS $y ){
            $r['id']            = $y->id;
            $r['label']         = $y->label;
            $r['parent_id']     = $y->parent_id;
            $r['added_by']      = $y->added_by;

            array_push($response['rencana_kinerja'], $r);
        }


        return $response;

    }

    public function tim_kerja_level_0(Request $request)
    {
        $renja_id = ($request->renja_id)? $request->renja_id : null ;

        $query = TimKerja::
                                    WHERE('parent_id','=','0')
                                    ->SELECT(
                                                'id',
                                                'label',
                                                'parent_id',
                                                'renja_id'
                                            );
        if($renja_id != null ) {
            $query->where('renja_id','=', $renja_id );
        }

        $response = array();
        foreach( $query->get() AS $x ){
            $h['id']            = $x->id;
            $h['label']         = $x->label;
            $h['parent_id']     = $x->parent_id;

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
