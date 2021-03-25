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
        'acc_status' => 'Non Active'
        ], 
            ['acc_status' => 'Non Active']
        );
    }
}
