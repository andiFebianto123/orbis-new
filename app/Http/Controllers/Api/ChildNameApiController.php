<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ChildNamePastors;

class ChildNameApiController extends Controller
{

    public function list($id)
    {
        $lists = ChildNamePastors::where('personel_id', $id)
        ->get();

        $response = [
            'status' => true,
            'title' => 'Child Names',
            'data' => $lists,
        ];

        return response()->json($response, 200); 
    }

    public function create(Request $request){
        $insert_p = new ChildNamePastors();
        $insert_p->child_name = $request->child_name;
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

        $update_p = ChildNamePastors::where('id', $id)->first();

        if (isset($request->child_name)) {
            $update_p->child_name = $request->child_name;
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
        
        ChildNamePastors::where('id', $id)->delete();

        $response = [
            'status' => true,
            'title' => 'Successfully',
        ];
        
        return response()->json($response, 200); 
    }

}
