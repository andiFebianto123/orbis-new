<?php
namespace App\Helpers;

use App\Models\LogHub;
use App\Models\Personel;
use App\Models\StructureChurch;

class LeadershipSyncHelper
{
    public function sync($personel_id){
        $churches = StructureChurch::where('personel_id', $personel_id)->get();
        $arr_unit = [];

        foreach ($churches as $key => $churche) {
            $arr_unit[] = ['title_structure_id' => $churche->title_structure_id, 'church_id' =>$churche->churches_id];
        }
        /* disabled

        $prs = Personel::where("id", $personel_id)->first();
        if (isset($prs)) {
            $prs->church_name = json_encode($arr_unit);   
            $prs->save();       
        }
        */
    }
}