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
        $power_user = User::updateOrCreate(['email' => 'yemima@gmail.com'], [
            'name' => 'yemima',
            'email' => 'yemima@gmail.com',
            'password' => 'qwerty', // qwerty
            'privilege' => 'Power User', 
            'status_user' => 'Active']
        );

        $power_user->assignRole('Super Admin');

        $administrator = User::updateOrCreate(['email' => 'sontiara@gmail.com'], [
            'name' => 'sontiara',
            'email' => 'sontiara@gmail.com',
            'password' => 'qwerty', // qwerty
            'privilege' => 'Administrator', 
            'status_user' => 'Active']
        );

        $administrator->assignRole('Super Admin');

    }
}
