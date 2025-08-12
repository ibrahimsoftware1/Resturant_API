<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use App\permissions\Abilities;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponse;
    public function login(LoginUserRequest $request){

        if(!Auth::attempt($request->only('email','password'))){

            return $this->error('Invalid Credentials',401);
        }

        $user=User::query()->firstWhere('email',$request->email);
        return $this->ok(
            'Authenticated successfully',
            [
                'token'=>$user->createToken('API Token for : '.$user->email,
                Abilities::getAbilities($user),//abilities
                now()->addMonth())->plainTextToken]
        );

    }

    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();
        return $this->ok('You logged out successfully');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        return $this->ok(
            'User registered successfully',
            [
                'token' => $user->createToken(
                    'API Token for ' . $user->email,
                    [],
                    now()->addMonth()
                )->plainTextToken
            ]
        );
    }

}
