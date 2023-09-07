<?php

namespace App\Controllers\Security;

use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\Controller;

class Activate extends Controller
{
    public function post(): ResponseInterface
    {
        $username = trim($this->getProperty('username', ''));
        $code = trim($this->getProperty('code', ''));

        /** @var User $user */
        if (!$user = User::query()->where('username', $username)->first()) {
            return $this->failure('', 404);
        }
        if (!$user->activatePassword($code)) {
            return $this->failure('', 403);
        }
        $token = $user->createToken($this->request->getAttribute('ip_address'));

        return $this->success(['token' => $token->token]);
    }
}