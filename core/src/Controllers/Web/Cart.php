<?php

namespace App\Controllers\Web;

use App\Models\Cart as CartModel;
use App\Models\User;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelController;

/**
 * @property User $user
 */
class Cart extends ModelController
{
    protected string $model = CartModel::class;

    public function put(): ResponseInterface
    {
        if ($this->user && $this->user->cart) {
            $cart = $this->user->cart;
        } else {
            $cart = new CartModel();
            $cart->user_id = $this->user->id ?? null;
            $cart->save();
        }

        return $this->success($cart->only('id'));
    }

    public function get(): ResponseInterface
    {
        return $this->failure('Method Not Allowed', 405);
    }

    public function patch(): ResponseInterface
    {
        return $this->failure('Method Not Allowed', 405);
    }
}