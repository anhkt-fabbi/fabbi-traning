<?php


namespace App\Repositories\Vote;


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

    public function listVotes()
    {
        $data = $this->model->with(['options' => function($q) {
            $q->withCount('users as qty');
        }])->get();

        return [
            'success' => true,
            'data' => $data
        ];
    }
}
