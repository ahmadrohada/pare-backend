<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoleUser;

use Validator;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     *
     */
    public function update(Request $request)
    {
        $messages = [
            'id.required'         => 'Harus diisi',
            'is_admin.required'    => 'Harus diisi',
        ];
        $validator = Validator::make(
            $request->all(),
            [
                'id'              => 'required',
                'is_admin'         => 'required',
            ],
            $messages
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->messages()], 422);
        }

        if ( $request->is_admin == true ){
            $ah    = new RoleUser;
            $ah->user_id            = $request->id;
            $ah->role_id            = '2';
            $ah->save();
        }

        if ( $request->is_admin == false ){
            $sr    = RoleUser::find($request->id)->WHERE('role_id','=','2');
            $sr->delete();
        }




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
