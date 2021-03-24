<?php

use App\Models\RcDpwList;
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RcDpwListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RcDpwList::updateOrCreate([
            'rc_dpw_name' => 'Jabodetabek & Kalimantan'
        ], 
        ['rc_dpw_name' => 'Jabodetabek & Kalimantan']
        );
        RcDpwList::updateOrCreate([
            'rc_dpw_name' => 'West Java'
        ], 
        ['rc_dpw_name' => 'West Java']
        );
        RcDpwList::updateOrCreate([
            'rc_dpw_name' => 'Central Java'
        ], 
        ['rc_dpw_name' => 'Central Java']
        );
        RcDpwList::updateOrCreate([
            'rc_dpw_name' => 'East Java - Bali - NTB - NTT'
        ], 
        ['rc_dpw_name' => 'East Java - Bali - NTB - NTT']
        );
        RcDpwList::updateOrCreate([
            'rc_dpw_name' => 'East Indonesia'
        ], 
        ['rc_dpw_name' => 'East Indonesia']
        );
        RcDpwList::updateOrCreate([
            'rc_dpw_name' => 'East Indonesia 1'
        ], 
        ['rc_dpw_name' => 'East Indonesia 1']
        );
    }
}
