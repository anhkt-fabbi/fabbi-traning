<?php

namespace App\Http\Controllers\Api;

use App\Enums\Constant;
use App\Enums\ErrorType;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\CreateVoteRequest;
use App\Http\Requests\User\OfferVoteRequest;
use App\Http\Requests\User\UnUpVote;
use App\Http\Requests\User\UpVoteRequest;
use App\Http\Requests\User\UserRegisterRequest;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAccount()
    {
        $result = $this->userRepository->getAccount();
        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000);
        }

        return $this->sendSuccess($result['data']);
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $result = $this->userRepository->changePassword($request);
        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000);
        }

        return $this->sendSuccess();
    }

    public function register(UserRegisterRequest $request)
    {
        $result = $this->userRepository->register($request);

        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000);
        }

        return $this->sendSuccess();
    }

    public function createVote(CreateVoteRequest $request)
    {
        $result = $this->userRepository->createVote($request);

        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000, $result['message']);
        }

        return $this->sendSuccess();
    }

    public function offerVote(OfferVoteRequest $request)
    {
        $result = $this->userRepository->offerVote($request);

        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000, $result['message']);
        }

        return $this->sendSuccess();
    }

    public function upVote(UpVoteRequest $request)
    {
        $result = $this->userRepository->upVote($request);

        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000, $result['message']);
        }

        return $this->sendSuccess();
    }

    public function deleteVote($id)
    {
        $result = $this->userRepository->deleteVote($id);

        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000, $result['message']);
        }

        return $this->sendSuccess();
    }

    public function listVote(Request $request)
    {
        $result = $this->userRepository->listVote($request);

        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000, $result['message']);
        }

        return $this->sendSuccess($result['data']);
    }

    public function listVoteOfUser(Request $request, $id)
    {
        $result = $this->userRepository->listVoteOfUser($request, $id);

        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000, $result['message']);
        }

        return $this->sendSuccess($result['data']);
    }

    public function unUpVote(UnUpVote $request)
    {
        $result = $this->userRepository->unUpVote($request);
        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000, $result['message']);
        }

        return $this->sendSuccess();
    }
}
