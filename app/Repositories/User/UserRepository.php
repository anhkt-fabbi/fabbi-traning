<?php

namespace App\Repositories\User;

use App\Enums\Constant;
use App\Enums\ErrorType;
use App\Models\Option;
use App\Models\User;
use App\Models\Vote;
use App\Repositories\RepositoryAbstract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;
use Tymon\JWTAuth\Facades\JWTAuth;


class UserRepository extends RepositoryAbstract implements UserRepositoryInterface
{
    public function model()
    {
        return User::class;
    }

    public function changePassword($data)
    {
        $user = JWTAuth::user();

        if (Hash::check($data->oldPassword, $user->password)) {
            $user->password = bcrypt($data->newPassword);
            try {
                $user->save();
                auth()->logout();

                return [
                    'success' => true
                ];
            } catch (\Exception $exception) {
                return [
                    'success' => false,
                    'message' => $exception->getMessage()
                ];
            }
        }

        return [
            'success' => false,
            'message' => Constant::CLIENT_ERROR
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
        $options = [];

        DB::beginTransaction();
        try {
            $vote = $user->votes()->create($title);
            foreach ($optionsData as $option) {
                $options[] = [
                    'title' => $option,
                    'vote_id' => $vote->id
                ];
            }
            DB::table('options')->insert($options);

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

    public function offerVote($request)
    {
        $voteId = $request->voteId;
        $options = $request->options;

        try {
            $vote = Vote::findOrFail($voteId);
            if ($vote) {
                $insertOptions = [];
                foreach ($options as $option) {
                    $insertOptions[] = [
                        'vote_id' => $voteId,
                        'title' => $option
                    ];
                }
                DB::table('options')->insert($insertOptions);

                return [
                    'success' => true
                ];
            }

            return [
                'success' => false,
                'message' => Constant::CLIENT_ERROR
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function upVote($data)
    {
        $optionId = $data->optionId;
        $user = JWTAuth::user();
        $voteId = $data->voteId;

        try {
            $option = Option::findOrFail($optionId);
            $optionUser = DB::table('option_users')
                ->where('user_id', $user->id)
                ->where('option_id', $optionId)->first();
            if ($option->vote->id == $voteId && $optionUser == null) {
                $user->options()->attach($optionId);

                return [
                    'success' => true
                ];
            }

            return [
                'success' => false,
                'message' => Constant::CLIENT_ERROR
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function deleteVote($id)
    {
        $user = JWTAuth::user();
        $vote = Vote::where('user_id', $user->id)
            ->where('id', $id)->first();

        DB::beginTransaction();
        try {
            if ($vote) {
                foreach ($vote->options as $option) {
                    $option->users()->detach();
                }
                $vote->options()->delete();
                $vote->delete();

                DB::commit();
                return [
                    'success' => true
                ];
            }

            return [
                'success' => false,
                'message' => Constant::CLIENT_ERROR
            ];
        } catch (\Exception $exception) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function updateVote($request, $id)
    {
        $user = JWTAuth::user();
        $vote = Vote::where('id', $id)->where('user_id', $user->id)->first();

        try {
            if ($vote) {
                $vote->title = $request->title;
                $vote->save();

                return [
                    'success' => true
                ];
            }

            return [
                'success' => false,
                'message' => Constant::CLIENT_ERROR
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function listVote($request)
    {
        $perPage = $request->has('perPage') ? $request->perPage : Constant::PER_PAGE_DEFAULT;
        $user = JWTAuth::user();

        $data = Vote::with(['options' => function ($q) {
            $q->with('listUpVote:id,full_name as fullName,email');
        }])->where('user_id', $user->id);
        if (!empty($request['title'])) {
            $data->where('title', 'like', '%' . $request['title'] . '%');
        }

        return [
            'success' => true,
            'data' => [
                'owner' => $user,
                'votes' => $data->orderBy('id', 'desc')->paginate($perPage)
            ]
        ];
    }

    public function listVoteOfUser($request, $id)
    {
        $perPage = $request->has('perPage') ? $request->perPage : Constant::PER_PAGE_DEFAULT;
        $user = $this->model->findOrFail($id);

        $data = Vote::with(['options' => function ($q) {
            $q->with('listUpVote:id,full_name as fullName,email');
        }])->where('user_id', $user->id);
        if (!empty($request['title'])) {
            $data->where('title', 'like', $request['title']);
        }

        return [
            'success' => true,
            'data' => [
                'owner' => $user,
                'votes' => $data->orderBy('id', 'desc')->paginate($perPage)
            ]
        ];
    }

    public function unUpVote($request)
    {
        $user = JWTAuth::user();
        $optionId = $request->optionId;

        try {
            $user->options()->detach($optionId);

            return [
                'success' => true
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function getAccount()
    {
        $user = JWTAuth::user();

        return [
            'success' => true,
            'data' => $user
        ];
    }
}
