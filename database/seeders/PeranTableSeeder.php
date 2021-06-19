<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peran;


class PeranTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $data = [
            [
                'label'         => 'Pejabat Pimpinan Tinggi',
                'level'         => '1',
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'label'         => 'Pimpinan Unit Kerja Mandiri',
                'level'         => '1',
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'label'         => 'Koordinator Tim Kerja',
                'level'         => '2',
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'label'         => 'Ketua Kelompok Kerja',
                'level'         => '3',
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
            [
                'label'         => 'Anggota Kelompok Kerja',
                'level'         => '4',
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
        ];

        Peran::insert($data);
    }
}
