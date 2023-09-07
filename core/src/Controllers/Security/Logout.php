<?php

namespace App\Controllers\Security;

use App\Models\User;
use App\Models\UserToken;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;

class Logout extends Controller
{
    /** @var User $user */
    protected $user;
    protected $scope = 'profile';

    public function post(): ResponseInterface
    {
        /** @var UserToken $userToken */
        if ($userToken = $this->user->tokens()->find($this->request->getAttribute('token'))) {
            $userToken->active = false;
            $userToken->save();
        }

        return $this->success();
    }
}
