<?php

namespace App\Controllers\Security;

use App\Models\User;
use App\Models\UserToken;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;

/**
 * @property User $user
 */
class Logout extends Controller
{
    protected string|array $scope = 'profile';

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
