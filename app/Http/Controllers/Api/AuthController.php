<?php

namespace App\Http\Controllers\Api;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use ApiResponse;
    public function login(LoginUserRequest $request){

        if(!Auth::attempt($request->only('email','password'))){

            return $this->error('Invalid Credentials',401);
        }

        $user=User::query()->firstWhere('email',$request->email);
        return $this->ok(
            'Login Success',
            [
                'token'=>$user->createToken('API Token for : '.$user->email,
                ['*'],//abilities
                now()->addMonth())->plainTextToken]);

    }

    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();
        return $this->ok('You logged out successfully');
    }
}
