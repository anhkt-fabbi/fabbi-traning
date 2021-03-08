<?php

namespace App\Http\Controllers\Api;

use App\Enums\Constant;
use App\Enums\ErrorType;
use App\Http\Requests\User\ChangePasswordRequest;
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
}
