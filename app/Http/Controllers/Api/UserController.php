<?php

namespace App\Http\Controllers\Api;

use App\Enums\Constant;
use App\Enums\ErrorType;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\CreateVoteRequest;
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

    public function changePassword(ChangePasswordRequest $request)
    {
        $result = $this->userRepository->changePassword($request);
        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000);
        }

        return $this->sendSuccess(null, Constant::SUCCESS);
    }

    public function register(UserRegisterRequest $request)
    {
        $result = $this->userRepository->register($request);

        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000);
        }

        return $this->sendSuccess(null, Constant::SUCCESS);
    }

    public function createVote(CreateVoteRequest $request)
    {
        $result = $this->userRepository->createVote($request);

        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000, $result['message']);
        }

        return $this->sendSuccess(null, Constant::SUCCESS);
    }

    public function upVote(UpVoteRequest $request)
    {
        $result = $this->userRepository->upVote($request);

        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000, $result['message']);
        }

        return $this->sendSuccess(null, Constant::SUCCESS);
    }
}
