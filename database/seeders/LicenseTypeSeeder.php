<?php

use App\Models\LicenseType;
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LicenseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LicenseType::updateOrCreate([
            'pastors_license_type' => 'Certificate of Ministry Appointment (CMA/SPP)'
        ], 
        ['pastors_license_type' => 'Certificate of Ministry Appointment (CMA/SPP)']
        );
        LicenseType::updateOrCreate([
            'pastors_license_type' => 'Certificate of Ministry License (CML)'
        ], 
        ['pastors_license_type' => 'Certificate of Ministry License (CML)']
        );
        LicenseType::updateOrCreate([
            'pastors_license_type' => 'ID Card'
        ], 
        ['pastors_license_type' => 'ID Card']
        );
    }
}
