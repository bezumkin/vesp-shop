<?php

namespace App\Controllers\User;

use App\Models\User;
use Psr\Http\Message\ResponseInterface;

class Profile extends \Vesp\Controllers\User\Profile
{
    protected $scope = 'profile';
    /** @var User */
    protected $user;

    public function get(): ResponseInterface
    {
        if ($this->user) {
            $data = $this->user->toArray();
            $data['scope'] = $this->user->role->scope;
            $data['cart'] = $this->user->cart->id ?? null;

            return $this->success(['user' => $data]);
        }

        return $this->failure('Authentication required', 401);
    }

    public function patch(): ResponseInterface
    {
        foreach (['username', 'fullname', 'password', 'email'] as $key) {
            if ($value = trim($this->getProperty($key, ''))) {
                $this->user->setAttribute($key, $value);
            }
        }
        $this->user->save();

        return $this->get();
    }
}
