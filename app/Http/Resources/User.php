<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Arr;


class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {



        return [
            'id'            => $this->id,
            'username'      => $this->username,
            'nip'           => $this->nip,
            'nama_lengkap'  => $this->pegawai['nama_lengkap'],
            'photo'         => $this->pegawai['photo'],
            //'profile'       => $this->pegawai['profile'],
            'jabatan'       => $this->pegawai['jabatan'],
            'skpd'          => $this->pegawai['skpd'],
            'unit_kerja'    => $this->pegawai['unit_kerja'],
            'roles'         => $this->roles()->get('role_id'),

        ];
    }
}
