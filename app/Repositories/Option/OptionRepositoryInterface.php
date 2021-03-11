<?php


namespace App\Repositories\Option;


use App\Repositories\RepositoryInterface;

interface OptionRepositoryInterface extends RepositoryInterface
{
        public function updateOption($request, $id);
}
