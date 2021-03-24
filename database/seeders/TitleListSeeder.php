<?php

namespace Database\Seeders;

use App\Models\TitleList;
use Illuminate\Database\Seeder;

class TitleListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TitleList::updateOrCreate([
            'short_desc' => 'Pdm'
        ], 
        ['short_desc' => 'Pdm', 
        'long_desc' => 'Assoc. Pastor']
        );
        TitleList::updateOrCreate([
            'short_desc' => 'Pdp'
        ], 
        ['short_desc' => 'Pdp', 
        'long_desc' => 'Lay Pastor']
        );
        TitleList::updateOrCreate([
            'short_desc' => 'Pdt'
        ], 
        ['short_desc' => 'Pdt', 
        'long_desc' => 'Pastor']
        );
        TitleList::updateOrCreate([
            'short_desc' => 'Pnt'
        ], 
        ['short_desc' => 'Pnt', 
        'long_desc' => 'Elder Pastor']
        );
    }
}
