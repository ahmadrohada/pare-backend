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

        //pegawai
        if ($this->pegawai) {
            $pegawai = $this->pegawai;
        } else {
            $pegawai = array(['tes' => 'tes']);
        }








        return [
            'id' => $this->id,
            'username' => $this->username,

            'user'      => $this->pegawai,

            //cek index pada array pegawai
            /* 'nip' => Arr::exists($pegawai, 'nip') ? $pegawai['nip'] : "",
            'nama' => Arr::exists($pegawai, 'nama') ? $pegawai['nama'] : "",
            'jabatan' => Arr::exists($pegawai, 'jabatan') ? $pegawai['jabatan'] : "", */

            //'nama' => $this->pegawai['nama'],
        ];
    }
}
