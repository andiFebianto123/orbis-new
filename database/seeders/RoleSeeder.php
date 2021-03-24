<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'power_user',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'administrator',
            'guard_name' => 'web'
        ]);
    }
}
