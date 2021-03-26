<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $power_user = User::updateOrCreate([
            'name' => 'yemima',
            'email' => 'yemima@gmail.com',
            'password' => '$2y$12$r3ueyelLv4O3RhRrpqEDt.raFs8sAh7GoJzMJ5ZGE5EHqps2JNILW', // qwerty
            'privilege' => 'Power User', 
            'status_user' => 'Active']
        );

        $power_user->assignRole('power_user');

        $administrator = User::updateOrCreate([
            'name' => 'sontiara',
            'email' => 'sontiara@gmail.com',
            'password' => '$2y$12$r3ueyelLv4O3RhRrpqEDt.raFs8sAh7GoJzMJ5ZGE5EHqps2JNILW', // qwerty
            'privilege' => 'Administrator', 
            'status_user' => 'Active']
        );

        $administrator->assignRole('administrator');

    }
}
