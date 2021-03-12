<?php

namespace App\Http\Controllers\Api;

use App\Enums\Constant;
use App\Enums\ErrorType;
use App\Http\Requests\Vote\AddOptionRequest;
use App\Http\Requests\Vote\UpdateTitleRequest;
use App\Repositories\Vote\VoteRepositoryInterface;
use Illuminate\Http\Request;

class VoteController extends ApiController
{
    private $voteRepository;

    public function __construct(VoteRepositoryInterface $voteRepository)
    {
        $this->voteRepository = $voteRepository;
    }

    public function show($id)
    {
        $result = $this->voteRepository->show($id);
        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000);
        }

        return $this->sendSuccess($result['data']);
    }

    public function updateVoteTitle(UpdateTitleRequest $request, $id)
    {
        $result = $this->voteRepository->updateVoteTitle($request, $id);
        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000);
        }

        return $this->sendSuccess();
    }

    public function addOptions(AddOptionRequest $request, $id)
    {
        $result = $this->voteRepository->addOptions($request, $id);
        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000);
        }

        return $this->sendSuccess();
    }

    public function listVotes(Request $request)
    {
        $result = $this->voteRepository->listVotes($request);
        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000);
        }

        return $this->sendSuccess($result['data']);
    }
}
