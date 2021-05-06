<?php

namespace Database\Seeders;

use App\Models\Accountstatus;
use Illuminate\Database\Seeder;

class AccountstatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Accountstatus::updateOrCreate([
            'acc_status' => 'Active'
        ], 
        ['acc_status' => 'Active']
        );
        Accountstatus::updateOrCreate([
            'acc_status' => 'inActive'
        ], 
        ['acc_status' => 'inActive']
        );
        Accountstatus::updateOrCreate([
        'acc_status' => 'Deceased'
        ], 
            ['acc_status' => 'Deceased']
        );
        Accountstatus::updateOrCreate([
            'acc_status' => 'Pending'
        ], 
        ['acc_status' => 'Pending']
        );
        Accountstatus::updateOrCreate([
        'acc_status' => 'Resign'
        ], 
            ['acc_status' => 'Resign']
        );
        Accountstatus::updateOrCreate([
            'acc_status' => 'Resigned'
        ], 
        ['acc_status' => 'Resigned']
        );
        Accountstatus::updateOrCreate([
        'acc_status' => 'Retired'
        ], 
            ['acc_status' => 'Retired']
        );
        Accountstatus::updateOrCreate([
            'acc_status' => 'Revoked'
        ], 
        ['acc_status' => 'Revoked']
        );
        Accountstatus::updateOrCreate([
            'acc_status' => 'Not Extended'
        ], 
        ['acc_status' => 'Not Extended']
        );
        Accountstatus::updateOrCreate([
            'acc_status' => 'On Leave'
        ], 
        ['acc_status' => 'On Leave']
        );
        Accountstatus::updateOrCreate([
            'acc_status' => 'Other Church'
        ], 
        ['acc_status' => 'Other Church']
        );
                
    }
}
