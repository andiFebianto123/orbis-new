<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Personel;
use App\Models\StatusHistory;
use App\Models\StructureChurch;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        if (!Auth::guard('personel')->attempt($request->only('email', 'password'))) {
            return response()->json([
                    'status' => false,
                    'message' => 'Wrong email or password'
                    ], 200);
        }

        $personels = Personel::where('email', $request->email)->get();
        $active_email = false;
        $valid_personel = [];
        foreach ($personels as $key => $personel) {
            if(StatusHistory::where('personel_id', $personel->id)->where('status_histories_id', 1)->exists()){
                $active_email = true;
                $valid_personel = $personel;
            }
        }
        
        if ($active_email) {
            $token = $valid_personel->createToken('auth_token')->plainTextToken;
            $church = StructureChurch::where('personel_id', $valid_personel->id)
                        ->join('churches', 'churches.id', 'structure_churches.churches_id')
                        ->get(['churches.id as church_id', 'church_name'])
                        ->first();

            $response = [
                'status' => true,
                'message' => 'Success Login',
                'access_token' => $token,
                'data' => $valid_personel,
                'data_church' => $church,
            ];
        }else{
            $response = [
                'status' => false,
                'message' => 'Email is not active',
            ];
        }
        

        return response()->json($response, 200);
    }
    
    public function logout(Request $request) {
        $personel = $request->user();
        $personel->currentAccessToken()->delete();
        $response = [
            'status' => true,
            'message' => 'Logout successfully',
        ];
        return response()->json($response, 200);
    }

    public function logoutAll(Request $request) {
        $personel = $request->user();
        $personel->tokens()->delete();
        $response = [
            'status' => true,
            'message' => 'Logout All successfully',
        ];
        return response()->json($response, 200);
    }

}
