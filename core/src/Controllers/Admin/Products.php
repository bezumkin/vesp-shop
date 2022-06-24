<?php

namespace App\Controllers\Admin;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelController;

class Products extends ModelController
{
    protected $scope = 'products';
    protected $model = Product::class;

    protected function beforeCount(Builder $c): Builder
    {
        if ($query = $this->getProperty('query')) {
            $c->where(static function (Builder $c) use ($query) {
                $c->where('title', 'LIKE', "%$query%");
                $c->orWhere('description', 'LIKE', "%$query%");
            });
        }

        return $c;
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->with('category:id,title');
        $c->with('firstFile');

        return $c;
    }
}