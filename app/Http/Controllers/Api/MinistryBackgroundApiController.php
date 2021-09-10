<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MinistryBackgroundPastor;

class MinistryBackgroundApiController extends Controller
{

    public function list($id)
    {
        $lists = MinistryBackgroundPastor::where('personel_id', $id)
                ->get();
        
        $response = [
            'status' => true,
            'title' => 'Ministry Backgrounds',
            'data' => $lists,
        ];

        return response()->json($response, 200); 
    }

    public function create(Request $request){
        $insert_p = new MinistryBackgroundPastor();
        $insert_p->ministry_title = $request->ministry_title;
        $insert_p->ministry_description = $request->ministry_description;
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

        $update_p = MinistryBackgroundPastor::where('id', $id)->first();

        if (isset($request->ministry_description)) {
            $update_p->ministry_description = $request->ministry_description;
        }
        if (isset($request->ministry_title)) {
            $update_p->ministry_title = $request->ministry_title;
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
        
        MinistryBackgroundPastor::where('id', $id)->delete();

        $response = [
            'status' => true,
            'title' => 'Successfully',
        ];
        
        return response()->json($response, 200); 
    }

}
