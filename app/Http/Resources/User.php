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

        //profile
        if ($this->profile) {
            $profile = $this->profile;
        } else {
            $profile = array(['tes' => 'tes']);
        }
        return [
            'id'            => $this->id,
            'username'      => $this->username,
            'nip'           => $this->nip,
            'pegawai'       => $this->pegawai,
            'jabatan'       => $this->jabatan,
            'skpd'          => $this->skpd,
            'unit_kerja'    => $this->unit_kerja,
            'roles'         => $this->roles()->get('role_id'),

        ];
    }
}
