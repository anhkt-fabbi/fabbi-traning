<?php

namespace App\Repositories\User;

use App\Enums\ErrorType;
use App\Models\User;
use App\Repositories\RepositoryAbstract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;
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

    public function register($data)
    {
        $userData = $data->only(['email', 'fullName', 'name', 'password']);
        $userData['password'] = bcrypt($userData['password']);
        $userData['full_name'] = $userData['fullName'];

        try {
            $this->model->create($userData);

            return [
                'success' => true
            ];
        } catch (Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function createVote($data)
    {
        $user = JWTAuth::user();
        $title = $data->only('title');
        $optionsData = $data->options;
        $optionsVote = [];

        DB::beginTransaction();
        try {
            $vote = $user->votes()->create($title);
            foreach ($optionsData as $option) {
                $optionsVote[] = [
                    'option' => $option,
                    'vote_id' => $vote->id
                ];
            }
            DB::table('options')->insert($optionsVote);

            DB::commit();
            return [
                'success' => true
            ];
        } catch (\Exception $exception) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }
}
