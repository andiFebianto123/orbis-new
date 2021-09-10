<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CareerBackgroundPastors;

class CareerBackgroundApiController extends Controller
{

    public function list($id)
    {
        $lists = CareerBackgroundPastors::where('personel_id', $id)
                ->get();
        
        $response = [
            'status' => true,
            'title' => 'Career Backgrounds',
            'data' => $lists,
        ];

        return response()->json($response, 200);  
    }

    public function create(Request $request){
        $insert_p = new CareerBackgroundPastors();
        $insert_p->career_title = $request->career_title;
        $insert_p->career_description = $request->career_description;
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

        $update_p = CareerBackgroundPastors::where('id', $id)->first();

        if (isset($request->career_title)) {
            $update_p->career_title = $request->career_title;
        }
        if (isset($request->career_description)) {
            $update_p->career_description = $request->career_description;
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
        
        CareerBackgroundPastors::where('id', $id)->delete();

        $response = [
            'status' => true,
            'title' => 'Successfully',
        ];
        
        return response()->json($response, 200); 
    }

}
