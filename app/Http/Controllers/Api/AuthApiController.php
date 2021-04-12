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

        return response()->json([
                'status' => true,
                'message' => 'Success Login',
                'access_token' => $token,
                'data' => $personel,
        ]);
    }

}
