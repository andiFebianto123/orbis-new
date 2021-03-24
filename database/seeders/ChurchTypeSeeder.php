<?php

namespace Database\Seeders;

use App\Models\ChurchType;
use Illuminate\Database\Seeder;

class ChurchTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ChurchType::updateOrCreate([
            'entities_type' => 'Local Church'
        ], 
        ['entities_type' => 'Local Church']
        );
        ChurchType::updateOrCreate([
            'entities_type' => 'House Fellowship / TPI'
        ], 
        ['entities_type' => 'House Fellowship / TPI']
        );
        ChurchType::updateOrCreate([
            'entities_type' => 'Satellite Church'
        ], 
        ['entities_type' => 'Satellite Church']
        );
        ChurchType::updateOrCreate([
            'entities_type' => 'Regional Office'
        ], 
        ['entities_type' => 'Regional Office']
        );
        ChurchType::updateOrCreate([
            'entities_type' => 'Satellite Council / Kantor DPW'
        ], 
        ['entities_type' => 'Satellite Council / Kantor DPW']
        );
        ChurchType::updateOrCreate([
            'entities_type' => 'Government / Synod Office'
        ], 
        ['entities_type' => 'Government / Synod Office']
        );
    }
}
