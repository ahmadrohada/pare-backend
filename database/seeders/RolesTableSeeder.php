<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Roles;

class RolesTableSeeder extends Seeder
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
                'name'   => 'personal',
            ],
            [
                'name'   => 'admin_skpd',
            ],
            [
                'name'   => 'administrator',
            ],
            [
                'name'   => 'admin_puskesmas',
            ],
        ];

        Roles::insert($data);
    }
}
