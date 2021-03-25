<?php

namespace Database\Seeders;

use App\Models\ChurchEntityType;
use Illuminate\Database\Seeder;

class ChurchEntityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ChurchEntityType::updateOrCreate([
            'entities_type' => 'Local Church'
        ], 
        ['entities_type' => 'Local Church']
        );
        ChurchEntityType::updateOrCreate([
            'entities_type' => 'House Fellowship / TPI'
        ], 
        ['entities_type' => 'House Fellowship / TPI']
        );
        ChurchEntityType::updateOrCreate([
            'entities_type' => 'Satellite Church'
        ], 
        ['entities_type' => 'Satellite Church']
        );
        ChurchEntityType::updateOrCreate([
            'entities_type' => 'Regional Office'
        ], 
        ['entities_type' => 'Regional Office']
        );
        ChurchEntityType::updateOrCreate([
            'entities_type' => 'Satellite Council / Kantor DPW'
        ], 
        ['entities_type' => 'Satellite Council / Kantor DPW']
        );
        ChurchEntityType::updateOrCreate([
            'entities_type' => 'Government / Synod Office'
        ], 
        ['entities_type' => 'Government / Synod Office']
        );
    }
}
