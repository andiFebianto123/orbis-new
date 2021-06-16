<?php

namespace Database\Seeders;

use App\Models\ModelHasRole;
use Illuminate\Database\Seeder;

class AssignRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(ModelHasRole::where('model_id', 1)->count() != 0){
            ModelHasRole::where('model_id', 1)->update([
                'role_id' => 3
            ]);
        }
        else{
            ModelHasRole::create([
                'role_id' => 3,
                'model_type' => 'App\Models\User',
                'model_id' => 1
            ]);
        }  
    }
}
