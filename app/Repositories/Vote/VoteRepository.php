<?php


namespace App\Repositories\Vote;


use App\Enums\Constant;
use App\Models\Vote;
use App\Repositories\RepositoryAbstract;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class VoteRepository extends RepositoryAbstract implements VoteRepositoryInterface
{
    public function getModel()
    {
        return Vote::class;
    }

    public function listVotes($request)
    {
        $perPage = $request->has('perPage') ? $request->perPage : Constant::PER_PAGE_DEFAULT;
        $data = $this->model->with(['options' => function($q) {
            $q->withCount('users as qty');
        }])->orderBy('id', 'desc')->take($perPage)->get();

        return [
            'success' => true,
            'data' => $data
        ];
    }
}
