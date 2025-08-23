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



    /**
     *
     * @OA\Post(
     * path="/api/login",
     * summary="Login a user",
     * tags={"Auth"},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"email","password"},
     * @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     * @OA\Property(property="password", type="string", format="password", example="secret123")
     * )
     * ),
     * @OA\Response(response=200, description="Authenticated successfully"),
     * @OA\Response(response=401, description="Invalid credentials")
     * )
     **/
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
/**
* @OA\Post(
*     path="/api/logout",
*     summary="Logout the authenticated user",
*     tags={"Auth"},
*     security={{"sanctum":{}}},
 *     @OA\Response(response=200, description="Logged out successfully")
* )
 */
    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();
        return $this->ok('You logged out successfully');
    }


    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register a new user",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User registered successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     *
     * */
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

