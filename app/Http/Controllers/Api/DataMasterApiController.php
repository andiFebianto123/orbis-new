<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
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

    

}
