<?php

namespace App\Http\Controllers\Api;

use App\Helpers\LeadershipSyncHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CareerBackgroundPastors;
use App\Models\Personel;
use App\Models\StructureChurch;

class LeadershipStructureApiController extends Controller
{

    public function show($id)
    {
      
        $lists = StructureChurch::join('personels', 'personels.id', 'structure_churches.personel_id')
                ->join('ministry_roles', 'ministry_roles.id', 'structure_churches.title_structure_id')
                ->join('title_lists', 'title_lists.id', 'personels.title_id')
                ->join('churches', 'churches.id', 'structure_churches.churches_id')
                ->where('structure_churches.id', $id)
                ->get(['structure_churches.id as id', 'ministry_roles.ministry_role as ministry_role', 
                'title_lists.short_desc','churches.church_name', 'churches.id as church_id', 'churches.church_address','title_lists.long_desc','personels.first_name', 'personels.last_name']);

        $response = [
            'status' => true,
            'title' => 'Leadership',
            'data' => $lists,
        ];

        return response()->json($response, 200);  
    }

    public function create(Request $request){
        $insert_p = new StructureChurch();
        $insert_p->title_structure_id = $request->title_structure_id;
        $insert_p->churches_id = $request->churches_id;
        $insert_p->personel_id = $request->personel_id;
        $insert_p->save();

        (new LeadershipSyncHelper())->sync($insert_p->personel_id);

        $response = [
            'status' => true,
            'title' => 'Successfully',
        ];
        
        return response()->json($response, 200); 
    }


    public function update(Request $request){
        $id = $request->id;

        $update_p = StructureChurch::where('id', $id)->first();

        if (isset($request->title_structure_id)) {
            $update_p->title_structure_id = $request->title_structure_id;
        }
        if (isset($request->churches_id)) {
            $update_p->churches_id = $request->churches_id;
        }

        $update_p->save();

        (new LeadershipSyncHelper())->sync($update_p->personel_id);

        $response = [
            'status' => true,
            'title' => 'Successfully',
        ];
        
        return response()->json($response, 200); 
    }

    public function delete(Request $request){
        $id = $request->id;
        
        StructureChurch::where('id', $id)->delete();

        $response = [
            'status' => true,
            'title' => 'Successfully',
        ];
        
        return response()->json($response, 200); 
    }

    // private function updateChurchNameJson($personel_id){
    //     $churches = StructureChurch::join('personels', 'personels.id', 'structure_churches.personel_id')
    //         ->join('ministry_roles', 'ministry_roles.id', 'structure_churches.title_structure_id')
    //         ->join('title_lists', 'title_lists.id', 'personels.title_id')
    //         ->join('churches', 'churches.id', 'structure_churches.churches_id')
    //         ->where('structure_churches.personel_id', $personel_id)
    //         ->get(['structure_churches.id as id', 'ministry_roles.ministry_role as ministry_role', 'structure_churches.personel_id','structure_churches.title_structure_id','structure_churches.churches_id',
    //         'title_lists.short_desc','churches.church_name', 'churches.id as church_id', 'churches.church_address','title_lists.long_desc','personels.first_name', 'personels.last_name']);
    //     $arr_unit = [];

    //     foreach ($churches as $key => $churche) {
    //         $arr_unit[] = ['title_structure_id' => $churche->title_structure_id, 'church_id' =>$churche->churches_id];
    //     }

    //     $prs = Personel::where("id", $personel_id)->first();
    //     if (isset($prs)) {
    //         $prs->church_name = json_encode($arr_unit);   
    //         $prs->save();       
    //     }
    // }

}
