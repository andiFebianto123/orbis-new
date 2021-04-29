<?php

namespace Database\Seeders;

use App\Models\Personel;
use Illuminate\Database\Seeder;

class PersonelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Personel::updateOrCreate([
            'acc_status_id' => '1'
        ], 
        ['rc_dpw_id' => '1',
        'title_id' => '1',
        'first_name' => 'Teresa',
        'last_name' => 'Bunga',
        'gender' => 'Female',
        'date_of_birth' => '2021-04-14',
        'marital_status' => 'Single',
        'ministry_background' => 'abcd',
        'career_background' => 'abcd',
        'image' => 'abcd',
        'street_address' => 'abcd',
        'city' => 'Semarang',
        'province' => 'Jawa Tengah',
        'postal_code' => '1234',
        'country_id' => '1',
        'email' => 'test@gmail.com',
        'phone' => '12345',
        'fax' => '12345',
        'first_licensed_on'=> '2021-04-14',
        'card' => 'abcd',
        'valid_card_start' => '2021-04-14',
        'valid_card_end' => '2021-04-14',
        'password' => 'qwerty' ]
        );
    }
}
