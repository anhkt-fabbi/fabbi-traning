<?php


namespace App\Repositories\Option;


use App\Enums\Constant;
use App\Models\Option;
use App\Repositories\RepositoryAbstract;
use Illuminate\Support\Facades\DB;
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

    public function deleteOption($id)
    {
        $user = JWTAuth::user();
        $option = $this->model->findOrFail($id);
        $vote = $option->vote;

        DB::beginTransaction();
        try {
            if ($vote->user_id == $user->id) {
                $option->users()->detach();
                $option->delete();

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
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }
}
