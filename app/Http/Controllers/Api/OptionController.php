<?php

namespace App\Http\Controllers\Api;

use App\Enums\Constant;
use App\Enums\ErrorType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Option\UpdateOptionRequest;
use App\Repositories\Option\OptionRepositoryInterface;
use Illuminate\Http\Request;

class OptionController extends ApiController
{
    private $optionRepository;

    public function __construct(OptionRepositoryInterface $optionRepository)
    {
        $this->optionRepository = $optionRepository;
    }

    public function updateOptions(UpdateOptionRequest $request, $id)
    {
        $result = $this->optionRepository->updateOption($request, $id);
        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000);
        }

        return $this->sendSuccess();
    }

    public function deleteOption($id)
    {
        $result = $this->optionRepository->deleteOption($id);
        if (!$result['success']) {
            return $this->sendError(ErrorType::CODE_5000, ErrorType::STATUS_5000);
        }

        return $this->sendSuccess();
    }
}
