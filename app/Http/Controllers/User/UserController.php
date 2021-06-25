<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required|confirmed',
        ]);

        $user=new User();
        $user->fill($request->only(['name','email']));
        $user['password']=Hash::make($request->password);
        $user->save();

        return response()->json([
            "status"=>1,
            "message"=>"registered",
            "user"=>$user
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required',
        ]);

        $user=User::where('email',$request->email)->firstOrFail();
        if($user)
        {
            if(Hash::check($request->password,$user->password))
            {

                $token=$user->createToken('auth_token')->plainTextToken;

                return response()->json([
                "status"=>1,
                "message"=>"User Logged in",
                "token"=>$token,
                 ]);
            }
        }

        return response()->json([
            "status"=>0,
            "message"=>"Invalid credentials",
        ]);
    }

    public function profile(Request $request)
    {
        return response()->json([
            "status"=>1,
            "profile"=>auth()->user()
        ]);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "status"=>1,
            "message"=>"logged out"
        ]);
    }
}
