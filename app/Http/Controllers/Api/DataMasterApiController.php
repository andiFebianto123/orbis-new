<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\CountryList;
use App\Models\RcDpwList;
use App\Models\TitleList;

class DataMasterApiController extends Controller
{

    public function title()
    {
        $masters = TitleList::get();

        $arr_master = [];
        foreach ($masters as $key => $master) {
            $arr_master[] = ['id' => $master->id, 'text' => $master->short_desc];
        }
       
        $response = [
            'status' => true,
            'data' => $arr_master,
        ];

        return response()->json($response, 200); 
    }

    public function regionalCouncil()
    {
        $masters = RcDpwList::get();

        $arr_master = [];
        foreach ($masters as $key => $master) {
            $arr_master[] = ['id' => $master->id, 'text' => $master->rc_dpw_name];
        }
       
        $response = [
            'status' => true,
            'data' => $arr_master,
        ];

        return response()->json($response, 200); 
    }

    public function gender()
    {

        $arr_master = [];
        $arr_master[] = ['id' => 'Male', 'text' => 'Male'];
        $arr_master[] = ['id' => 'Female', 'text' => 'Female'];
       
        $response = [
            'status' => true,
            'data' => $arr_master,
        ];

        return response()->json($response, 200); 
    }

    public function country()
    {
        $masters = CountryList::get();

        $arr_master = [];
        foreach ($masters as $key => $master) {
            $arr_master[] = ['id' => $master->id, 'text' => $master->country_name];
        }
       
        $response = [
            'status' => true,
            'data' => $arr_master,
        ];

        return response()->json($response, 200); 
    }

    public function maritalStatus()
    {
        $arr_master = [];
        $arr_master[] = ['id' => 'Single', 'text' => 'Single'];
        $arr_master[] = ['id' => 'Married', 'text' => 'Married'];
        $arr_master[] = ['id' => 'Divorce', 'text' => 'Divorce'];
        $arr_master[] = ['id' => 'Widower', 'text' => 'Widower'];
       
        $response = [
            'status' => true,
            'data' => $arr_master,
        ];

        return response()->json($response, 200); 
    }

}
