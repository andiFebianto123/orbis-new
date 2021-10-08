<?php
 namespace App\Http\Controllers\Admin;

use App\Models\Personel;
use App\Models\MinistryRole;
use Illuminate\Support\Facades\Artisan;
 use Backpack\CRUD\app\Http\Controllers\CrudController;

 class BackupController extends CrudController{

    public function downloadDb(){
        $arr_datas = [];
        $sc = "Joshua Agung Artono - Satellite Pastor";
        if (strpos( $sc, "-") !== false) {
            $expl_dash = explode("-",$sc);
            $last_dash = substr_count($sc, "-");

            $per_name = rtrim($expl_dash[0]);
            $first_name = $per_name;
            $last_name = "";
            if (strpos( $per_name, " ") !== false) {
                $expl_space = explode(" ",$per_name);
                $first_name = $expl_space[0];
                $last_space = substr_count($per_name, " ");
                $last_name = trim($expl_space[$last_space]);
            }

            $personel_name = Personel::where('first_name','like', '%'.rtrim($first_name).'%')
                             ->where("last_name",'like', '%'.$last_name.'%')->first();

            $ministry_role = MinistryRole::where('ministry_role','like', '%'.trim($expl_dash[$last_dash]).'%')->first();

            if (isset($personel_name) && isset($ministry_role)) {
                $arr_datas['idd'] = ['personel_id' => $personel_name->id, 'title_structure_id' => $ministry_role->id];
            }
        }  

        return $arr_datas;
    }
}