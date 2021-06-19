<?php

namespace Database\Seeders;

use App\Models\JenisJafung as ModelsJenisJafung;
use Illuminate\Database\Seeder;

class JenisJafungSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->getOutput()->writeln('<info>Seeding Jenis Jafung:</info>');

        $data = file_get_contents(__DIR__ . '/../data/jenis_jafung.json');
        foreach (json_decode($data, true) as $dt) {
            ModelsJenisJafung::create($dt);
        }
    }
}
