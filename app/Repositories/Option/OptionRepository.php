<?php


namespace App\Repositories\Option;


use App\Models\Option;
use App\Repositories\RepositoryAbstract;

class OptionRepository extends RepositoryAbstract implements OptionRepositoryInterface
{
    public function model()
    {
        return Option::class;
    }

}
