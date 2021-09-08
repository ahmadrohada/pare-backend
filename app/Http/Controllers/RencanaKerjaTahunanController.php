<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RencanaKerjaTahunan;

use Illuminate\Pagination\Paginator;

use GuzzleHttp\Client;

class RencanaKerjaTahunanController extends Controller
{

    public function level_0(Request $request)
    {
        $renja_id = ($request->renja_id)? $request->renja_id : null ;

        $query = RencanaKerjaTahunan::
                                    WHERE('level','=','s0')
                                    ->SELECT(
                                                'id',
                                                'label',
                                                'parent_id',
                                                'renja_id',
                                                'attribute'
                                            );
        if($renja_id != null ) {
            $query->where('renja_id','=', $renja_id );
        }

        $response = array();
        foreach( $query->get() AS $x ){
            $h['id']            = $x->id;
            $h['label']         = $x->label;
            $h['attribute']     = $x->attribute;
            $h['parent_id']     = $x->parent_id;

            //cek apakah node ini memiliki child atau tidak
            $child = RencanaKerjaTahunan::WHERE('parent_id','=',$x->id)->exists();
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
        $query = RencanaKerjaTahunan::
                                    WHERE('parent_id','=',$request->parent_id)
                                    ->SELECT(
                                        'id',
                                        'label',
                                        'parent_id',
                                        'renja_id',
                                        'attribute'
                                    )
                                    ->get();
        $response = array();
        foreach( $query AS $x ){
                $h['id']            = $x->id;
                $h['label']         = $x->label;
                $h['attribute']     = $x->attribute;
                $h['parent_id']     = $x->parent_id;
                //cek apakah node ini memiliki child atau tidak
                $child = RencanaKerjaTahunan::WHERE('parent_id','=',$x->id)->exists();
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

    public function treeView(Request $request)
    {
        $data = RencanaKerjaTahunan::with('child')->WHERE('attribute','=','tujuan')->get();
        return $data;
    }





    
    public function list(Request $request)
    {
        $page = ($request->page)? $request->page : 1 ;
        $limit = ($request->limit)? $request->limit : 50 ;

        $renja_id = ($request->renja_id)? $request->renja_id : null ;
        $attribute = ($request->attribute)? $request->attribute : 'tujuan' ;

        Paginator::currentPageResolver(fn() => $page );

        $query =  RencanaKerjaTahunan::select('*')->WHERE('attribute',$attribute);



        if($renja_id != null ) {
            $query->where('renja_id','=', $renja_id );
        }

        if($request->search) {
            $query->where('renja_id','LIKE', '%'.$request->search.'%');
        }

        $data = $query->paginate($limit);

        $pagination = array(
            'current_page'  => $data->currentPage(),
            'total_page'    => ( ($data->perPage() != 0 ) && ($data->total() != 0 )  ) ? Floor($data->total()/$data->count()) : 0,
            'data_per_page' => $data->count(),
            'limit'         => (int)$data->perPage(),
            'total'         => $data->total(),
        );

        $data = $data->items();


        return [

            'data'                 => $data,
            'pagination'           => $pagination

        ];
    }


}
