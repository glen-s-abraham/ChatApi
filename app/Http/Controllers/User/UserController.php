<?php

namespace App\Http\Controllers\User;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Traits\UserStatusTraits;
use App\Repositories\Interfaces\BroadcastRepositoryInterface;
use App\Repositories\Interfaces\UserStatusRepositoryInterface;
use App\Http\Requests\UserStoreRequest;
class UserController extends Controller
{
    use UserStatusTraits;

    private $broadcastRepositoryInterface;
    private $userStatusRepositoryInterface;

    public function __construct(
        BroadcastRepositoryInterface $broadcastRepositoryInterface,
        UserStatusRepositoryInterface $userStatusRepositoryInterface
    )
    {
        $this->broadcastRepositoryInterface=$broadcastRepositoryInterface;
        $this->userStatusRepositoryInterface=$userStatusRepositoryInterface;
    }

    public function register(UserStoreRequest $request)
    {
        $user=new User();
        $user->fill($request->only(['name','email']));
        $user['password']=Hash::make($request->password);
        $user->save();

        $this->broadcastRepositoryInterface->createBroadcastChannel($user->id);
        $this->userStatusRepositoryInterface->setStatusToOffline($user->id);

        return response()->json([$user]);
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

                $this->setStatusToOnline($user->id);

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
        $this->setStatusToOffline(auth()->user()->id);
        return response()->json([
            "status"=>1,
            "message"=>"logged out"
        ]);
    }
}
