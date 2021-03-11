<?php

namespace App\Repositories\User;

use App\Repositories\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface {
    public function changePassword($data);
    public function register($data);
    public function createVote($data);
    public function upVote($data);
    public function deleteVote($id);
    public function updateVote($request, $id);
    public function listVoteOfUser($request, $id);
    public function unUpVote($request);
}
