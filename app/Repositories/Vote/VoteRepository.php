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
        $data = $this->model->with(['owner:id,full_name,email','options' => function ($q) {
            $q->with('listUpVote:id,full_name,email');
        }])->where('id', $id)->first();

        return [
            'success' => true,
            'data' => $data
        ];
    }

    public function updateVoteTitle($request, $id)
    {
        $user = JWTAuth::user();
        $vote = $this->model->where('user_id', $user->id)->where('id', $id)->first();

        try {
            if ($vote) {
                $vote->title = $request->title;
                $vote->save();

                return [
                    'success' => true
                ];
            }

            return  [
                'success' => false,
                'message' => Constant::CLIENT_ERROR
            ];
        } catch (\Exception $exception) {
            return  [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function addOptions($request, $id)
    {
        $user = JWTAuth::user();
        $vote = $this->model->where('id' , $id)->where('user_id', $user->id)->first();
        $optionsData = $request->options;

        try {
            if ($vote) {
                $options = [];
                foreach ($optionsData as $option) {
                    $options[] = [
                        'title' => $option,
                        'vote_id' => $vote->id
                    ];
                }
                DB::table('options')->insert($options);

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

    public function listVotes($request)
    {
        $perPage = $request->has('perPage') ? $request->perPage : Constant::PER_PAGE_DEFAULT;
        $votes = $this->model->with(['owner:id,full_name,email', 'options' => function($q) {
            $q->with('listUpVote:id,full_name,email');
        }]);

        if (!empty($request['title'])) {
            $votes->where('title', 'LIKE', '%' . $request['title'] . '%');
        }

        $data = $votes->orderBy('id', 'desc')->paginate($perPage)->toArray();
        $listVotes = $data['data'];
        unset($data['data']);
        $data['listVotes'] = $listVotes;

        return [
            'success' => true,
            'data' => $data
        ];
    }
}
