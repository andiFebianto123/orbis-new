<?php

namespace Database\Seeders;

use App\Models\CountryList;
use Illuminate\Database\Seeder;

class CountryListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CountryList::updateOrCreate([
            'iso_two' => 'AF'
        ], 
        ['iso_two' => 'AF', 
        'iso_three' => 'AFG',
        'country_name' => 'Afghanistan']
        );
        CountryList::updateOrCreate([
            'iso_two' => 'AL'
        ], 
        ['iso_two' => 'AL', 
        'iso_three' => 'ALB',
        'country_name' => 'Albania']
        );
        CountryList::updateOrCreate([
            'iso_two' => 'DZ'
        ], 
        ['iso_two' => 'DZ', 
        'iso_three' => 'DZA',
        'country_name' => 'Algeria']
        );
    }
}
