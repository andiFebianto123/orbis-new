<?php

use App\Models\AccountStatus;
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AccountStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AccountStatus::updateOrCreate([
            'acc_status' => 'Active'
        ], 
        ['acc_status' => 'Active']
        );
        AccountStatus::updateOrCreate([
        'acc_status' => 'Non Active'
        ], 
            ['acc_status' => 'Non Active']
        );
    }
}
