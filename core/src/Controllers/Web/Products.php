<?php

namespace App\Controllers\Web;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Vesp\Controllers\ModelController;

class Products extends ModelController
{
    protected $model = Product::class;

    protected function beforeGet(Builder $c): Builder
    {
        $c->where('active', true);
        $c->whereHas('category', static function (Builder $c) {
            $c->where('active', true);
        });

        return $c;
    }

    protected function beforeCount(Builder $c): Builder
    {
        return $this->beforeGet($c);
    }

    protected function afterCount(Builder $c): Builder
    {
        $c->with('category:id,title,alias');
        $c->with('firstFile');

        return $c;
    }

    protected function getPrimaryKey(): ?array
    {
        return null;
    }
}