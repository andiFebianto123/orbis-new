<?php

namespace Database\Seeders;

use App\Models\ServiceType;
use Illuminate\Database\Seeder;

class ServiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ServiceType::updateOrCreate([
            'church_service' => 'Kids Service'
        ], 
        ['church_service' => 'Kids Service']
        );
        ServiceType::updateOrCreate([
            'church_service' => 'Teen Service'
        ], 
        ['church_service' => 'Teen Service']
        );
        ServiceType::updateOrCreate([
            'church_service' => 'Main Service'
        ], 
        ['church_service' => 'Main Service']
        );
        ServiceType::updateOrCreate([
            'church_service' => 'Mandarin Service'
        ], 
        ['church_service' => 'Mandarin Service']
        );
        ServiceType::updateOrCreate([
            'church_service' => 'English Service'
        ], 
        ['church_service' => 'English Service']
        );
        ServiceType::updateOrCreate([
            'church_service' => 'Indonesian Service'
        ], 
        ['church_service' => 'Indonesian Service']
        );
        ServiceType::updateOrCreate([
            'church_service' => 'Youth Service'
        ], 
        ['church_service' => 'Youth Service']
        );
    }
}
