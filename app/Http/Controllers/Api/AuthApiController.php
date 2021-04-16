<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Personel;

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

        $personel = Personel::where('email', $request->email)->firstOrFail();
        $token = $personel->createToken('auth_token')->plainTextToken;

        $response = [
            'status' => true,
            'message' => 'Success Login',
            'access_token' => $token,
            'data' => $personel,
        ];

        return response()->json($response, 200);
    }

    public function profile($id)
    {
        $personel = Personel::where('id', $id)->get()->first();

        $response = [
            'status' => true,
            'message' => 'Data Personel',
            'data' => $personel,
        ];

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
