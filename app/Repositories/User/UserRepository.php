<?php

namespace App\Repositories\User;

use App\Enums\ErrorType;
use App\Models\User;
use App\Repositories\RepositoryAbstract;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserRepository extends RepositoryAbstract implements UserRepositoryInterface
{
    public function getModel()
    {
        return User::class;
    }

    public function changePassword($data)
    {
        $user = JWTAuth::user();

        if (Hash::check($data->oldPassword, $user->password)) {
            if ($data->newPassword == $data->confirmPassword) {
                $user->password = bcrypt($data->newPassword);
                try {
                    $user->save();
                    auth()->logout();

                    return [
                        'success' => true
                    ];
                } catch (\Exception $exception) {
                    return [
                        'success' => false
                    ];
                }
            }
        }

        return [
            'success' => false
        ];
    }
}
