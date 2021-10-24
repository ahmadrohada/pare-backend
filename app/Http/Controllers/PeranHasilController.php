<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimKerja;
use App\Models\RenjaPejabat;
use App\Models\RencanaKinerja;

use Illuminate\Pagination\Paginator;

use GuzzleHttp\Client;
use Validator;

class PeranHasilController extends Controller
{


    public function matrik(Request $request)
    {

        $data = TimKerja::  WHERE('id',$request->top_id)
                            ->SELECT('id','label','renja_id')
                            ->first();

        $rencana_kinerja = RencanaKinerja:: WHERE('tim_kerja_id',$request->top_id)
                                            ->SELECT('id','label')
                                            ->ORDERBY('id','ASC')
                                            ->GET();

       /*  $response['data'] = array();
        //array_push($response['data'], $data);

        foreach( $data->children AS $x ){
            $h['tim_kerja']             = $x->label;
            array_push($response['data'], $h);
            foreach( $x->children AS $y ){
                $i['tim_kerja']             = $y->label;
                array_push($response['data'], $i);
            }

        } */

        return $rencana_kinerja;


    }
}
