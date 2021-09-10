<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Personel;
use App\Models\Appointment_history;
use App\Models\StatusHistory;
use App\Models\SpecialRolePersonel;
use App\Models\Relatedentity;
use App\Models\EducationBackground;
use App\Models\ChildNamePastors;
use App\Models\MinistryBackgroundPastor;
use App\Models\CareerBackgroundPastors;
use App\Models\StructureChurch;

class EducationBackgroundApiController extends Controller
{

    public function list($id)
    {
        $lists = EducationBackground::where('personel_id', $id)
                ->get();
        
        $response = [
            'status' => true,
            'title' => 'Education Backgrounds',
            'data' => $lists,
        ];

        return response()->json($response, 200); 
    }

    public function create(Request $request){
        $insert_p = new EducationBackground();
        $insert_p->degree = $request->degree;
        $insert_p->type_education = $request->type_education;
        $insert_p->concentration_education = $request->concentration_education;
        $insert_p->school = $request->school;
        $insert_p->year = $request->year;
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

        $update_p = EducationBackground::where('id', $id)->first();

        if (isset($request->degree)) {
            $update_p->degree = $request->degree;
        }
        if (isset($request->type_education)) {
            $update_p->type_education = $request->type_education;
        }
        if (isset($request->concentration_education)) {
            $update_p->concentration_education = $request->concentration_education;
        }
        if (isset($request->school)) {
            $update_p->school = $request->school;
        }
        if (isset($request->year)) {
            $update_p->year = $request->year;
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
        
        EducationBackground::where('id', $id)->delete();

        $response = [
            'status' => true,
            'title' => 'Successfully',
        ];
        
        return response()->json($response, 200); 
    }

}
