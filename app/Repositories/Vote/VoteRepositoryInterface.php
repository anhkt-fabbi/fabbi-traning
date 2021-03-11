<?php


namespace App\Repositories\Vote;


use App\Repositories\RepositoryInterface;

interface VoteRepositoryInterface extends RepositoryInterface
{
    public function show($id);
    public function updateVoteTitle($request, $id);
    public function addOptions($request, $id);
}
