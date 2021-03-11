<?php


namespace App\Repositories\Option;


use App\Models\Option;
use App\Repositories\RepositoryAbstract;
use Tymon\JWTAuth\Facades\JWTAuth;

class OptionRepository extends RepositoryAbstract implements OptionRepositoryInterface
{
    public function model()
    {
        return Option::class;
    }

    public function updateOption($request, $id)
    {
        $optionData = $request->option;
        $voteId = $request->voteId;
        $user = JWTAuth::user();
        $option = $this->model->findOrFail($id);
        $vote = $option->vote->where('user_id', $user->id)->first();

        try {
            if ($vote && $vote->id === $voteId) {
                $option->option = $optionData;
                $option->save();
                return [
                    'success' => true
                ];
            }

            return [
                'success' => false
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }
}
