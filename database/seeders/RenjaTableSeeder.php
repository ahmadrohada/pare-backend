<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Renja;

class RenjaTableSeeder extends Seeder
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
                'skpd_id'            => 42,
                'periode_id'         => 1,
                'nama_skpd'          => 'Badan Kepegawaian dan Pengembangan Sumber Daya Manusia',
                'singkatan_skpd'     => 'BKPSDM',
                'nama_kepala_skpd'   => 'H.Asep Aang Rahmatullah, S.STP.,MP',
                'nama_admin'         => 'Jajang Jaenudin, S.STP., MM',
                'created_at'         => new \DateTime,
                'updated_at'         => null,
                'deleted_at'         => null
            ],

        ];

        Renja::insert($data);
    }
}
