<?php

namespace Database\Seeders;

use App\Models\SpecialRole;
use Illuminate\Database\Seeder;

class SpecialRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SpecialRole::updateOrCreate([
            'special_role' => 'Apostolic Team'
        ], 
        ['special_role' => 'Apostolic Team']
        );
        SpecialRole::updateOrCreate([
            'special_role' => 'Executive Council / DPP'
        ], 
        ['special_role' => 'Executive Council / DPP']
        );
        SpecialRole::updateOrCreate([
            'special_role' => 'RC Head/ Ketua DPW'
        ], 
        ['special_role' => 'RC Head/ Ketua DPW']
        );
        SpecialRole::updateOrCreate([
            'special_role' => 'Directors'
        ], 
        ['special_role' => 'Directors']
        );
        SpecialRole::updateOrCreate([
            'special_role' => 'President'
        ], 
        ['special_role' => 'President']
        );
        SpecialRole::updateOrCreate([
            'special_role' => 'Vice President'
        ], 
        ['special_role' => 'Vice President']
        );
    }
}
