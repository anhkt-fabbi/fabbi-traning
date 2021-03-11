<?php


namespace App\Repositories\Vote;


use App\Enums\Constant;
use App\Models\User;
use App\Models\Vote;
use App\Repositories\RepositoryAbstract;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class VoteRepository extends RepositoryAbstract implements VoteRepositoryInterface
{
    public function model()
    {
        return Vote::class;
    }

    public function show($id)
    {
        $data = $this->model->with(['user:id,full_name,email','options' => function ($q) {
            $q->with('users:id,full_name,email')->get();
        }])->where('id', $id)->get();

        return [
            'success' => true,
            'data' => $data
        ];
    }

    public function updateVoteTitle($request, $id)
    {
        $user = JWTAuth::user();
        $vote = $this->model->where('user_id', $user->id)->where('id', $id)->first();

        if ($vote) {
            $vote->title = $request->title;
            $vote->save();

            return [
                'success' => true
            ];
        }

        return  [
            'success' => false
        ];
    }

    public function addOptions($request, $id)
    {
        $vote = $this->model->findOrFail($id);
        $optionsData = $request->options;

        try {
            $options = [];
            foreach ($optionsData as $option) {
                $options[] = [
                    'option' => $option,
                    'vote_id' => $vote->id
                ];
            }
            DB::table('options')->insert($options);

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
}
