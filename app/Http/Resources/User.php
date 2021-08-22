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
            'nama_lengkap'  => ($this->pegawai)?$this->pegawai['nama_lengkap']:'-',
            'photo'         => ($this->pegawai)?$this->pegawai['photo']:null,
            //'profile'       => $this->pegawai['profile'],
            'jabatan'       => ($this->pegawai)?$this->pegawai['jabatan']:null,
            'skpd'          => ($this->pegawai)?$this->pegawai['skpd']:null,
            'unit_kerja'    => ($this->pegawai)?$this->pegawai['unit_kerja']:null,
            'roles'         => $this->roles()->get('role_id'),

        ];
    }
}
