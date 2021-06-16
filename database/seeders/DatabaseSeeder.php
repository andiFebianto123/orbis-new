<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(RoleSeeder::class);
        // $this->call(UserSeeder::class);
        // $this->call(AccountStatusSeeder::class);
        // $this->call(RcDpwListSeeder::class);
        // $this->call(ChurchEntityTypeSeeder::class);
        // $this->call(ServiceTypeSeeder::class);
        // $this->call(TitleListSeeder::class);
        // $this->call(MinistryRoleSeeder::class);
        // $this->call(SpecialRoleSeeder::class);
        // $this->call(LicenseTypeSeeder::class);
        // $this->call(LegalDocumentSeeder::class);
        // $this->call(CountryListSeeder::class);
        // $this->call(PersonelSeeder::class);
        $this->call(AssignRoleSeeder::class);
    }
}
