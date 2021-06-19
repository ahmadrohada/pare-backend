<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use Illuminate\Database\Seeder;

class JudulSuratSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->getOutput()->writeln('<info>Seeding Judul Surat:</info>');

        $data = file_get_contents(__DIR__ . '/../data/jenis_surat.json');
        foreach (json_decode($data, true) as $dt) {
            JenisSurat::create($dt);
        }
    }
}
