<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SkpRencanaKinerjaIndikator;

class SkpRencanaKinerjaIndikatorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            //KABAN
            [
                'label'                     => 'Nilai Sistem Merit Hasi Penilaian Mandiri',
                'rencana_kinerja_id'        => 1,
                'aspek'                     => 'kuantitas',
                'target'                    => '300',
                'satuan_target'             => 'score',
                'keterangan_target'         => '',
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
            [
                'label'                     => 'Nilai LAKIP BKPSDM',
                'rencana_kinerja_id'        => 2,
                'aspek'                     => 'kuantitas',
                'target'                    => '71',
                'satuan_target'             => 'score',
                'keterangan_target'         => '',
                'created_at'                => new \DateTime,
                'updated_at'                => null,
                'deleted_at'                => null
            ],
        ];

        SkpRencanaKinerjaIndikator::insert($data);
    }
}
