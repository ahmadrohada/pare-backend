<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PerjanjianKinerja;
use App\Models\RenjaPejabat;
use App\Models\Periode;
use App\Models\User;
use App\Models\TimKerja;
use App\Http\Traits\SotkRequest;

use App\Services\Datatables\RenjaDataTable;

use Illuminate\Pagination\Paginator;
use Validator;

class RenjaController extends Controller
{

    use SotkRequest;

    public function Renja(Request $request)
    {
        $searchDatas = new RenjaDataTable($request->all());
        $data = $searchDatas->search();
        //return PerjanjianKinerjaResource::collection($data);
        return $data;
    }



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
                                ->with(array('rencanaSkp' => function($query) {
                                    $query->select('id','renja_pejabat_id');
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



    public function create(Request $request)
    {



        //get active periode
        $periodes = Periode::SELECT('id AS id','tahun AS label')->get()->toArray();
        $periode_active = Periode::WHERE('status','=','1')->SELECT('id')->first();

        $detailRenja = [
            ['title' => 'SKPD', 'label' => $this->Skpd($request->skpd_id)['nama'] ],
        ];

        return [
            'skpdId'             => $request->skpd_id,
            'periodeList'        => $periodes,
            'detailRenja'        => $detailRenja,
            'periodeAktifId'     => $periode_active->id,
            'userId'             => auth()->user()->id

        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'periodeId.required'    => 'Harus diisi',
            'skpdId.required'       => 'Harus diisi',
            'userId.required'       => 'Harus diisi',

        ];



        $validator = Validator::make(
            $request->all(),
            [
                'periodeId'         => 'required',
                'skpdId'            => 'required',
                'userId'            => 'required',

            ],
            $messages
        );

        if ($validator->fails()) {
            //$messages = $validator->messages();
            return response()->json(['errors' => $validator->messages()], 422);
        }

        //cek apakah pernah ada id skpd dan id periode yang sama pada db
        if (Renja::WHERE('periode_id','=',$request->periodeId)->WHERE('skpd_id','=',$request->skpdId)->exists()) {
            return \Response::make(['message'=>'Renja pada periode ini sudah dibuat'],422);
        }

        //cari  detail pejabat dan jabatan pada SIM-ASN
        $admin  =  User::WHERE('id','=',$request->userId)->first();
        $skpd   =  $this->Skpd($request->skpdId);

        $rp    = new Renja;
        $rp->skpd_id             = $request->skpdId;
        $rp->periode_id          = $request->periodeId;
        $rp->periode             = json_encode(Periode::WHERE('id',$request->periodeId)->first());
        $rp->skpd                = json_encode($skpd);
        $rp->kepala_skpd         = null;
        $rp->admin               = json_encode($admin);

        if ($rp->save()) {
            return \Response::make($rp->id, 200);
        } else {
            return \Response::make('error', 500);
        }



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
    public function destroy(Request $request)
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


        $sr    = Renja::find($request->id);
        if (is_null($sr)) {
            return $this->sendError('Renja tidak ditemukan.');
        }

        if (TimKerja::where('renja_id', '=',$request->id)->exists()) {
            return \Response::make(['message'=>'Tidak dapat menghapus Renja yang sudah memiliki Tim Kerja'],422);
        }


        if ( $sr->delete()){
            return \Response::make('sukses', 200);
        }else{
            return \Response::make('error', 500);
        }
    }
}
