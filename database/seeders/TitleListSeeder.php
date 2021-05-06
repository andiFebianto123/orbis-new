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
            'short_desc' => 'Pdm Pdt'
        ], 
        ['short_desc' => 'Pdm Pdt', 
        'long_desc' => 'Pdm Pdt']
        );
        TitleList::updateOrCreate([
            'short_desc' => 'Pdp'
        ], 
        ['short_desc' => 'Pdp', 
        'long_desc' => 'Lay Pastor']
        );
        TitleList::updateOrCreate([
            'short_desc' => 'Pdp (Em)'
        ], 
        ['short_desc' => 'Pdp (Em)', 
        'long_desc' => 'Pdp (Em)']
        );
        TitleList::updateOrCreate([
            'short_desc' => 'Pdp Pdt'
        ], 
        ['short_desc' => 'Pdp Pdt', 
        'long_desc' => 'Pdp Pdt']
        );
        TitleList::updateOrCreate([
            'short_desc' => 'Pdt'
        ], 
        ['short_desc' => 'Pdt', 
        'long_desc' => 'Pastor']
        );
        TitleList::updateOrCreate([
            'short_desc' => 'Pdt (Em)'
        ], 
        ['short_desc' => 'Pdt (Em)', 
        'long_desc' => 'Pdt (Em)']
        );
        TitleList::updateOrCreate([
            'short_desc' => 'Pnt'
        ], 
        ['short_desc' => 'Pnt', 
        'long_desc' => 'Elder Pastor']
        );
        TitleList::updateOrCreate([
            'short_desc' => 'Pst'
        ], 
        ['short_desc' => 'Pst', 
        'long_desc' => 'Adoption Pastor']
        );
        TitleList::updateOrCreate([
            'short_desc' => 'Mr'
        ], 
        ['short_desc' => 'Mr', 
        'long_desc' => 'Coordinator']
        );
        TitleList::updateOrCreate([
            'short_desc' => 'Mrs'
        ], 
        ['short_desc' => 'Mrs', 
        'long_desc' => 'Coordinator']
        );
        TitleList::updateOrCreate([
            'short_desc' => 'Ms'
        ], 
        ['short_desc' => 'Ms', 
        'long_desc' => 'Coordinator']
        );
    }
}
