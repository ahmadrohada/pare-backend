<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\Periode;

class PeriodeTableSeeder extends Seeder
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
                'tahun'         => 2021,
                'mulai'         => '2021-01-01',
                'selesai'       => '2021-12-31',
                'status'        => '0',
                'created_at'    => new \DateTime,
                'updated_at'    => null,
                'deleted_at'    => null
            ],
        ];

        Periode::insert($data);
    }
}
