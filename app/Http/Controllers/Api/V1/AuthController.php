<?php

namespace App\Http\Controllers\Api\V1;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ProfileResource;

class AuthController extends Controller
{
    public function login(LoginRequest $request) 
    {
        $userByEmail = User::where('email', $request->email)->first();
        if(!$userByEmail) {
            return response()->json([
                'message' => 'Bad email'
            ], 401);
        } 
        if(!Hash::check($request->password, $userByEmail->password)) {
            return response()->json([
                'message' => 'Bad password'
            ], 401);
        }
        $credentials = $request->only(['email', 'password']);
        if(Auth::attempt($credentials)) {
            $user = $request->user();
            $permissions = self::setPermissions($user->role->name);
            $tokenData = $user->createToken($user->email.'-'.now(), $permissions);
            $token = $tokenData->token;
            
            if($request->remember_me) {
                $token->expires_at = Carbon::now()->addWeeks(1);
            }
            
            if($token->save()) {
                return response()->json([
                    'message' => 'Good',
                    'user' => $user,
                    'token_type' => 'Bearer',
                    'access_token' => $tokenData->accessToken,
                    'token_scope' => $token->scopes,
                    'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
                ], 200);
            } 
        }
        return response()->json([
            'message' => 'Fail'
        ], 500);
    }

    public function setPermissions($role) {
        switch($role) {
            case 'admin':
                return ['operator', 'admin'];
                break;
            case 'operator':
                return ['operator'];
                break;
        }
    }

    public function profile(Request $request) {
        if($user = $request->user()) {
            return response()->json(new ProfileResource($user), 200);
        }
        return response()->json([
            'message' => 'Fail'
        ], 500);
    }

    public function logout(Request $request) 
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Logout successfully!',
        ], 200);
    }
}
