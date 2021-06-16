<?php

namespace Database\Seeders;

use App\Models\ModelHasRole;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AssignRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        $role_id = Role::where('name', 'Super Admin')->first();
        if(ModelHasRole::where('model_id', 1)->count() != 0){
            ModelHasRole::where('model_id', 1)->update([
                'role_id' => $role_id ?? 3
            ]);
        }
        else{
            ModelHasRole::create([
                'role_id' => $role_id ?? 3,
                'model_type' => 'App\Models\User',
                'model_id' => 1
            ]);
        }  
        Schema::enableForeignKeyConstraints();
    }
}
