<?php

namespace Database\Seeders;

use App\Models\LegalDocument;
use Illuminate\Database\Seeder;

class LegalDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LegalDocument::updateOrCreate([
            'documents' => 'Church Certificate'
        ], 
        ['documents' => 'Church Certificate']
        );
        LegalDocument::updateOrCreate([
            'documents' => 'Covenant Of Understanding (COU)'
        ], 
        ['documents' => 'Covenant Of Understanding (COU)']
        );
        LegalDocument::updateOrCreate([
            'documents' => 'Memorandum Of Understanding (MOU)'
        ], 
        ['documents' => 'Memorandum Of Understanding (MOU)']
        );
    }
}
