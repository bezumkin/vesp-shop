<?php

namespace App\Controllers\Traits;

use App\Models\Product;
use Psr\Http\Message\ResponseInterface;

trait ProductPropertyController
{
    protected ?Product $product;

    public function checkScope(string $method): ?ResponseInterface
    {
        if ($check = parent::checkScope($method)) {
            return $check;
        }

        if (!$this->product = Product::query()->find($this->getProperty('product_id'))) {
            return $this->failure('', 404);
        }

        return null;
    }
}