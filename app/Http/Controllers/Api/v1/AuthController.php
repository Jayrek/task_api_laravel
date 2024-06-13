<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request)
    {
      try {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);
        
        if($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
                'data' => null,
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 200);
      }
      catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => $validator->errors(),
            'data' => null,
        ], 401);
      }
    }

    public function login(Request $request){
        try{

            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors(),
                    'data' => null,
                ], 422);
            }

            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid login details',
                    'data' => null,
                ], 401);
            }

            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ], 200);
        }
        catch (\Exception $e){
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ], 401);
        }
    }

    public function profile(Request $request){
        return response()->json([
            'status' => true,
            'message' => 'success',
            'data' => $request->user(),
        ], 200);
    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => 'Token Revoked',
            'data' => null,
        ], 200);
    }
}
