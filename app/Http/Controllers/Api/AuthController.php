<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    private $guard;

    public function __construct()
    {
        $this->guard = auth()->guard('api');
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request['email'],
            'password' => $request['password']
        ];

        if (! $token = $this->guard->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function redirectSocial($social)
    {
        return response()->json([
            'url' => Socialite::driver($social)->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    public function callbackSocial($social)
    {
        dd("asd");
        $user = Socialite::driver($social)->stateless()->user();
        Log::info($user->token);
        dd($user);
    }
}
