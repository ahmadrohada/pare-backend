<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoleUser;

class RoleUserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->getOutput()->writeln('<info>Users Role:</info>');

        $data = file_get_contents(__DIR__ . '/data/role_user.json');
        foreach (json_decode($data, true) as $dt) {
            RoleUser::create($dt);
        }
    }
}
