<?php

namespace App\Http\Controllers\Api;

use App\Models\Personel;
use App\Helpers\LogHubApi;
use Illuminate\Http\Request;
use App\Models\StatusHistory;
use App\Models\StructureChurch;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Passwords\PasswordBroker;
use Backpack\CRUD\app\Notifications\ResetPasswordNotification;

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
        $can_crud = false;
        foreach ($personels as $key => $personel) {
            $current_status_history = StatusHistory::where('personel_id', $personel->id)
                            ->leftJoin('account_status', 'account_status.id', 'status_histories.status_histories_id')
                            ->orderBy('date_status','desc')
                            ->orderBy('status_histories.created_at','desc')
                            ->first();
           
            if(isset($current_status_history->acc_status) && strtolower($current_status_history->acc_status) == 'active'){
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
            
            $leaderships_exist = StructureChurch::join('personels', 'personels.id', 'structure_churches.personel_id')
                        ->join('ministry_roles', 'ministry_roles.id', 'structure_churches.title_structure_id')
                        ->where('structure_churches.personel_id', $valid_personel->id)
                        ->where(function ($query) {
                            $query->where('ministry_roles.ministry_role', 'Lead Pastor')
                                  ->orWhere('ministry_roles.ministry_role', 'Senior Pastor');
                        })
                        ->exists();
            $can_crud = $leaderships_exist;
            
            $response = [
                'status' => true,
                'message' => 'Success Login',
                'access_token' => $token,
                'data' => $valid_personel,
                'data_church' => $church,
                'can_crud' => $can_crud
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

    public function forgotPassword(Request $request){
        $validator = Validator::make($request->all(), ['email' => 'required|email']);
        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()], 422);
        }

        ResetPasswordNotification::createUrlUsing(function($notifiable, $token){
            $url = config('app.front_end_reset_password_url') . '?token=' . 
            $token . '&email=' . $notifiable->getEmailForPasswordReset();
            return $url;
        });

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = Password::broker('personel')->sendResetLink(
           ['email' => $request->email]
        );

        return response()->json(['message' => trans(PasswordBroker::RESET_LINK_SENT)]);
    }

    public function resetPassword(Request $request){
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::broker('personel')->reset(
            $credentials, function ($user, $password) {
                $this->resetPasswordUser($user, $password);
            }
        );

        if($response == PasswordBroker::PASSWORD_RESET){
            return response()->json(['message' => trans(PasswordBroker::PASSWORD_RESET)]);
        }
        else{
            return response()->json(['message' => trans('passwords.token_user')], 403);
        }
    }

    public function resetPasswordUser($user, $password){
        if($user != null){
            $user->password = $password;
            $user->save();
        }
    }

}
