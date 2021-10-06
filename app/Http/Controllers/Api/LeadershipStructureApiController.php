<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CareerBackgroundPastors;
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

}
