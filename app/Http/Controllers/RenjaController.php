<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Renja;
use App\Models\RenjaPejabat;

use Illuminate\Pagination\Paginator;

use GuzzleHttp\Client;

class RenjaController extends Controller
{




    public function list(Request $request)
    {
        $page = ($request->page)? $request->page : 1 ;
        $limit = ($request->limit)? $request->limit : 50 ;

        $skpd_id = ($request->skpd_id)? $request->skpd_id : null ;

        Paginator::currentPageResolver(fn() => $page );

        $query =  Renja::select(
                            'renja.id AS renja_id',
                            'renja.periode->tahun AS periode',
                            'renja.skpd->id AS skpd_id',
                            'renja.skpd->nama AS nama_skpd',
                            'renja.skpd->singkatan AS singkatan_skpd',
                            'renja.status AS status',
                            'renja.created_at AS created_at',

                        )
                        ->orderBY('renja_id', 'desc');



        if($skpd_id != null ) {
            $query->where('skpd_id','=', $skpd_id );
        }

        if($request->search) {
            $query->where('skpd_id','LIKE', '%'.$request->search.'%');
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

    public function personal_renja_list(Request $request)
    {
        $page = ($request->page)? $request->page : 1 ;
        $limit = ($request->limit)? $request->limit : 50 ;
        $user_id = ($request->user_id)? $request->user_id : null ;
        Paginator::currentPageResolver(fn() => $page );

        $query = RenjaPejabat::with(array('timKerja' => function($query) {
                                    $query->select('id','renja_id','label','parent_id')
                                    ->with('renja:id,periode->tahun AS periode,skpd->id AS id_skpd,skpd->nama AS nama_skpd,status');
                                }))
                                ->SELECT(
                                        'id',
                                        'nama_lengkap',
                                        'user_id',
                                        'tim_kerja_id',
                                        'jabatan'
                                        )
                                ->WHERE('user_id',$user_id);


        if($request->search) {
            $query->where('nama_lengkap','LIKE', '%'.$request->search.'%');
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



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
