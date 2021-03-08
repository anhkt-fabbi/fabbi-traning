<?php

namespace App\Http\Controllers\Api;

use App\Models\SocialAccount;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends ApiController
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
        return $this->sendSuccess([
            'url' => Socialite::driver($social)->stateless()->redirect()->getTargetUrl()
        ]);
    }

    public function callbackSocial($social)
    {
        $socialUser = Socialite::driver($social)->stateless()->user();
        $user = User::where('email', $socialUser->email)->first();
        if (is_null($user)) {
            DB::beginTransaction();
            try {
                $userCreated = User::create([
                    'email' => $socialUser->email,
                    'name' => $socialUser->name
                ]);

                SocialAccount::create([
                    'user_id' => $userCreated->id,
                    'social_id' => $socialUser->getId(),
                    'social_provider' => $social,
                    'social_name' => $socialUser->getName()
                ]);

                $token = JWTAuth::fromUser($userCreated);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();

                return [
                    'success' => false,
                ];
            }
        } else {
            SocialAccount::create([
                'user_id' => $user->id,
                'social_id' => $socialUser->getId(),
                'social_provider' => $social,
                'social_name' => $socialUser->getName()
            ]);

            $token = JWTAuth::fromUser($user);
        }

        return $this->sendSuccess([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('api')->factory()->getTTL() * 60
        ]);

    }
}
