<?php

namespace Database\Seeders;

use App\Models\RolePermissions;
use Illuminate\Database\Seeder;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RolePermissions::updateOrCreate([
            'role' => 'Power User'
        ], 
        ['role' => 'Power User', 
        'permission' => 'Power User']
        );
        RolePermissions::updateOrCreate([
            'role' => 'Management'
        ], 
        ['role' => 'Management', 
        'permission' => 'Management']
        );
        RolePermissions::updateOrCreate([
            'role' => 'Pastor/ Church Level User'
        ], 
        ['role' => 'Pastor/ Church Level User', 
        'permission' => 'Pastor/ Church Level User']
        );
        RolePermissions::updateOrCreate([
            'role' => 'Administrator'
        ], 
        ['role' => 'Administrator', 
        'permission' => 'Administrator']
        );
    }
}
