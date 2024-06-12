<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{


    public function register(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $users = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $users->createToken('auth_token')->plainTextToken;
        return response()->json(['access_token'=>$token, 'token_type'=>'Bearer']);
    }

    public function login (Request $request)
    {
        $request->validate([
            'email'=>'required|email|string',
            'password'=>'required|string'
        ]);

        if(Auth::attempt($request->only('email' , 'password'))){
            $user = User::where('email', $request->email)->first();

            $user = Auth::user();
            $token = $user->createToken("API token for{$request->email}")->plainTextToken;

            return response()->json(["token"=>$token, "user"=>$user]);
        }
        return response()->json('Invalid Credentials');
        
    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Makakalaya ka na']);
    }


}
