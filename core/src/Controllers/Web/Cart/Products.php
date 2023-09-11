<?php

namespace App\Controllers\Web\Cart;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelController;

class Products extends ModelController
{
    protected $model = CartProduct::class;
    protected $primaryKey = ['cart_id', 'product_key'];

    protected ?Cart $cart;

    public function checkScope(string $method): ?ResponseInterface
    {
        if ($method === 'options') {
            return null;
        }

        $id = $this->getProperty('cart_id');
        if (!$id || !$this->cart = Cart::query()->find($id)) {
            return $this->failure('', 404);
        }
        if (!$this->cart->user_id && $this->user) {
            $this->cart->update(['user_id' => $this->user->id]);
        }

        return null;
    }

    public function put(): ResponseInterface
    {
        $id = $this->getProperty('id');
        /** @var Product $product */
        if (!$id || !$product = Product::query()->where('active', true)->find($id)) {
            return $this->failure('Not Found', 404);
        }

        $productKey = CartProduct::generateKey($product, $this->getProperty('options'));
        /** @var CartProduct $item */
        if ($item = $this->cart->cartProducts()->where('product_key', $productKey)->first()) {
            $item->amount += $this->getProperty('amount', 1);
        } else {
            $item = new CartProduct([
                'cart_id' => $this->cart->id,
                'product_key' => $productKey,
                'product_id' => $product->id,
                'amount' => $this->getProperty('amount', 1),
                'options' => $this->getProperty('options'),
            ]);
        }
        $item->save();

        return $this->get();
    }

    public function patch(): ResponseInterface
    {
        parent::patch();
        $this->unsetProperty('product_key');

        return $this->get();
    }

    public function delete(): ResponseInterface
    {
        parent::delete();
        $this->unsetProperty('product_key');

        return $this->get();
    }

    protected function beforeCount(Builder $c): Builder
    {
        $c->where('cart_id', $this->cart->id);
        $c->whereHas('product', static function (Builder $c) {
            $c->where('active', true);
        });

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->with(
            'product:id,price,weight,uri',
            'product.firstFile',
            'product.translations:product_id,lang,title'
        );

        return $c;
    }

    protected function getPrimaryKey(): ?array
    {
        $key = [];
        foreach ($this->primaryKey as $item) {
            if (!$value = $this->getProperty($item)) {
                return null;
            }
            $key[$item] = $value;
        }

        return $key;
    }
}