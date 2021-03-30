<?php

namespace Database\Seeders;

use App\Models\MinistryRole;
use Illuminate\Database\Seeder;

class MinistryRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MinistryRole::updateOrCreate([
            'ministry_role' => 'Lead Pastor'
        ], 
        ['ministry_role' => 'Lead Pastor']
        );
        MinistryRole::updateOrCreate([
            'ministry_role' => 'Satellite Pastor'
        ], 
        ['ministry_role' => 'Satellite Pastor']
        );
        MinistryRole::updateOrCreate([
            'ministry_role' => 'Coordinator'
        ], 
        ['ministry_role' => 'Coordinator']
        );
        MinistryRole::updateOrCreate([
            'ministry_role' => 'Secretary'
        ], 
        ['ministry_role' => 'Secretary']
        );
        MinistryRole::updateOrCreate([
            'ministry_role' => 'Treasurer'
        ], 
        ['ministry_role' => 'Treasurer']
        );
    }
}
