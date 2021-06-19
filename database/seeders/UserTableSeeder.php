<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->getOutput()->writeln('<info>Users:</info>');

        $data = file_get_contents(__DIR__ . '/data/users.json');
        foreach (json_decode($data, true) as $dt) {
            User::create($dt);
        }
    }
}
