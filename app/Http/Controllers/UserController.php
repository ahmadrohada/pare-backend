<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\User as UserResource;

use Illuminate\Pagination\Paginator;


class UserController extends Controller
{

    public function profile_user_aktif(Request $request)
    {
        return new UserResource(auth()->user());
    }


    public function hirarki_user_aktif(Request $request)
    {

        $data = auth()->user();
        return [

            'pegawai'                   => $data->pegawai,
            'pejabat_penilai'           => $data->pejabat_penilai,
            'atasan_pejabat_penilai'    => $data->atasan_pejabat_penilai,

        ];


    }



    public function user_update()
    {
        $data = User::leftjoin('pegawai AS pegawai', function ($join) {
                $join->on('users.pegawai_id', '=', 'pegawai.id');
            })
            ->SELECT(
                'users.id AS user_id',
                'users.pegawai_id AS pegawai_id',
                'users.username AS nip_alias',
                'pegawai.nip AS nip'
            )
            ->get();

        $no = 0;
        foreach ($data as $x) {

            $sr    = User::find($x->user_id);
            $sr->nip   = ( $x->nip != null ) ? $x->nip : $x->nip_alias;

            if ($sr->save()) {
                $no++;
            }
        }
        return "berhasil update data sebanyak : " . $no;
    }


    public function user_list(Request $request)
    {

        $page = ($request->page)? $request->page : 1 ;
        $length = ($request->length)? $request->length : 10 ;

        Paginator::currentPageResolver(fn() => $page );

        $query =  User::select(
            'username',
            'id',
            'pegawai->nip AS nip',
            'pegawai->nama_lengkap AS nama_lengkap',
        );

        if($request->search) {
            $query->where('pegawai->nama_lengkap','LIKE', '%'.$request->search.'%');
        }

        $data = $query->paginate($length);


        $pagination = array(
            'current_page'  => $data->currentPage(),
            'total_page'    => ( ($data->perPage() != 0 ) && ($data->total() != 0 )  ) ? Floor($data->total()/$data->count()) : 0,
            'data_per_page' => $data->count(),
            'limit'         => (int)$data->perPage(),
            'total'         => $data->total(),
        );

        return [
            'data'          => $data->items(),
            'pagination'    => $pagination,

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
