<?php

namespace App\Http\Controllers\Api;

use App\Enums\Constant;
use App\Enums\ErrorType;
use App\Repositories\Vote\VoteRepositoryInterface;
use Illuminate\Http\Request;

class VoteController extends ApiController
{
    private $voteRepository;

    public function __construct(VoteRepositoryInterface $voteRepository)
    {
        $this->voteRepository = $voteRepository;
    }

    public function listVotes(Request $request)
    {
        $result = $this->voteRepository->listVotes($request);
        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000);
        }

        return $this->sendSuccess($result['data'], Constant::SUCCESS);
    }

    public function show($id)
    {
        $result = $this->voteRepository->show($id);
        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000);
        }

        return $this->sendSuccess($result['data'], Constant::SUCCESS);
    }
}
