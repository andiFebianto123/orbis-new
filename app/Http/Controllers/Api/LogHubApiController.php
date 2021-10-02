<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LogHub;

class LogHubApiController extends Controller
{
    public function list(){
        if (request('personel_id')) {
            $filters[] = ['personel_id', '=', request('personel_id')];
        }

        return LogHub::where($filters)->orderBy('id', 'desc')->paginate(1);
    }


}